<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CategoryController;

use Illuminate\Support\Facades\Route;

require __DIR__.'/client.php';

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/designs', [DesignController::class, 'index'])->name('designs.index');
    Route::get('/designs/create', [DesignController::class, 'create'])->name('designs.create');
    Route::post('/designs/store', [DesignController::class, 'store'])->name('designs.store');
    Route::get('/designs/edit/{id}', [DesignController::class, 'showEditForm'])->name('designs.edit');
    Route::patch('/designs/update/{id}', [DesignController::class, 'update'])->name('designs.update');
    Route::post('/designs/upload-image', [DesignController::class, 'uploadImage'])->name('designs.upload-image');
    Route::delete('/designs/images/{image}', [DesignController::class, 'deleteImage'])->name('designs.delete-image');
    Route::get('/designs/delete/{id}', [DesignController::class, 'destroy'])->name('designs.destroy');

    Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
    Route::get('/tags/create', [TagController::class, 'create'])->name('tags.create');
    Route::post('/tags/store', [TagController::class, 'store'])->name('tags.store');
    Route::get('/tags/edit/{id}', [TagController::class, 'showEditForm'])->name('tags.edit');
    Route::patch('/tags/update/{id}', [TagController::class, 'update'])->name('tags.update');
    Route::get('/tags/delete/{id}', [TagController::class, 'destroy'])->name('tags.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/edit/{id}', [CategoryController::class, 'showEditForm'])->name('categories.edit');
    Route::patch('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::get('/categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

require __DIR__.'/auth.php';
