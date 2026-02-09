<?php

namespace IbrahimEng12\Seoquent\Services;

class TwitterCardBuilder
{
    protected string $card = 'summary_large_image';
    protected ?string $site = null;
    protected ?string $creator = null;
    protected ?string $title = null;
    protected ?string $description = null;
    protected ?string $image = null;
    protected ?string $imageAlt = null;

    public function __construct()
    {
        $this->card = config('seoquent.twitter.card', 'summary_large_image');
        $this->site = config('seoquent.twitter.site');
        $this->creator = config('seoquent.twitter.creator');
    }

    public function card(string $type): static
    {
        $this->card = $type;

        return $this;
    }

    public function summary(): static
    {
        $this->card = 'summary';

        return $this;
    }

    public function summaryLargeImage(): static
    {
        $this->card = 'summary_large_image';

        return $this;
    }

    public function site(string $username): static
    {
        $this->site = $username;

        return $this;
    }

    public function creator(string $username): static
    {
        $this->creator = $username;

        return $this;
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

    public function image(string $url, ?string $alt = null): static
    {
        $this->image = $url;
        $this->imageAlt = $alt;

        return $this;
    }

    public function render(MetaTagBuilder $metaFallback, OpenGraphBuilder $ogFallback): string
    {
        $html = [];

        $html[] = '<meta name="twitter:card" content="' . e($this->card) . '">';

        if ($this->site) {
            $html[] = '<meta name="twitter:site" content="' . e($this->site) . '">';
        }

        if ($this->creator) {
            $html[] = '<meta name="twitter:creator" content="' . e($this->creator) . '">';
        }

        $title = $this->title ?? $ogFallback->getTitle() ?? $metaFallback->getTitle();
        if ($title) {
            $html[] = '<meta name="twitter:title" content="' . e($title) . '">';
        }

        $description = $this->description ?? $ogFallback->getDescription() ?? $metaFallback->getDescription();
        if ($description) {
            $html[] = '<meta name="twitter:description" content="' . e($description) . '">';
        }

        if ($this->image) {
            $html[] = '<meta name="twitter:image" content="' . e($this->image) . '">';

            if ($this->imageAlt) {
                $html[] = '<meta name="twitter:image:alt" content="' . e($this->imageAlt) . '">';
            }
        }

        return implode("\n    ", $html);
    }
}
