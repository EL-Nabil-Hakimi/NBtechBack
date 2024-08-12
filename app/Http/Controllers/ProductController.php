<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::with('images')->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'oldprice' => 'nullable|numeric',
            'contite' => 'required',
            'shipping' => 'required',
            'disponible' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' 
        ]);

        $product = Product::create($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public'); 
                $product->images()->create(['name' => $path]);
            }
        }

        return response()->json([
            'message' => 'Product created successfully.',
            'product' => $product->load('images')
        ], 201);
    }

    // Display the specified product
    public function show(Product $product)
    {
        $product->load('images');
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric',
            'oldprice' => 'nullable|numeric',
            'contite' => 'required',
            'shipping' => 'required',
            'disponible' => 'required|boolean',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048' 
        ]);

        $product->update($validated);

        if ($request->hasFile('images')) {
            $product->images()->delete();

            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public'); 
                $product->images()->create(['name' => $path]);
            }
        }

        return response()->json([
            'message' => 'Product updated successfully.',
            'product' => $product->load('images')
        ]);
    }

    public function destroy(Product $product)
    {
        $product->images()->each(function ($image) {
            Storage::disk('public')->delete($image->name);
        });
        $product->images()->delete();
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully.'
        ]);
    }
}
