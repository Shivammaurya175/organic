<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class ProductController extends Controller
{



public function add_category()
{
    return view('Admin.create_category');
}

public function store_category(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:2048',
    ]);

       if ($request->hasFile('image')) {
        $path = $request->file('image')->store('categories', 'public');
        $validated['image'] = $path;
    }
    $validated['slug'] = Str::slug($validated['name']);

    Category::create($validated);

    return redirect()->route('categories.create')->with('success', 'Category added.');
}


    // Show form to create a new product
    public function create()
    {
        $categories = Category::all();
        return view('Admin.create', compact('categories'));
    }

    // Handle the form submission and store product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'volume_point' => 'required|integer',
            'category_id' => 'required|exists:categories,id', // 2MB max
            'dp_price' =>  ['required', 'numeric', 'min:0'], // 2MB max
        ]);

        if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $validated['image'] = $path;
    }

        Product::create($validated);

        return redirect()->route('products.create')->with('success', 'Product added successfully!');
    }

    public function destroy(Product $product)
{
    $product->delete();
    return back()->with('success', 'Product soft deleted.');
}

public function restore($id)
{
    $product = Product::withTrashed()->findOrFail($id);
    $product->restore();
    return back()->with('success', 'Product restored.');
}
public function edit(Product $product)
{
    $categories = Category::all();
    return view('Admin.product_edit', compact('product', 'categories'));
}
public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'dp_price' => 'nullable|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'volume_point' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($validated);

    return redirect()->route('products.edit', $product->id)->with('success', 'Product updated successfully!');
}


public function product_list()
{
    $products = Product::withTrashed()->get();
    return view('Admin.product_list', compact('products'));
}

}
