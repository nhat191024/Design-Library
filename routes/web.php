<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DesignController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/designs', [DesignController::class, 'index'])->name('designs.index');
    Route::get('/designs/edit/{id}', [DesignController::class, 'showEditForm'])->name('designs.edit');
    Route::patch('/designs/update/{id}', [DesignController::class, 'update'])->name('designs.update');
    Route::post('/designs/upload-image', [DesignController::class, 'uploadImage'])->name('designs.upload-image');
    Route::delete('/designs/images/{image}', [DesignController::class, 'deleteImage'])->name('designs.delete-image');
    Route::get('/designs/delete/{id}', [DesignController::class, 'destroy'])->name('designs.destroy');
});

require __DIR__.'/auth.php';
