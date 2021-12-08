<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|max:50',
                'description' => 'required|max:255',
                'price' => 'required|numeric',
                'stock' => 'required|numeric',
                'category_id' => 'required|numeric',
            ]);

            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'discount' => $request->discount ? $request->discount : 0,
                'category_id' => $request->category_id,
                'image' => $request->image,
            ]);

            return response()->json([
                'message' => 'Product created successfully',
                'product' => $product,
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Product not created',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return Product::find($product->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try {
            $product->update($request->all());

            return response()->json([
                'message' => 'Product updated successfully',
                'product' => $product,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Product not updated',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Product not deleted',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function search($query)
    {
        try {
            $products = Product::where('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->get()
            ;

            return response()->json([
                'message' => 'Products found',
                'products' => $products,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Products not found',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
