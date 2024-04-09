<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;

class DashboardController extends Controller
{
    public function categories() {
        // Retrieve all categories with product counts
        $categories = Category::withCount('products')->get();

        // Return a JSON response
        return response()->json(['categories' => $categories]);
    }

    public function products() {
        $products = Product::with('category')
        ->orderBy('created_at', 'desc') // Sort by creation date in descending order
        ->get();

        // Return the latest products as JSON
        return response()->json($products);
    }

    public function best_sellings_books() {
        // Retrieve selected product attributes
        $products = Product::with(['category:id,cat_name'])
            ->select('id', 'name', 'about', 'image_path', 'is_best_selling')
            ->get();

        // Return a JSON response
        return response()->json(['products' => $products]);
    }

    public function comments(Request $request)
    {
        // Retrieve comments with user and product information
        $comments = Comment::with(['user:id,name,email', 'product:id,name'])
            ->select('text', 'is_approved', 'rating')
            ->latest() // Order by the latest update
            ->get();

        // Return a JSON response
        return response()->json(['comments' => $comments]);
    }
}
