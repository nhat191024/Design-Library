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
            $products = Product::with('Category', 'Images', 'Tags')->paginate(48);
        }

        $tags = Tag::all()->unique('name');
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

    public function downloadImage($slug): BinaryFileResponse
    {
        $product = Product::where('slug', $slug)->first();
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
            return response()->download(public_path($image->url), null, [
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
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

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return redirect()->route('client.shop.index');
        }
        $products = $category->Products()->with('Category', 'Images', 'Tags')->paginate(48);
        $tags = Tag::all()->unique('name');
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
