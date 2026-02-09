<?php

namespace IbrahimEng12\Seoquent\Facades;

use IbrahimEng12\Seoquent\Services\JsonLdBuilder;
use IbrahimEng12\Seoquent\Services\MetaTagBuilder;
use IbrahimEng12\Seoquent\Services\OpenGraphBuilder;
use IbrahimEng12\Seoquent\Services\SeoManager;
use IbrahimEng12\Seoquent\Services\SitemapBuilder;
use IbrahimEng12\Seoquent\Services\TwitterCardBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static SeoManager title(string $title)
 * @method static SeoManager description(string $description)
 * @method static SeoManager keywords(array|string $keywords)
 * @method static SeoManager image(string $url, ?int $width = null, ?int $height = null, ?string $alt = null)
 * @method static SeoManager canonical(?string $url)
 * @method static SeoManager robots(string $robots)
 * @method static SeoManager noIndex()
 * @method static SeoManager author(string $author)
 * @method static SeoManager fromArray(array $data)
 * @method static MetaTagBuilder meta()
 * @method static OpenGraphBuilder openGraph()
 * @method static TwitterCardBuilder twitter()
 * @method static JsonLdBuilder jsonLd()
 * @method static SitemapBuilder sitemap()
 * @method static string renderHead()
 * @method static string renderJsonLd()
 *
 * @see SeoManager
 */
class Seo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'seoquent';
    }
}
