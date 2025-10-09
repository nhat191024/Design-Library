<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'name',
        'is_show'
    ];

    protected static function booted()
    {
        // Clear cache khi tag được tạo, cập nhật hoặc xóa
        static::created(function () {
            Cache::forget('all_tags');
        });

        static::updated(function () {
            Cache::forget('all_tags');
        });

        static::deleted(function () {
            Cache::forget('all_tags');
        });
    }

    public function Products()
    {
        return $this->belongsToMany(Product::class, 'tag_products', 'tag_id', 'product_id');
    }
}
