<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all products
        $products = Product::with('category')->get();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data (customize as needed)
        $request->validate([
            'category_id' => 'required',
            'name' => 'required|max:70',
            'age' => 'required|max:70',
            'about' => 'required|max:600',
            'image' => 'required|image|max:2048',
            'arabic_file' => 'required|mimes:pdf',
            'english_file' => 'required|mimes:pdf',
            'ebook_file' => 'required|mimes:pdf',
            'exercises_file' => 'required|mimes:pdf',
            'cards_file' => 'required|mimes:pdf',
            'short_story_file' => 'required|mimes:pdf',
        ]);

        // Save image
        $imagePath = $request->file('image')->store('images', 'public');

        // Save PDF files
        $arabicPath = $request->file('arabic_file')->store('arabic_files', 'public');
        $englishPath = $request->file('english_file')->store('english_files', 'public');
        $ebookPath = $request->file('ebook_file')->store('ebook_files', 'public');
        $exercisesPath = $request->file('exercises_file')->store('exercises_files', 'public');
        $cardsPath = $request->file('cards_file')->store('cards_files', 'public');
        $shortStoryPath = $request->file('short_story_file')->store('short_story_files', 'public');

        // Create a new product record
        Product::create([
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'about' => $request->input('about'),
            'image_path' => $imagePath,
            'arabic_file_path' => $arabicPath,
            'english_file_path' => $englishPath,
            'e_book_file_path' => $ebookPath,
            'exercises_file_path' => $exercisesPath,
            'cards_file_path' => $cardsPath,
            'short_Story_file_path' => $shortStoryPath,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Retrieve a specific product
        return response()->json($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:70',
            'age' => 'required|string|max:70',
            'about' => 'required|string|max:600',
            'image' => 'image|mimes:jpeg,png|max:2048', // Validate image file
            'arabic_file' => 'file|mimes:pdf',
            'english_file' => 'file|mimes:pdf',
            'ebook_file' => 'file|mimes:pdf',
            'exercises_file' => 'file|mimes:pdf',
            'cards_file' => 'file|mimes:pdf',
            'short_story_file' => 'file|mimes:pdf',
        ]);

        // Find the product by ID
        $product = Product::findOrFail($id);

        // Update the product attributes
        $product->name = $request->input('name');
        $product->age = $request->input('age');
        $product->about = $request->input('about');

        // Handle image upload (if provided)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $product->image_path = $imagePath;
        }

        // Handle PDF file uploads (if provided)
        if ($request->hasFile('arabic_file')) {
            $product->arabic_file_path = $request->file('arabic_file')->store('arabic_files', 'public');
        }
        if ($request->hasFile('english_file')) {
            $product->english_file_path = $request->file('english_file')->store('english_files', 'public');
        }
        if ($request->hasFile('ebook_file')) {
            $product->e_book_file_path = $request->file('ebook_file')->store('ebook_files', 'public');
        }
        if ($request->hasFile('exercises_file')) {
            $product->exercises_file_path = $request->file('exercises_file')->store('exercises_files', 'public');
        }
        if ($request->hasFile('cards_file')) {
            $product->cards_file_path = $request->file('cards_file')->store('cards_files', 'public');
        }
        if ($request->hasFile('short_story_file')) {
            $product->short_Story_file_path = $request->file('short_story_file')->store('short_story_files', 'public');
        }

        // Save the changes
        $product->save();

        return response()->json(['message' => 'Product updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Retrieve the product by ID
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        
        // Delete the associated image
        if ($product->image_path) {
            Storage::disk('public')->delete('images/' . $product->image_path);
        }

        // Delete the associated Arabic file
        if ($product->arabic_file_path) {
            Storage::disk('public')->delete('arabic_files/' . $product->arabic_file_path);
        }

        // Delete the associated Card file
        if ($product->cards_file_path) {
            Storage::disk('public')->delete('cards_files/' . $product->cards_file_path);
        }
        // Delete the associated E-Book file
        if ($product->e_book_file_path) {
            Storage::disk('public')->delete('ebook_files/' . $product->e_book_file_path);
        }

        // Delete the associated English file
        if ($product->english_file_path) {
            Storage::disk('public')->delete('english_files/' . $product->english_file_path);
        }
        // Delete the associated Exercises file
        if ($product->exercises_file_path) {
            Storage::disk('public')->delete('exercises_files/' . $product->exercises_file_path);
        }

        // Delete the associated Short Story file
        if ($product->short_Story_file_path) {
            Storage::disk('public')->delete('short_story_files/' . $product->short_Story_file_path);
        }

        // Delete the product from the database
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
