<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\Admin\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Show comments for a specific product
Route::post('/productComment/{product_id}', [CommentController::class, 'index']);

Route::middleware(['auth'])->group(function(){
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/change-password', [PasswordController::class, 'changeUserPassword']);

    // Store a new comment
    Route::post('/productComment', [CommentController::class, 'storeComment']);

    // Store a new review/rating
    Route::post('/productComment/review', [CommentController::class, 'reviewstore']);
});

// Admin Dashboard Routes :
Route::middleware(['isAdmin'])->group(function(){
    // Categories index :
    Route::get('/dashboard/categories', [DashboardController::class, 'categories'])->name('dashboard.categories');
    // Add Category :
    Route::post('/dashboard/categories/store', [CategoryController::class, 'store'])->name('dashboard.categories.store');
    // Update Category :
    Route::put('/dashboard/categories/update/{id}', [CategoryController::class, 'update'])->name('dashboard.categories.update');
    // Delete Category :
    Route::post('/dashboard/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('dashboard.categories.destroy');

    // Products index :
    Route::get('/dashboard/products', [DashboardController::class, 'products'])->name('dashboard.products');
    // Add Product :
    Route::post('/dashboard/products/store', [ProductController::class, 'store'])->name('dashboard.products.store');
    // Update Product :
    Route::put('/dashboard/products/update/{id}', [ProductController::class, 'update'])->name('dashboard.products.update');
    // Delete Product :
    Route::post('/dashboard/products/destroy/{id}', [ProductController::class, 'destroy'])->name('dashboard.products.destroy');
    // Update Is Published Product :
    Route::put('/dashboard/products/update_is_published/{id}', [ProductController::class, 'updateIsPublished'])->name('dashboard.products.updateIsPublished');
    
    // Bset Sellings index :
    Route::get('/dashboard/best_sellings_books', [DashboardController::class, 'best_sellings_books'])->name('dashboard.best_sellings_books');
    // Update Best Selling Product :    
    Route::put('/dashboard/products/update_best_sellings/{id}', [ProductController::class, 'updateIsBsetSelling'])->name('dashboard.products.updateIsBestSellings');
    
    // Comments index :
    Route::get('/dashboard/comments', [DashboardController::class, 'comments'])->name('dashboard.comments');
    // Post Comment :
    Route::put('/dashboard/comments/post/{id}', [CommentController::class, 'update'])->name('dashboard.comments.post');
    // Delete Comment :
    Route::post('/dashboard/comments/destroy/{id}', [CommentController::class, 'destroy'])->name('dashboard.comments.destroy');
    // Show Comment :
    Route::get('/dashboard/comments/show/{id}', [CommentController::class, 'show'])->name('dashboard.comments.show');
    
    // Contacts index :
    Route::get('/dashboard/contacts', [DashboardController::class, 'contacts'])->name('dashboard.contacts');
    // Show Contact :
    Route::get('/dashboard/contacts/show/{id}', [DashboardController::class, 'show'])->name('dashboard.contacts.show');
    
});
