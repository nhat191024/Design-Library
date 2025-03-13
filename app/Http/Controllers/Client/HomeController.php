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
        $tags = Tag::latest()->limit(50)->get();
        $categories = Category::whereNull('parent_id')->latest()->limit(15)->get();
        $products = Product::with(['Category', 'Images', 'Tags'])->latest()->limit(28)->get();
        $showcaseCategories = Category::where('is_show', 1)->whereNotNull('parent_id')->latest()->get();
        return view('client.home.home')->with([
            'title' => $this->PAGE_TITLE,
            'tags' => $tags,
            'categories' => $categories,
            'products' => $products,
            'showcaseCategories' => $showcaseCategories
        ]);
    }
}
