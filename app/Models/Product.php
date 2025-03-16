<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'code',
        'category_id',
        'main_image',
        'is_showcase',
    ];

    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function Images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }

    public function Tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_products', 'product_id', 'tag_id');
    }

    public function MainImage()
    {
        return $this->hasOne(Image::class, 'id', 'main_image');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = $product->slug ?: self::generateSlug($product);
            $product->code = $product->code ?: $product->generateCode();
        });

        static::updating(function ($product) {
            $product->slug = self::generateSlug($product);
            $product->code = $product->code ?: $product->generateCode();
        });
    }

    /**
     * Generate a slug for the given product
     *
     * @param  self  $product
     * @return string
     */
    public static function generateSlug($product)
    {
        $timestamp = time();
        if (preg_match('/[^\x{0000}-\x{007F}]+/u', $product->name)) {
            return 'san-pham-' . Str::slug($product->name) . '-' . $timestamp . '-' . ($product->id ?? Str::random(6));
        }
        return Str::slug($product->name) . '-' . ($product->id ?? $timestamp);
    }

    public function generateCode()
    {
        return strtoupper('SP' . Str::random(3) . time());
    }
}
