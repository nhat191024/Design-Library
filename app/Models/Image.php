<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $table = 'images';

    protected $fillable = [
        'product_id',
        'url'
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
