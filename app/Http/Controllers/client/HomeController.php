<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

class HomeController extends Controller
{
    private $PAGE_TITLE = "Design Showcase";
    
    public function index()
    {
        $tags = Tag::all();
        $categories = Category::all();
        $products = Product::with('Category', 'Images', 'Tags')->get();
        return view('client.home.home')->with([
            'title' => $this->PAGE_TITLE,
            'tags' => $tags,
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
