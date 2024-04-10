<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function homeBestSelling () {
        // Retrieve the latest 6 products where 'is_best_selling' column is 1
        $bestSellingProducts = Product::where('is_best_selling', 1)
        ->latest()
        ->take(6)
        ->with('category') // Load the associated category
        ->get();

        // Prepare the response data
        $responseData = $bestSellingProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'image' => $product->image,
                'name' => $product->name,
                'age' => $product->age,
                'about' => $product->about,
                'category' => $product->category->name, // Get the category name
            ];
        });

        // Return the best-selling products with category names as a JSON response
        return JsonResponse::json(['products' => $responseData]);
    }

    public function homeReviews () {
        // Retrieve the latest 10 comments with associated product and user details
        $latestComments = Comment::with(['product', 'user'])
            ->where('is_approved', 1) // Only approved comments
            ->latest()
            ->take(10)
            ->get();

        // Prepare the response data
        $responseData = $latestComments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user_name' => $comment->user->name,
                'product_name' => $comment->product->name,
                'text' => $comment->text,
                'is_approved' => $comment->is_approved,
                'created_at' => $comment->created_at,
            ];
        });

        // Return the latest comments with additional details as a JSON response
        return JsonResponse::json(['comments' => $responseData]);
    }

    public function products () {
        // Retrieve published products
        $publishedProducts = Product::where('is_published', 1)
            ->select('id', 'image_path')
            ->paginate(10); // Paginate with 10 products per page

        // Return as JSON API
        return response()->json([
            'data' => $publishedProducts,
        ]);
    }

    public function serach ($search_txt) {
        // Search products by name or description
        $products = Product::whereTranslationLike('name', '%' . $search_txt . '%')
            ->orWhereTranslationLike('description', '%' . $search_txt . '%')
            ->get();

        // Search products by variations (attribute values)
        $productsWithVariations = Product::whereHas('variations', function ($query) use ($search_txt) {
            $query->where('value', 'like', $search_txt);
        })->get();

        // Combine both sets of results
        $combinedProducts = $products->concat($productsWithVariations);

        // Return the combined products as a JSON response
        return JsonResponse::json(['products' => $combinedProducts]);
    }

    public function showProduct ($id) {
        // Retrieve the product by ID
        $product = Product::findOrFail($id);

        // Construct the JSON response
        return response()->json([
            'id' => $product->id,
            'category_id' => $product->category_id,
            'name' => $product->name,
            'age' => $product->age,
            'about' => $product->about,
            'image_path' => $product->image_path,
            'arabic_file_path' => $product->arabic_file_path,
            'english_file_path' => $product->english_file_path,
            'e_book_file_path' => $product->e_book_file_path,
            'exercises_file_path' => $product->exercises_file_path,
            'cards_file_path' => $product->cards_file_path,
            'short_Story_file_path' => $product->short_Story_file_path,
        ]);
    }

    public function showCommentProduct ($id) {
        $comments = Comment::whith(['user'])
            ->where('product_id', $id)
            ->get();

        return response()->json([
            'comments' => $comments,
        ]);
    }
    public function showRelatedProducts ($id) {

    }

    public function contact () {

    }
}
