<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Data Product";
        $datas = products::with('category')->get();
        return view('products.index', compact('title', 'datas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Categories::orderBy('id', 'asc')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'is_active' => $request->is_active
        ];

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('product', 'public');
            $data['product_photo'] = $photo;
        }
        Products::create($data);
        return redirect()->to('products');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $edit = products::find($id);
        $categories = Categories::orderBy('id', 'asc')->get();
        return view('products.edit', compact('edit', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_price' => $request->product_price,
            'product_description' => $request->product_description,
            'is_active' => $request->is_active
        ];

        $product = products::find($id);
        if ($request->hasFile('photo')) {
            if ($product->product_photo) {
                File::delete(public_path('storage/' . $product->product_photo));
            }
            $photo = $request->file('photo')->store('product', 'public');
            $data['product_photo'] = $photo;
        }

        $product->update($data);
        return redirect()->to('products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = products::find($id);
        File::delete(public_path('storage/' . $product->product_photo));
        $product->delete();
        return redirect()->to('products');
    }
}
