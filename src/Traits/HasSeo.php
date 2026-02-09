<?php

namespace IbrahimEng12\Seoquent\Traits;

use IbrahimEng12\Seoquent\Facades\Seo;
use IbrahimEng12\Seoquent\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasSeo
{
    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function setSeo(array $data): SeoMeta
    {
        return $this->seoMeta()->updateOrCreate(
            [
                'seoable_type' => get_class($this),
                'seoable_id' => $this->getKey(),
            ],
            $data,
        );
    }

    public function getSeo(): ?SeoMeta
    {
        return $this->seoMeta;
    }

    public function applySeo(): void
    {
        $seoMeta = $this->getSeo();

        if (! $seoMeta) {
            return;
        }

        $data = $seoMeta->toSeoArray();

        // Apply main meta tags
        if (isset($data['title'])) {
            Seo::title($data['title']);
        }

        if (isset($data['description'])) {
            Seo::description($data['description']);
        }

        if (isset($data['keywords'])) {
            Seo::keywords($data['keywords']);
        }

        if (isset($data['canonical'])) {
            Seo::canonical($data['canonical']);
        }

        if (isset($data['robots'])) {
            Seo::robots($data['robots']);
        }

        if (isset($data['author'])) {
            Seo::author($data['author']);
        }

        // Apply Open Graph
        if (isset($data['og_title'])) {
            Seo::openGraph()->title($data['og_title']);
        }

        if (isset($data['og_description'])) {
            Seo::openGraph()->description($data['og_description']);
        }

        if (isset($data['og_type'])) {
            Seo::openGraph()->type($data['og_type']);
        }

        if (isset($data['og_image'])) {
            Seo::openGraph()->image($data['og_image']);
        }

        // Apply Twitter Card
        if (isset($data['twitter_card'])) {
            Seo::twitter()->card($data['twitter_card']);
        }

        if (isset($data['twitter_title'])) {
            Seo::twitter()->title($data['twitter_title']);
        }

        if (isset($data['twitter_description'])) {
            Seo::twitter()->description($data['twitter_description']);
        }

        if (isset($data['twitter_image'])) {
            Seo::twitter()->image($data['twitter_image']);
        }

        // Apply custom JSON-LD schemas
        if ($seoMeta->json_ld) {
            foreach ($seoMeta->json_ld as $schema) {
                Seo::jsonLd()->addSchema($schema);
            }
        }

        // Apply custom meta tags
        if ($seoMeta->custom_meta) {
            foreach ($seoMeta->custom_meta as $name => $content) {
                Seo::meta()->meta($name, $content);
            }
        }
    }

    public function deleteSeo(): bool
    {
        return (bool) $this->seoMeta()->delete();
    }
}
