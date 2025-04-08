<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

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
            $products = Product::with('Category', 'Images', 'Tags')->latest()->paginate(48);
        }
        $tagSuggestions = Tag::where('is_show', true)->get();
        $tags = Tag::latest()->get()->unique('name');
        $categories = Category::whereNull('parent_id')->get();

        return view('client.shop.index')->with([
            'title' => "Cửa hàng - Design showcase",
            'products' => $products,
            'tags' => $tags,
            'categories' => $categories,
            'query' => $request->q ?? '',
            'tagSuggestions' => $tagSuggestions,
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
                ->paginate(48);
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

        $query  = null;

        $query = Tag::where('name', 'like', '%' . $searchTerm . '%')->first();

        if ($query) {
            $products = Product::whereHas('Tags', function ($tagQuery) use ($searchTerm) {
                $tagQuery->where('name', 'like', '%' . $searchTerm . '%');
            })->with('Category', 'Images', 'Tags')->paginate(48);
            return $products;
        }

        $query = Category::where('name', 'like', '%' . $searchTerm . '%')->first();

        if ($query) {
            $products = Product::whereHas('Category', function ($categoryQuery) use ($searchTerm) {
                $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
            })->with('Category', 'Images', 'Tags')->paginate(48);
            return $products;
        }

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

        $products = $query->paginate(48);
        return $products;
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
        $category = Category::where('slug', $slug)->first();
        $categories = Category::whereNull('parent_id')->get();
        $products = $category->Products()->with('Category', 'Images', 'Tags')->paginate(48);
        if (!$category) {
            return redirect()->route('client.shop.index');
        }
        if ($category->parent_id) {
            $products = Product::latest()->where('category_id', $category->id)->with('Category', 'Images', 'Tags')->paginate(48);
        } else {
            $products = Product::latest()->whereHas('Category', function ($query) use ($category) {
                $query->where('parent_id', $category->id);
            })->with('Category', 'Images', 'Tags')->paginate(48);
        }
        $tagSuggestions = Tag::where('is_show', true)->get();
        $tags = Tag::where('is_show', '1')->latest()->get()->unique('name');
        return view('client.shop.index')->with([
            'title' => $category->name,
            'products' => $products,
            'tags' => $tags,
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
            $relatedProducts = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->with('Category', 'Images', 'Tags')->paginate(48);
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
