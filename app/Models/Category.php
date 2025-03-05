<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
