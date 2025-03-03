<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        $categories = Category::all();
        $products = Product::with('Category', 'Images', 'Tags')->get();
        return view('client.home.home')->with([
            'title' => "Design Showcase",
            'tags' => $tags,
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
