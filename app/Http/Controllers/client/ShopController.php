<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('q')) {
            $searchTerm = $request->q;
            $products = $this->tryWithMeilisearch($searchTerm);
            if (!$products) {
                $products = $this->fallbackToBasicSearch($searchTerm);
            }
        } else {
            $products = Product::with('Category', 'Images', 'Tags')->get();
        }

        $tags = Tag::all();
        $categories = Category::all();

        return view('client.shop.index')->with([
            'title' => "Cửa hàng - Design showcase",
            'products' => $products,
            'tags' => $tags,
            'categories' => $categories,
            'query' => $request->q ?? ''
        ]);
    }

    /**
     * Attempt to search for products using Meilisearch (needs to be set up and running on the host).
     * If Meilisearch is down or throws an exception, return null.
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    private function tryWithMeilisearch($query)
    {
        try {
            $products = Product::search($query, function ($meilisearch, $query, $options) {
                $options['typoTolerance'] = [
                    'enabled' => true,
                    'minWordSizeForTypos' => [
                        'oneTypo' => 3,
                        'twoTypos' => 6
                    ],
                    'disableOnWords' => [],
                    'disableOnAttributes' => []
                ];

                return $meilisearch->search($query, $options);
            })
                ->with('Category', 'Images', 'Tags')
                ->get();
            return $products;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Search for products using basic search (i.e. LIKE queries).
     *
     * This method is used as a fallback when Meilisearch is not available.
     *
     * @param string $searchTerm
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function fallbackToBasicSearch($searchTerm)
    {
        $query = Product::query();
        $query->with('Category', 'Images', 'Tags');
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%');
            $words = explode(' ', $searchTerm);
            foreach ($words as $word) {
                if (strlen($word) >= 2) {
                    $q->orWhere('name', 'like', '%' . $word . '%');
                }
            }
            $q->orWhere('description', 'like', '%' . $searchTerm . '%');
            $q->orWhereHas('Tags', function ($tagQuery) use ($searchTerm, $words) {
                $tagQuery->where('name', 'like', '%' . $searchTerm . '%');
                foreach ($words as $word) {
                    if (strlen($word) >= 2) {
                        $tagQuery->orWhere('name', 'like', '%' . $word . '%');
                    }
                }
            });
            $q->orWhereHas('Category', function ($categoryQuery) use ($searchTerm) {
                $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
            });
        });
        $products = $query->get();
        return $products;
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return redirect()->route('client.shop.index');
        }
        $products = $category->Products()->with('Category', 'Images', 'Tags')->get();
        $tags = Tag::all();
        $categories = Category::all();
        return view('client.shop.index')->with([
            'title' => $category->name,
            'products' => $products,
            'tags' => $tags,
            'categories' => $categories,
            'query' => $category->name
        ]);
    }

    public function detail($slug)
    {
        $product = Product::with('Category', 'Images', 'Tags')->where('slug', $slug)->first();
        if (!$product) {
            return redirect()->route('client.shop.index');
        }
        return view('client.shop.detail')->with([
            'title' => $product->name,
            'product' => $product
        ]);
    }
}
