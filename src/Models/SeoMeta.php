<?php

namespace IbrahimEng12\Seoquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'title',
        'description',
        'keywords',
        'robots',
        'canonical',
        'author',
        'og_title',
        'og_description',
        'og_type',
        'og_image',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'json_ld',
        'custom_meta',
    ];

    protected $casts = [
        'keywords' => 'array',
        'json_ld' => 'array',
        'custom_meta' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('seoquent.database.table_name', 'seo_meta'));
    }

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function toSeoArray(): array
    {
        $data = [];

        $fields = [
            'title', 'description', 'keywords', 'robots',
            'canonical', 'author', 'og_title', 'og_description',
            'og_type', 'og_image', 'twitter_card', 'twitter_title',
            'twitter_description', 'twitter_image',
        ];

        foreach ($fields as $field) {
            if ($this->{$field} !== null) {
                $data[$field] = $this->{$field};
            }
        }

        return $data;
    }
}
