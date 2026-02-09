<?php

namespace IbrahimEng12\Seoquent\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class SitemapBuilder
{
    protected array $urls = [];
    protected array $modelSources = [];
    protected bool $cacheEnabled;
    protected int $cacheDuration;
    protected int $maxUrls;

    public function __construct()
    {
        $this->cacheEnabled = config('seoquent.sitemap.cache_enabled', true);
        $this->cacheDuration = config('seoquent.sitemap.cache_duration', 3600);
        $this->maxUrls = config('seoquent.sitemap.max_urls', 50000);
    }

    public function add(
        string $url,
        ?string $lastmod = null,
        ?string $changefreq = null,
        ?float $priority = null,
    ): static {
        $entry = ['loc' => $url];

        if ($lastmod) {
            $entry['lastmod'] = $lastmod;
        }

        $entry['changefreq'] = $changefreq ?? config('seoquent.sitemap.default_frequency', 'weekly');
        $entry['priority'] = $priority ?? config('seoquent.sitemap.default_priority', 0.5);

        $this->urls[] = $entry;

        return $this;
    }

    public function addModel(
        string $modelClass,
        string $routeName,
        ?string $routeKey = null,
        ?string $lastmodColumn = 'updated_at',
        ?string $changefreq = null,
        ?float $priority = null,
        ?\Closure $queryCallback = null,
    ): static {
        $this->modelSources[] = [
            'model' => $modelClass,
            'route_name' => $routeName,
            'route_key' => $routeKey ?? 'slug',
            'lastmod_column' => $lastmodColumn,
            'changefreq' => $changefreq ?? config('seoquent.sitemap.default_frequency', 'weekly'),
            'priority' => $priority ?? config('seoquent.sitemap.default_priority', 0.5),
            'query_callback' => $queryCallback,
        ];

        return $this;
    }

    public function getUrls(): array
    {
        return $this->urls;
    }

    public function getModelSources(): array
    {
        return $this->modelSources;
    }

    public function generate(): string
    {
        if ($this->cacheEnabled) {
            return Cache::remember('seoquent:sitemap', $this->cacheDuration, function () {
                return $this->buildXml();
            });
        }

        return $this->buildXml();
    }

    public function clearCache(): void
    {
        Cache::forget('seoquent:sitemap');
    }

    protected function buildXml(): string
    {
        $allUrls = $this->urls;

        foreach ($this->modelSources as $source) {
            $query = $source['model']::query();

            if ($source['query_callback']) {
                $query = call_user_func($source['query_callback'], $query);
            }

            $query->each(function ($model) use ($source, &$allUrls) {
                $routeKey = $model->{$source['route_key']};
                $url = route($source['route_name'], $routeKey);

                $entry = [
                    'loc' => $url,
                    'changefreq' => $source['changefreq'],
                    'priority' => $source['priority'],
                ];

                if ($source['lastmod_column'] && $model->{$source['lastmod_column']}) {
                    $entry['lastmod'] = $model->{$source['lastmod_column']}->toIso8601String();
                }

                $allUrls[] = $entry;
            });
        }

        $allUrls = array_slice($allUrls, 0, $this->maxUrls);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($allUrls as $entry) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($entry['loc'], ENT_XML1) . "</loc>\n";

            if (isset($entry['lastmod'])) {
                $xml .= '    <lastmod>' . $entry['lastmod'] . "</lastmod>\n";
            }

            if (isset($entry['changefreq'])) {
                $xml .= '    <changefreq>' . $entry['changefreq'] . "</changefreq>\n";
            }

            if (isset($entry['priority'])) {
                $xml .= '    <priority>' . number_format($entry['priority'], 1) . "</priority>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
