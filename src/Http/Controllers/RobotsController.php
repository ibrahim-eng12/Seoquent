<?php

namespace IbrahimEng12\Seoquent\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $lines = [];

        $lines[] = 'User-agent: *';

        $allow = config('seoquent.robots.allow', ['/']);
        foreach ($allow as $path) {
            $lines[] = 'Allow: ' . $path;
        }

        $disallow = config('seoquent.robots.disallow', []);
        foreach ($disallow as $path) {
            $lines[] = 'Disallow: ' . $path;
        }

        $lines[] = '';

        $sitemapUrl = config('seoquent.robots.sitemap_url');
        if (! $sitemapUrl && config('seoquent.sitemap.enabled')) {
            $sitemapRoute = config('seoquent.sitemap.route', 'sitemap.xml');
            $sitemapUrl = url($sitemapRoute);
        }

        if ($sitemapUrl) {
            $lines[] = 'Sitemap: ' . $sitemapUrl;
        }

        $content = implode("\n", $lines);

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
