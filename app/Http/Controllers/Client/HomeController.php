<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Services\SearchService;

class HomeController extends Controller
{
    private $PAGE_TITLE = "Design Showcase";

    public function index()
    {
        $tagSuggestions = Tag::where('is_show', true)->get();

        // Chỉ load 16 sản phẩm cho trang chủ và eager load images để tránh N+1 query
        $products = Product::where('is_showcase', 1)
            ->with(['MainImage', 'images' => function ($query) {
                $query->limit(1);
            }])
            ->latest()
            ->limit(16)
            ->get();

        $showcaseCategories = Category::where('is_show', 1)->whereNotNull('parent_id')->latest()->get();

        $bgSettings = [
            'zone0_image'   => \App\Models\SiteSetting::get('bg_zone0_image'),
            'zone0_blur'    => \App\Models\SiteSetting::get('bg_zone0_blur', 0),
            'zone0_opacity' => \App\Models\SiteSetting::get('bg_zone0_opacity', 0.5),
            'zone1_image'   => \App\Models\SiteSetting::get('bg_zone1_image'),
            'zone1_blur'    => \App\Models\SiteSetting::get('bg_zone1_blur', 0),
            'zone1_opacity' => \App\Models\SiteSetting::get('bg_zone1_opacity', 0.5),
            'zone2_image'   => \App\Models\SiteSetting::get('bg_zone2_image'),
            'zone2_blur'    => \App\Models\SiteSetting::get('bg_zone2_blur', 0),
            'zone2_opacity' => \App\Models\SiteSetting::get('bg_zone2_opacity', 0.5),
        ];

        return view('client.home.home')->with([
            'title' => $this->PAGE_TITLE,
            'products' => $products,
            'showcaseCategories' => $showcaseCategories,
            'tagSuggestions' => $tagSuggestions,
            'bgSettings' => $bgSettings,
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

    // API endpoint để lấy search suggestions
    public function searchSuggestions(Request $request, SearchService $searchService)
    {
        $keyword = $request->get('keyword', '');
        return response()->json($searchService->getSuggestions($keyword));
    }
}
