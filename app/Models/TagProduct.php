<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagProduct extends Model
{
    protected $table = 'tag_products';

    protected $fillable = [
        'tag_id',
        'product_id'
    ];
}
