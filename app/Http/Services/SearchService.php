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
     * Tokenize a keyword into meaningful words (min 2 chars each).
     */
    private function tokenize(string $keyword): array
    {
        $words = preg_split('/\s+/u', trim($keyword));
        return array_values(array_filter($words, fn($w) => mb_strlen($w, 'UTF-8') >= 2));
    }

    /**
     * Get search suggestions across products, tags, and parent categories.
     * Higher relevance (exact/prefix) and shorter names are prioritized.
     * Supports both full-phrase and multi-word (any-word) matching.
     */
    public function getSuggestions(string $keyword): array
    {
        if (mb_strlen($keyword) < 2) {
            return [];
        }

        $escapedKeyword = str_replace(['%', '_'], ['\\%', '\\_'], $keyword);
        $cacheKey = 'search_suggest_' . md5($keyword);

        return Cache::remember($cacheKey, 600, function () use ($escapedKeyword, $keyword) {
            $words = $this->tokenize($keyword);

            // --- Phrase-level queries (high relevance) ---
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
                ->limit(50)
                ->get();

            // --- Word-level queries (lower relevance, for multi-word gaps like "sinh bé trai") ---
            // Only kicks in when there are multiple meaningful tokens
            $wordResults = collect();
            if (count($words) > 1) {
                // All-words-present query: every word must appear somewhere in the name
                $allWordsProductBase = DB::table('products')->select('name');
                foreach ($words as $word) {
                    $escapedWord = str_replace(['%', '_'], ['\\%', '\\_'], $word);
                    $allWordsProductBase->where('name', 'LIKE', "%{$escapedWord}%");
                }

                $allWordsTagBase = DB::table('tags')->select('name')->where('is_show', true);
                foreach ($words as $word) {
                    $escapedWord = str_replace(['%', '_'], ['\\%', '\\_'], $word);
                    $allWordsTagBase->where('name', 'LIKE', "%{$escapedWord}%");
                }

                $allWordsCatBase = DB::table('categories')->select('name')->whereNull('parent_id');
                foreach ($words as $word) {
                    $escapedWord = str_replace(['%', '_'], ['\\%', '\\_'], $word);
                    $allWordsCatBase->where('name', 'LIKE', "%{$escapedWord}%");
                }

                // Add a dummy relevance column to UNION with the phrase queries
                $allWordsProducts = DB::table('products')
                    ->selectRaw("name, 3 as relevance")
                    ->whereIn('name', $allWordsProductBase);

                $allWordsTags = DB::table('tags')
                    ->selectRaw("name, 3 as relevance")
                    ->where('is_show', true)
                    ->whereIn('name', $allWordsTagBase);

                $wordResults = DB::table('categories')
                    ->selectRaw("name, 3 as relevance")
                    ->whereNull('parent_id')
                    ->whereIn('name', $allWordsCatBase)
                    ->union($allWordsProducts)
                    ->union($allWordsTags)
                    ->get();
            }

            // Merge and deduplicate (phrase matches come first, then fill with word matches)
            $merged = $results->concat($wordResults)->unique('name');

            // Deep PHP sort for Vietnamese accent accuracy
            return $merged->sortBy(function ($item) use ($keyword, $words) {
                $name = mb_strtolower($item->name ?? '', 'UTF-8');
                $lowerKeyword = mb_strtolower($keyword, 'UTF-8');

                if ($name === $lowerKeyword) {
                    $score = 1;
                } elseif (str_starts_with($name, $lowerKeyword)) {
                    $score = 2;
                } elseif (str_contains($name, $lowerKeyword)) {
                    $score = 3;
                } else {
                    // Count how many individual words match — more matches = better score
                    $matchedWords = count(array_filter(
                        $words,
                        fn($w) => str_contains($name, mb_strtolower($w, 'UTF-8'))
                    ));
                    $score = 4 + (count($words) - $matchedWords); // fewer misses = lower score = higher rank
                }

                return sprintf('%d-%05d-%s', $score, mb_strlen($name, 'UTF-8'), $name);
            })->values()->take(15)->pluck('name')->toArray();
        });
    }

    /**
     * Detailed search for products, including matching name, categories, or tags.
     * Returns a paginated product result.
     * Supports phrase search and individual-word fallback for gap tolerant matching.
     */
    public function searchProducts(string $keyword, int $perPage = 16)
    {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return Product::query()
                ->whereRaw('1 = 0')
                ->paginate($perPage);
        }

        $escapedKeyword = str_replace(['%', '_'], ['\\%', '\\_'], $keyword);
        $words = $this->tokenize($keyword);

        // Match categories and tags that contain ANY of the individual words
        $matchingCategoryIds = Category::where(function ($q) use ($escapedKeyword, $words) {
            $q->where('name', 'LIKE', "%{$escapedKeyword}%");
            foreach ($words as $word) {
                $ew = str_replace(['%', '_'], ['\\%', '\\_'], $word);
                $q->orWhere('name', 'LIKE', "%{$ew}%");
            }
        })->pluck('id');

        $matchingTagIds = Tag::where(function ($q) use ($escapedKeyword, $words) {
            $q->where('name', 'LIKE', "%{$escapedKeyword}%");
            foreach ($words as $word) {
                $ew = str_replace(['%', '_'], ['\\%', '\\_'], $word);
                $q->orWhere('name', 'LIKE', "%{$ew}%");
            }
        })
            ->where('is_show', true)
            ->pluck('id');

        return Product::query()
            ->select('products.*')
            ->orderByRaw("
                CASE
                    WHEN name = ? THEN 1
                    WHEN name LIKE ? THEN 2
                    ELSE 3
                END ASC,
                created_at DESC
            ", [$keyword, "{$escapedKeyword}%"])
            ->where(function ($query) use ($escapedKeyword, $words, $matchingCategoryIds, $matchingTagIds) {
                // 1. Phrase match on product name
                $query->where('name', 'LIKE', "%{$escapedKeyword}%");

                // 2. All-words-present match on product name (gap-tolerant)
                if (count($words) > 1) {
                    $query->orWhere(function ($q) use ($words) {
                        foreach ($words as $word) {
                            $ew = str_replace(['%', '_'], ['\\%', '\\_'], $word);
                            $q->where('name', 'LIKE', "%{$ew}%");
                        }
                    });
                }

                // 3. Category or Tag match (orWhere — lower priority but still included)
                $query->orWhereIn('category_id', $matchingCategoryIds)
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
