<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = [
        'name'
    ];

    public function Products()
    {
        return $this->belongsToMany(Product::class, 'tag_product', 'tag_id', 'product_id');
    }
}
