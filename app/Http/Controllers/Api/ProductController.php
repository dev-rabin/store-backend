<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Get all products
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->filled('search')) {
            $products->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $products->where('category', $request->category);
        }

        return response()->json([
            'success' => true,
            'products' => $products->latest()->get()
        ]);
    }

    // Get single product
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    // Create product
    public function store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'desc' => $request->desc,
            'category' => $request->category,
            'img' => $request->img
        ]);

        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }


    public function categories()
    {
        $categories = \App\Models\Product::distinct()
            ->pluck('category');

        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    public function newArrivals()
        {
            $products = Product::where('is_new_arrival', true)
                ->latest()
                ->take(10)
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products,
            ]);
        }
}