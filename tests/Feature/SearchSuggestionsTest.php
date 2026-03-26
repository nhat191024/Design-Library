<?php

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    // Clear cache before each test
    Cache::flush();

    // Seed some data
    Tag::create(['name' => 'Laravel', 'is_show' => true]);
    Tag::create(['name' => 'PHP', 'is_show' => true]);
    Tag::create(['name' => 'Hidden Tag', 'is_show' => false]);
    Tag::create(['name' => 'Thỏ cinnamoroll', 'is_show' => true]);
    Tag::create(['name' => 'Thỏ trắng', 'is_show' => true]);
    Tag::create(['name' => 'Mừng thọ', 'is_show' => true]);

    $backend = Category::create(['name' => 'Backend', 'parent_id' => null, 'is_show' => true, 'slug' => 'backend']);
    Category::create(['name' => 'Frontend', 'parent_id' => $backend->id, 'is_show' => true, 'slug' => 'frontend']); // Subcategory
});

it('returns suggestions for cinnamoroll even if it is a suffix', function () {
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'cinnamoroll']));
    $response->assertStatus(200)
             ->assertJson(['Thỏ cinnamoroll']);
});

it('prioritizes exact accent matches for Vietnamese keywords', function () {
    // Search for "thỏ"
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'thỏ']));
    $response->assertStatus(200);
    
    $data = $response->json();
    
    // "Thỏ trắng" (shorter) and then "Thỏ cinnamoroll" should be first because they start with "thỏ"
    // "Mừng thọ" should be last because it only matches "tho" (accent-insensitive)
    expect($data[0])->toBe('Thỏ trắng');
    expect($data[1])->toBe('Thỏ cinnamoroll');
    expect($data)->toContain('Mừng thọ');
});

it('is case-insensitive but accent-sensitive for ranking', function () {
    // Search for "Thỏ" (Capital)
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'Thỏ']));
    $response->assertStatus(200);
    
    $data = $response->json();
    expect($data)->toContain('Thỏ cinnamoroll');
    expect($data)->toContain('Thỏ trắng');
});



it('returns empty array if keyword is less than 2 characters', function () {
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'a']));

    $response->assertStatus(200)
             ->assertJson([]);
});

it('returns suggestions from tags and parent categories', function () {
    // Match "L" -> Laravel
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'La']));
    $response->assertStatus(200)
             ->assertJson(['Laravel']);

    // Match "B" -> Backend
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'Ba']));
    $response->assertStatus(200)
             ->assertJson(['Backend']);
});

it('does not return hidden tags or subcategories', function () {
    // Hidden Tag
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'Hi']));
    $response->assertStatus(200)
             ->assertJson([]);

    // Subcategory (Frontend has parent_id != null)
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'Fr']));
    $response->assertStatus(200)
             ->assertJson([]);
});

it('escapes SQL special characters', function () {
    // Create a tag with special characters
    Tag::create(['name' => 'Tag%Percent', 'is_show' => true]);

    // Search for "Tag%" literal
    $response = $this->getJson(route('client.api.search-suggestions', ['keyword' => 'Tag%']));
    $response->assertStatus(200)
             ->assertJson(['Tag%Percent']);
});

it('caches the results', function () {
    $keyword = 'Lara';
    $cacheKey = 'search_suggest_' . md5($keyword);

    expect(Cache::has($cacheKey))->toBeFalse();

    $this->getJson(route('client.api.search-suggestions', ['keyword' => $keyword]));

    expect(Cache::has($cacheKey))->toBeTrue();
    expect(Cache::get($cacheKey))->toContain('Laravel');
});
