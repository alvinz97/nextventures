<?php

namespace Domain\Product\Models;

use Domain\Product\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use SoftDeletes;
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title',
        'stock',
        'price'
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    protected static function newFactory(): ProductFactory
    {
        return new ProductFactory();
    }
}
