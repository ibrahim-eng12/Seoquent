<?php

namespace IbrahimEng12\Seoquent\Services;

class OpenGraphBuilder
{
    protected ?string $title = null;
    protected ?string $description = null;
    protected string $type = 'website';
    protected ?string $url = null;
    protected ?string $siteName = null;
    protected string $locale = 'en_US';
    protected ?string $image = null;
    protected ?int $imageWidth = null;
    protected ?int $imageHeight = null;
    protected ?string $imageAlt = null;
    protected array $custom = [];

    public function __construct()
    {
        $this->type = config('seoquent.open_graph.type', 'website');
        $this->siteName = config('seoquent.open_graph.site_name');
        $this->locale = config('seoquent.open_graph.locale', 'en_US');
        $this->image = config('seoquent.open_graph.image');
        $this->imageWidth = config('seoquent.open_graph.image_width');
        $this->imageHeight = config('seoquent.open_graph.image_height');
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function type(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function siteName(string $siteName): static
    {
        $this->siteName = $siteName;

        return $this;
    }

    public function locale(string $locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    public function image(string $url, ?int $width = null, ?int $height = null, ?string $alt = null): static
    {
        $this->image = $url;
        $this->imageWidth = $width ?? $this->imageWidth;
        $this->imageHeight = $height ?? $this->imageHeight;
        $this->imageAlt = $alt;

        return $this;
    }

    public function property(string $property, string $content): static
    {
        $this->custom[$property] = $content;

        return $this;
    }

    public function article(): static
    {
        $this->type = 'article';

        return $this;
    }

    public function profile(): static
    {
        $this->type = 'profile';

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function render(MetaTagBuilder $metaFallback): string
    {
        $html = [];

        $title = $this->title ?? $metaFallback->getTitle();
        if ($title) {
            $html[] = '<meta property="og:title" content="' . e($title) . '">';
        }

        $description = $this->description ?? $metaFallback->getDescription();
        if ($description) {
            $html[] = '<meta property="og:description" content="' . e($description) . '">';
        }

        $html[] = '<meta property="og:type" content="' . e($this->type) . '">';

        $url = $this->url ?? url()->current();
        $html[] = '<meta property="og:url" content="' . e($url) . '">';

        if ($this->siteName) {
            $html[] = '<meta property="og:site_name" content="' . e($this->siteName) . '">';
        }

        $html[] = '<meta property="og:locale" content="' . e($this->locale) . '">';

        if ($this->image) {
            $html[] = '<meta property="og:image" content="' . e($this->image) . '">';

            if ($this->imageWidth) {
                $html[] = '<meta property="og:image:width" content="' . e($this->imageWidth) . '">';
            }

            if ($this->imageHeight) {
                $html[] = '<meta property="og:image:height" content="' . e($this->imageHeight) . '">';
            }

            if ($this->imageAlt) {
                $html[] = '<meta property="og:image:alt" content="' . e($this->imageAlt) . '">';
            }
        }

        foreach ($this->custom as $property => $content) {
            $html[] = '<meta property="og:' . e($property) . '" content="' . e($content) . '">';
        }

        return implode("\n    ", $html);
    }
}
