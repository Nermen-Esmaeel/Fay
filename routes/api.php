<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\UserController;
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

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function(){
    Route::post('auth/logout', [AuthController::class, 'logout']);

// Show comments for a specific product
Route::get('/productComment/{product_id}', [CommentController::class, 'index']);

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

// Update a comment (only admin)
// Route::put('/productComment/{comment_id}', [CommentController::class, 'update']);

// Delete a comment (only admin)
// Route::delete('/productComment/{comment_id}', [CommentController::class, 'destroy']);

});
