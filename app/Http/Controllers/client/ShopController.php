<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

class ShopController extends Controller
{
    public function detail($slug)
    {
        $product = Product::with('Category', 'Images', 'Tags')->where('slug', $slug)->first();
        return view('client.shop.detail')->with([
            'title' => $product->name,
            'product' => $product
        ]);
    }
}

