<?php

namespace IbrahimEng12\Seoquent\Services;

class MetaTagBuilder
{
    protected string $title = '';
    protected string $titleSuffix = '';
    protected string $titleSeparator = ' | ';
    protected bool $useSuffix = true;
    protected string $description = '';
    protected array $keywords = [];
    protected string $robots = 'index, follow';
    protected ?string $canonical = null;
    protected string $author = '';
    protected array $custom = [];

    public function __construct()
    {
        $this->title = config('seoquent.defaults.title', '');
        $this->titleSuffix = config('seoquent.defaults.title_suffix', '');
        $this->titleSeparator = config('seoquent.defaults.title_separator', ' | ');
        $this->description = config('seoquent.defaults.description', '');
        $this->keywords = config('seoquent.defaults.keywords', []);
        $this->robots = config('seoquent.defaults.robots', 'index, follow');
        $this->canonical = config('seoquent.defaults.canonical');
        $this->author = config('seoquent.defaults.author', '');
    }

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function titleSuffix(string $suffix): static
    {
        $this->titleSuffix = $suffix;

        return $this;
    }

    public function titleSeparator(string $separator): static
    {
        $this->titleSeparator = $separator;

        return $this;
    }

    public function withoutTitleSuffix(): static
    {
        $this->useSuffix = false;

        return $this;
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function keywords(array|string $keywords): static
    {
        if (is_string($keywords)) {
            $keywords = array_map('trim', explode(',', $keywords));
        }

        $this->keywords = $keywords;

        return $this;
    }

    public function robots(string $robots): static
    {
        $this->robots = $robots;

        return $this;
    }

    public function noIndex(): static
    {
        $this->robots = 'noindex, nofollow';

        return $this;
    }

    public function canonical(?string $url): static
    {
        $this->canonical = $url;

        return $this;
    }

    public function author(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function meta(string $name, string $content): static
    {
        $this->custom[$name] = $content;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getFullTitle(): string
    {
        if ($this->useSuffix && $this->titleSuffix && $this->title !== $this->titleSuffix) {
            return $this->title . $this->titleSeparator . $this->titleSuffix;
        }

        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function getRobots(): string
    {
        return $this->robots;
    }

    public function getCanonical(): ?string
    {
        if ($this->canonical) {
            return $this->normalizeUrl($this->canonical);
        }

        return $this->normalizeUrl(url()->current());
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCustomMeta(): array
    {
        return $this->custom;
    }

    protected function normalizeUrl(string $url): string
    {
        $trailingSlash = config('seoquent.trailing_slash');

        if ($trailingSlash === true && ! str_ends_with($url, '/')) {
            return $url . '/';
        }

        if ($trailingSlash === false) {
            return rtrim($url, '/');
        }

        return $url;
    }

    public function render(): string
    {
        $html = [];

        $html[] = '<title>' . e($this->getFullTitle()) . '</title>';

        if ($this->description) {
            $html[] = '<meta name="description" content="' . e($this->description) . '">';
        }

        if (! empty($this->keywords)) {
            $html[] = '<meta name="keywords" content="' . e(implode(', ', $this->keywords)) . '">';
        }

        $html[] = '<meta name="robots" content="' . e($this->robots) . '">';

        $canonical = $this->getCanonical();
        if ($canonical) {
            $html[] = '<link rel="canonical" href="' . e($canonical) . '">';
        }

        if ($this->author) {
            $html[] = '<meta name="author" content="' . e($this->author) . '">';
        }

        foreach ($this->custom as $name => $content) {
            $html[] = '<meta name="' . e($name) . '" content="' . e($content) . '">';
        }

        return implode("\n    ", $html);
    }
}
