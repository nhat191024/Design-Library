<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'name',
        'is_show'
    ];

    public function Products()
    {
        return $this->belongsToMany(Product::class, 'tag_products', 'tag_id', 'product_id');
    }
}
