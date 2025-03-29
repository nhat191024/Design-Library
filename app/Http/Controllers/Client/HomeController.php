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
        $tagSuggestions = Tag::where('is_show', true)->get();
        $tags = Tag::latest()->get();
        $categories = Category::whereNull('parent_id')->latest()->limit(15)->get();
        $products = Product::where('is_showcase', 1)->latest()->get();
        $showcaseCategories = Category::where('is_show', 1)->whereNotNull('parent_id')->latest()->get();
        return view('client.home.home')->with([
            'title' => $this->PAGE_TITLE,
            'tags' => $tags,
            'categories' => $categories,
            'products' => $products,
            'showcaseCategories' => $showcaseCategories,
            'tagSuggestions' => $tagSuggestions,
        ]);
    }
}
