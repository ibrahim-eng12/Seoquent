<?php

namespace IbrahimEng12\Seoquent\Services;

class JsonLdBuilder
{
    protected array $schemas = [];
    protected bool $enabled;

    public function __construct()
    {
        $this->enabled = config('seoquent.json_ld.enabled', true);
    }

    public function enabled(bool $enabled = true): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function addSchema(array $schema): static
    {
        $this->schemas[] = $schema;

        return $this;
    }

    public function organization(?string $name = null, ?string $url = null, ?string $logo = null): static
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $name ?? config('seoquent.json_ld.organization.name'),
            'url' => $url ?? config('seoquent.json_ld.organization.url'),
        ];

        $logoUrl = $logo ?? config('seoquent.json_ld.organization.logo');
        if ($logoUrl) {
            $schema['logo'] = $logoUrl;
        }

        $this->schemas[] = $schema;

        return $this;
    }

    public function website(?string $name = null, ?string $url = null, ?string $searchUrl = null): static
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $name ?? config('seoquent.json_ld.organization.name'),
            'url' => $url ?? config('seoquent.json_ld.organization.url'),
        ];

        if ($searchUrl) {
            $schema['potentialAction'] = [
                '@type' => 'SearchAction',
                'target' => $searchUrl . '{search_term_string}',
                'query-input' => 'required name=search_term_string',
            ];
        }

        $this->schemas[] = $schema;

        return $this;
    }

    public function webPage(string $title, ?string $description = null, ?string $url = null): static
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $title,
            'url' => $url ?? url()->current(),
        ];

        if ($description) {
            $schema['description'] = $description;
        }

        $this->schemas[] = $schema;

        return $this;
    }

    public function article(
        string $title,
        string $author,
        ?string $datePublished = null,
        ?string $dateModified = null,
        ?string $image = null,
        ?string $description = null,
    ): static {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'author' => [
                '@type' => 'Person',
                'name' => $author,
            ],
        ];

        if ($datePublished) {
            $schema['datePublished'] = $datePublished;
        }

        if ($dateModified) {
            $schema['dateModified'] = $dateModified;
        }

        if ($image) {
            $schema['image'] = $image;
        }

        if ($description) {
            $schema['description'] = $description;
        }

        $this->schemas[] = $schema;

        return $this;
    }

    public function breadcrumbs(array $items): static
    {
        $listItems = [];

        foreach ($items as $position => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $position + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        $this->schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ];

        return $this;
    }

    public function faq(array $questions): static
    {
        $faqItems = [];

        foreach ($questions as $qa) {
            $faqItems[] = [
                '@type' => 'Question',
                'name' => $qa['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $qa['answer'],
                ],
            ];
        }

        $this->schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqItems,
        ];

        return $this;
    }

    public function product(
        string $name,
        ?string $description = null,
        ?string $image = null,
        ?string $brand = null,
        ?string $sku = null,
        ?float $price = null,
        ?string $currency = null,
        ?string $availability = null,
    ): static {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $name,
        ];

        if ($description) {
            $schema['description'] = $description;
        }

        if ($image) {
            $schema['image'] = $image;
        }

        if ($brand) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $brand,
            ];
        }

        if ($sku) {
            $schema['sku'] = $sku;
        }

        if ($price !== null) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $price,
                'priceCurrency' => $currency ?? 'USD',
            ];

            if ($availability) {
                $schema['offers']['availability'] = 'https://schema.org/' . $availability;
            }
        }

        $this->schemas[] = $schema;

        return $this;
    }

    public function localBusiness(
        string $name,
        ?string $address = null,
        ?string $phone = null,
        ?string $url = null,
        ?string $image = null,
        ?array $openingHours = null,
    ): static {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $name,
        ];

        if ($address) {
            $schema['address'] = $address;
        }

        if ($phone) {
            $schema['telephone'] = $phone;
        }

        if ($url) {
            $schema['url'] = $url;
        }

        if ($image) {
            $schema['image'] = $image;
        }

        if ($openingHours) {
            $schema['openingHours'] = $openingHours;
        }

        $this->schemas[] = $schema;

        return $this;
    }

    public function getSchemas(): array
    {
        return $this->schemas;
    }

    public function render(): string
    {
        if (! $this->enabled || empty($this->schemas)) {
            return '';
        }

        $html = [];

        foreach ($this->schemas as $schema) {
            $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            $html[] = '<script type="application/ld+json">' . "\n" . $json . "\n" . '</script>';
        }

        return implode("\n    ", $html);
    }
}
