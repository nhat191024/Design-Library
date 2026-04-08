<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;
use App\Http\Services\SearchService;

class ShopController extends Controller

{
    private const ITEM_PER_PAGE = 24;

    public function index(Request $request)
    {
        if (!$request->filled('tag') && $request->filled('q')) {
            $exactCategoryName = trim($request->q);
            $matchedCategory = Category::where('name', $exactCategoryName)
                ->select('slug')
                ->first();

            if ($matchedCategory) {
                return redirect()->route('client.shop.category', ['slug' => $matchedCategory->slug]);
            }

            $exactTagName = trim($request->q);
            $matchedTag = Tag::where('name', $exactTagName)
                ->select('name')
                ->first();

            if ($matchedTag) {
                $request->merge(['tag' => $matchedTag->name]);
            }
        }

        if ($request->has('tag')) {
            $tagName = $request->tag;
            $products = Product::whereHas('Tags', function ($query) use ($tagName) {
                $query->where('name', $tagName);
            })
                ->with([
                    'Category:id,name,slug',
                    'MainImage:id,url',
                    'images' => function ($query) {
                        $query->select('id', 'url', 'product_id')->limit(1);
                    }
                ])
                ->select('id', 'name', 'slug', 'description', 'category_id', 'main_image', 'created_at')
                ->latest()
                ->paginate(self::ITEM_PER_PAGE);
        } elseif ($request->has('q')) {
            $searchTerm = $request->q;
            $products = $this->tryWithMeilisearch($searchTerm);

            if (!$products) {
                $products = $this->fallbackToBasicSearch($searchTerm);
            }
        } else {
            $products = Product::with([
                'Category:id,name,slug',
                'MainImage:id,url',
                'images' => function ($query) {
                    $query->select('id', 'url', 'product_id')->limit(1);
                }
            ])
                ->select('id', 'name', 'slug', 'description', 'category_id', 'main_image', 'created_at')
                ->latest()
                ->paginate(self::ITEM_PER_PAGE);
        }

        $tagSuggestions = Tag::where('is_show', true)
            ->select('id', 'name')
            ->get();

        $categories = Category::whereNull('parent_id')
            ->select('id', 'name', 'slug')
            ->limit(9)
            ->get();

        return view('client.shop.index')->with([
            'title' => "Cửa hàng - Design showcase",
            'products' => $products,
            'query' => $request->q ?? '',
            'activeTag' => $request->tag ?? null,
            'tagSuggestions' => $tagSuggestions,
            'categories' => $categories,
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
                ->query(function ($eloquentQuery) {
                    $eloquentQuery
                        ->select('id', 'name', 'slug', 'description', 'category_id', 'main_image', 'created_at')
                        ->with([
                            'Category:id,name,slug',
                            'MainImage:id,url',
                            'images' => function ($query) {
                                $query->select('id', 'url', 'product_id')->limit(1);
                            }
                        ]);
                })
                ->paginate(self::ITEM_PER_PAGE);
            return $products;
        } catch (\Throwable $e) {
            Log::warning('Meilisearch search failed', [
                'query' => $query,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Search for products using basic search (i.e. LIKE queries).
     *
     * This method is used as a fallback when Meilisearch is not available.
     *
     * @param string $searchTerm
     */
    public function fallbackToBasicSearch($searchTerm)
    {
        $searchService = new SearchService();
        return $searchService->searchProducts($searchTerm, self::ITEM_PER_PAGE);
    }

    public function downloadImage($slug, Request $request)
    {
        $product = Product::where('slug', $slug)->first();
        $isMobile = $request->is_mobile ?? false;
        try {
            $imageCount = $product->Images->count();
        } catch (\Exception $e) {
            $imageCount = 0;
        }
        if (!$product || $imageCount < 1) {
            abort(404);
        } else if ($imageCount == 1) {
            try {
                $image = $product->Images->first();
            } catch (\Exception $e) {
                abort(404);
            }
            if ($image->url === null) {
                abort(404);
            }
            return $this->downloadOneImage($image->url);
        } else {

            if ($isMobile) {
                // send all the file urls to ajax request
                $imageUrls = [];
                foreach ($product->Images as $image) {
                    $filePath = public_path($image->url);
                    if (file_exists($filePath)) {
                        $imageUrls[] = [
                            'url' => asset($image->url),
                            'name' => basename($filePath)
                        ];
                    }
                }
                return response()->json([
                    'images' => $imageUrls,
                    'product_name' => $product->slug
                ]);
            } else {
                $directory = storage_path('app/public/images/designs');
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                $zipFileName = 'tai-nguyen-' . $product->slug . '.zip';
                $zipFilePath = $directory . DIRECTORY_SEPARATOR . $zipFileName;
                $zip = new ZipArchive;
                $result = $zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
                if ($result === true) {
                    $zip->setCompressionIndex(0, ZipArchive::CM_STORE);
                    foreach ($product->Images as $image) {
                        $filePath = public_path($image->url);
                        if (file_exists($filePath)) {
                            $zip->addFile($filePath, basename($filePath));
                            $index = $zip->lastId;
                            // set no compression for this zip file (best optimize for server performance)
                            $zip->setCompressionIndex($index, ZipArchive::CM_STORE);
                        }
                    }
                    $zip->close();
                } else {
                    abort(500);
                }
                return response()->download($zipFilePath, null, [
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                ])->deleteFileAfterSend(true);
            }
        }
    }

    // function to download one image
    public function downloadOneImage($imageUrl): BinaryFileResponse
    {
        $filePath = public_path($imageUrl);
        if (file_exists($filePath)) {
            return response()->download($filePath, null, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            ]);
        } else {
            abort(404);
        }
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->select('id', 'name', 'slug', 'parent_id')
            ->first();

        if (!$category) {
            return redirect()->route('client.shop.index');
        }

        $categories = Category::whereNull('parent_id')
            ->select('id', 'name', 'slug')
            ->limit(9)
            ->get();

        if ($category->parent_id) {
            $products = Product::latest()
                ->where('category_id', $category->id)
                ->select('id', 'name', 'slug', 'description', 'category_id', 'main_image', 'created_at')
                ->with([
                    'Category:id,name,slug',
                    'MainImage:id,url',
                    'images' => function ($query) {
                        $query->select('id', 'url', 'product_id')->limit(1);
                    }
                ])
                ->paginate(self::ITEM_PER_PAGE);
        } else {
            $products = Product::latest()
                ->whereHas('Category', function ($query) use ($category) {
                    $query->where('parent_id', $category->id);
                })
                ->select('id', 'name', 'slug', 'description', 'category_id', 'main_image', 'created_at')
                ->with([
                    'Category:id,name,slug',
                    'MainImage:id,url',
                    'images' => function ($query) {
                        $query->select('id', 'url', 'product_id')->limit(1);
                    }
                ])
                ->paginate(self::ITEM_PER_PAGE);
        }

        $tagSuggestions = Tag::where('is_show', true)
            ->select('id', 'name')
            ->get();

        return view('client.shop.index')->with([
            'title' => $category->name,
            'products' => $products,
            'categories' => $categories,
            'query' => $category->name,
            'tagSuggestions' => $tagSuggestions,
        ]);
    }

    public function detail($slug)
    {
        $product = Product::with('Category', 'Images', 'Tags')->where('slug', $slug)->first();
        if (!$product) {
            return redirect()->route('client.shop.index');
        }
        try {
            $relatedProducts = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->with('Category', 'Images', 'Tags')->paginate(self::ITEM_PER_PAGE);
        } catch (\Exception $e) {
            $relatedProducts = [];
        }
        $curentTags = $product->Tags;
        return view('client.shop.detail')->with([
            'title' => $product->name,
            'product' => $product,
            'products' => $relatedProducts,
            'tags' => $curentTags
        ]);
    }
}
