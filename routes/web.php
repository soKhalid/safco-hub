<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaGalleryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController as AdminMediaController;
use App\Http\Controllers\Admin\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Frontend Routes
Route::get('/', [MediaGalleryController::class, 'index'])->name('home');
Route::get('/gallery', [MediaGalleryController::class, 'index'])->name('gallery.index');
Route::get('/gallery/search', [MediaGalleryController::class, 'search'])->name('gallery.search');
Route::get('/gallery/type/{type}', [MediaGalleryController::class, 'byType'])->name('gallery.type');
Route::get('/gallery/category/{slug}', [MediaGalleryController::class, 'byCategory'])->name('gallery.category');
Route::get('/media/{id}', [MediaGalleryController::class, 'show'])->name('gallery.show');
Route::get('/media/{media}/download', [MediaGalleryController::class, 'download'])->name('gallery.download');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes (Protected by auth middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Media Management
    Route::resource('media', AdminMediaController::class);
    Route::post('/media/bulk-delete', [AdminMediaController::class, 'bulkDelete'])->name('media.bulk-delete');
    Route::post('/media/{media}/toggle-featured', [AdminMediaController::class, 'toggleFeatured'])->name('media.toggle-featured');
    Route::post('/media/{media}/toggle-active', [AdminMediaController::class, 'toggleActive'])->name('media.toggle-active');

    // Category Management
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('categories.toggle-active');
    Route::post('/categories/update-order', [CategoryController::class, 'updateOrder'])->name('categories.update-order');
});
