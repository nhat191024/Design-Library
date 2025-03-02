<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'category_id'
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
        return $this->belongsToMany(Tag::class, 'tag_product', 'product_id', 'tag_id');
    }
}
