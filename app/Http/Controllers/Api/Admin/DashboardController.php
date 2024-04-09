<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class DashboardController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth');
    // }
    public function products() {
        $products = Product::with('category')
        ->orderBy('created_at', 'desc') // Sort by creation date in descending order
        ->get();

    // Return the latest products as JSON
    return response()->json($products);
    }
}
