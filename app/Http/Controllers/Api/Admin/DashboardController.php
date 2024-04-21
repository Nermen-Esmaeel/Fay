<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Comment;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function Laravel\Prompts\select;
use App\Http\Resources\ProductResource;

class DashboardController extends Controller
{
    public function categories() {
        // Retrieve all categories with product counts
        $categories = Category::withCount('products')->get();

        // Return a JSON response
        return response()->json(['categories' => $categories]);
    }

    public function categoryProducts($id) {
        // Retrieve all categories with product counts
        $products = Product::where('category_id', $id)
            ->select('id', 'name')
            ->get();

        // Return a JSON response
        return response()->json($products);
    }

    public function showProduct($id) {
        $product = Product::findOrFail($id);

        return response()->json(new ProductResource($product));
    }

    public function products() {
        $products = Product::with(['category','cards','ebooks'])
            ->orderBy('created_at', 'desc') // Sort by creation date in descending order
            ->get();

        // Return the latest products as JSON
        return response()->json(ProductResource::collection($products));
    }

    public function searchProducts(Request $request)
    {
        $searchTerm = $request->input('search'); // Get the search term from the user

        // Query products based on the search term (case-insensitive)
        $products = Product::where('name', 'like', '%' . $searchTerm . '%')->get();

        return response()->json(['products' => $products]);
    }

    public function comments()
    {
        // Retrieve comments with user and product information
        $comments = Comment::with(['user:id,name,email', 'product:id,name'])
            ->select('text', 'is_approved', 'rating')
            ->latest() // Order by the latest update
            ->get();

        // Return a JSON response
        return response()->json(['comments' => $comments]);
    }

    public function contacts()
    {
        // Retrieve contacts with user information
        $contacts = Contact::select('id','name', 'email', 'message')
            ->latest() // Order by the latest update
            ->get();

        // Return a JSON response
        return response()->json(['contacts' => $contacts]);
    }

    public function show($id)
    {
        // Retrieve the contact by its ID
        $contact = Contact::findOrFail($id);

        // Return the contact and user as a JSON response
        return response()->json([
            'contact' => $contact
        ]);
    }
}
