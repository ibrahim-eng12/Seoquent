<?php

namespace IbrahimEng12\Seoquent\Services;

class SeoManager
{
    protected MetaTagBuilder $meta;
    protected OpenGraphBuilder $openGraph;
    protected TwitterCardBuilder $twitter;
    protected JsonLdBuilder $jsonLd;
    protected SitemapBuilder $sitemap;

    public function __construct()
    {
        $this->meta = new MetaTagBuilder();
        $this->openGraph = new OpenGraphBuilder();
        $this->twitter = new TwitterCardBuilder();
        $this->jsonLd = new JsonLdBuilder();
        $this->sitemap = new SitemapBuilder();
    }

    // -------------------------------------------------------------------------
    // Quick setters (fluent API on the main Seo facade)
    // -------------------------------------------------------------------------

    public function title(string $title): static
    {
        $this->meta->title($title);

        return $this;
    }

    public function description(string $description): static
    {
        $this->meta->description($description);

        return $this;
    }

    public function keywords(array|string $keywords): static
    {
        $this->meta->keywords($keywords);

        return $this;
    }

    public function image(string $url, ?int $width = null, ?int $height = null, ?string $alt = null): static
    {
        $this->openGraph->image($url, $width, $height, $alt);
        $this->twitter->image($url, $alt);

        return $this;
    }

    public function canonical(?string $url): static
    {
        $this->meta->canonical($url);

        return $this;
    }

    public function robots(string $robots): static
    {
        $this->meta->robots($robots);

        return $this;
    }

    public function noIndex(): static
    {
        $this->meta->noIndex();

        return $this;
    }

    public function author(string $author): static
    {
        $this->meta->author($author);

        return $this;
    }

    // -------------------------------------------------------------------------
    // Builder accessors
    // -------------------------------------------------------------------------

    public function meta(): MetaTagBuilder
    {
        return $this->meta;
    }

    public function openGraph(): OpenGraphBuilder
    {
        return $this->openGraph;
    }

    public function twitter(): TwitterCardBuilder
    {
        return $this->twitter;
    }

    public function jsonLd(): JsonLdBuilder
    {
        return $this->jsonLd;
    }

    public function sitemap(): SitemapBuilder
    {
        return $this->sitemap;
    }

    // -------------------------------------------------------------------------
    // Rendering
    // -------------------------------------------------------------------------

    public function renderHead(): string
    {
        $parts = [];

        $parts[] = $this->meta->render();
        $parts[] = $this->openGraph->render($this->meta);
        $parts[] = $this->twitter->render($this->meta, $this->openGraph);

        $jsonLd = $this->jsonLd->render();
        if ($jsonLd) {
            $parts[] = $jsonLd;
        }

        return implode("\n    ", array_filter($parts));
    }

    public function renderJsonLd(): string
    {
        return $this->jsonLd->render();
    }

    // -------------------------------------------------------------------------
    // Bulk set from array (useful for loading from database)
    // -------------------------------------------------------------------------

    public function fromArray(array $data): static
    {
        if (isset($data['title'])) {
            $this->title($data['title']);
        }

        if (isset($data['description'])) {
            $this->description($data['description']);
        }

        if (isset($data['keywords'])) {
            $this->keywords($data['keywords']);
        }

        if (isset($data['canonical'])) {
            $this->canonical($data['canonical']);
        }

        if (isset($data['robots'])) {
            $this->robots($data['robots']);
        }

        if (isset($data['author'])) {
            $this->author($data['author']);
        }

        if (isset($data['image'])) {
            $this->image($data['image']);
        }

        if (isset($data['og_type'])) {
            $this->openGraph->type($data['og_type']);
        }

        if (isset($data['twitter_card'])) {
            $this->twitter->card($data['twitter_card']);
        }

        return $this;
    }
}
