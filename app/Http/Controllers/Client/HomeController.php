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

        // Chỉ load 16 sản phẩm cho trang chủ và eager load images để tránh N+1 query
        $products = Product::where('is_showcase', 1)
            ->with(['MainImage', 'images' => function ($query) {
                $query->limit(1);
            }])
            ->latest()
            ->limit(16)
            ->get();

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

    // API endpoint để load thêm sản phẩm cho infinite scroll
    public function loadMore(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 16;
        $offset = ($page - 1) * $perPage;

        $products = Product::where('is_showcase', 1)
            ->with(['MainImage', 'images' => function ($query) {
                $query->limit(1);
            }])
            ->latest()
            ->skip($offset)
            ->take($perPage)
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'products' => $products,
                'hasMore' => $products->count() === $perPage,
                'html' => view('client.partials.products-loop', compact('products'))->render()
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}
