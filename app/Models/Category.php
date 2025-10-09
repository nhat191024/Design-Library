<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'parent_id',
        'is_show',
        'image'
    ];

    public function Parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function Children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function Products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function ProductsShowCase()
    {
        return $this->hasMany(Product::class, 'category_id')->where('is_showcase', true);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = $category->slug ?: self::generateSlug($category);
        });

        static::updating(function ($category) {
            $category->slug = self::generateSlug($category);
        });

        // Clear cache khi category được tạo, cập nhật hoặc xóa
        static::created(function () {
            Cache::forget('categories_with_parent');
        });

        static::updated(function () {
            Cache::forget('categories_with_parent');
        });

        static::deleted(function () {
            Cache::forget('categories_with_parent');
        });
    }
    public static function generateSlug($category)
    {
        $timestamp = time();
        if (preg_match('/[^\x{0000}-\x{007F}]+/u', $category->name)) {
            return 'danh-muc-' . Str::slug($category->name) . '-' . $timestamp . '-' . ($category->id ?? Str::random(6));
        }
        return Str::slug($category->name) . '-' . ($category->id ?? $timestamp);
    }
}
