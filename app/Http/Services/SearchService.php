<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SearchService
{
    /**
     * Get search suggestions across products, tags, and parent categories.
     * Higher relevance (exact/prefix) and shorter names are prioritized.
     */
    public function getSuggestions(string $keyword): array
    {
        if (mb_strlen($keyword) < 2) {
            return [];
        }

        $escapedKeyword = str_replace(['%', '_'], ['\\%', '\\_'], $keyword);
        $cacheKey = 'search_suggest_' . md5($keyword);

        return Cache::remember($cacheKey, 600, function () use ($escapedKeyword, $keyword) {
            $products = DB::table('products')
                ->selectRaw("name, CASE WHEN name = ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END as relevance", [$keyword, "{$escapedKeyword}%"])
                ->where('name', 'LIKE', "%{$escapedKeyword}%");

            $tags = DB::table('tags')
                ->selectRaw("name, CASE WHEN name = ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END as relevance", [$keyword, "{$escapedKeyword}%"])
                ->where('is_show', true)
                ->where('name', 'LIKE', "%{$escapedKeyword}%");

            $results = DB::table('categories')
                ->selectRaw("name, CASE WHEN name = ? THEN 1 WHEN name LIKE ? THEN 2 ELSE 3 END as relevance", [$keyword, "{$escapedKeyword}%"])
                ->whereNull('parent_id')
                ->where('name', 'LIKE', "%{$escapedKeyword}%")
                ->union($products)
                ->union($tags)
                ->orderBy('relevance')
                ->orderByRaw('LENGTH(name)')
                ->orderBy('name')
                ->limit(50)
                ->get();

            return $results->sortBy(function ($item) use ($keyword) {
                $name = mb_strtolower($item->name ?? '', 'UTF-8');
                $lowerKeyword = mb_strtolower($keyword, 'UTF-8');

                $score = 4;
                if ($name === $lowerKeyword) {
                    $score = 1;
                } elseif (str_starts_with($name, $lowerKeyword)) {
                    $score = 2;
                } elseif (str_contains($name, $lowerKeyword)) {
                    $score = 3;
                }

                return sprintf('%d-%05d-%s', $score, mb_strlen($name, 'UTF-8'), $name);
            })->values()->take(15)->pluck('name')->toArray();
        });
    }

    /**
     * Detailed search for products, including matching name, categories, or tags.
     * Returns a paginated product result.
     */
    public function searchProducts(string $keyword, int $perPage = 16)
    {
        $escapedKeyword = str_replace(['%', '_'], ['\\%', '\\_'], $keyword);

        // Find relevant categories and tags first to include their products
        $matchingCategoryIds = Category::where('name', 'LIKE', "%{$escapedKeyword}%")->pluck('id');
        $matchingTagIds = Tag::where('name', 'LIKE', "%{$escapedKeyword}%")->pluck('id');

        return Product::query()
            ->select('products.*')
            // Using CASE WHEN in order by to prioritize name matches over category/tag matches
            ->orderByRaw("
                CASE 
                    WHEN name = ? THEN 1 
                    WHEN name LIKE ? THEN 2 
                    ELSE 3 
                END ASC,
                created_at DESC
            ", [$keyword, "{$escapedKeyword}%"])
            ->where(function ($query) use ($escapedKeyword, $matchingCategoryIds, $matchingTagIds) {
                $query->where('name', 'LIKE', "%{$escapedKeyword}%")
                    ->orWhereIn('category_id', $matchingCategoryIds)
                    ->orWhereHas('Tags', function ($tagQuery) use ($matchingTagIds) {
                        $tagQuery->whereIn('tags.id', $matchingTagIds);
                    });
            })
            ->with([
                'Category:id,name,slug',
                'MainImage:id,url',
                'images' => function ($query) {
                    $query->select('id', 'url', 'product_id')->limit(1);
                }
            ])
            ->paginate($perPage);
    }
}
