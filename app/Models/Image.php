<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{

    protected $table = 'images';

    protected $fillable = [
        'product_id'
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
