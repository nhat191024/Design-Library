<?php

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ShopController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('client.profile.edit');
Route::get('/products', [ShopController::class, 'index'])->name('client.shop.index'); // shop index
Route::get('/products?q={query?}', [ShopController::class, 'search'])->name('client.shop.search'); // shop search
Route::get('/products/category/{slug}', [ShopController::class, 'category'])->name('client.shop.category'); // shop category filter
Route::get('/products/detail/{slug}', [ShopController::class, 'detail'])->name('client.product.detail'); // product detail
Route::get('/products/detail/{slug}/download', [ShopController::class, 'downloadImage'])->name('client.product.detail.download'); // product detail image download

Route::get('/contact', [ContactController::class, 'index'])->name('client.contact.index'); // contact index
