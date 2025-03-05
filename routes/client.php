<?php

use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ShopController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('client.profile.edit');
Route::get('/products/detail/{slug}', [ShopController::class, 'detail'])->name('client.product.detail');
