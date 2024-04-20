<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Contact;

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
