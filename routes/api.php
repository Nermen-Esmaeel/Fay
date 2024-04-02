<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Show comments for a specific product
Route::get('/productComment/{product_id}', [CommentController::class, 'index']);

// Store a new comment
Route::post('/productComment', [CommentController::class, 'storeComment'])->middleware('auth');

// Store a new review/rating
Route::post('/productComment/review', [CommentController::class, 'reviewstore'])->middleware('auth');

// Update a comment (only authenticated users)
// Route::put('/productComment/{comment_id}', [CommentController::class, 'update'])->middleware('auth');

// Delete a comment (only authenticated users)
// Route::delete('/productComment/{comment_id}', [CommentController::class, 'destroy'])->middleware('auth');
