<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Auth::guard('vendor')->user()->products;

        return view('products.index', compact('products'));
    }

    public function view($id) {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())->findOrFail($id);

        return view('clients.product', compact('product'));
    }

    // Enregistrer un nouveau produit
    public function store(ProductRequest $request)
    {
        $request->validated();

        $image = $request->file('image');

        $product = Auth::guard('vendor')->user()->products()->create($request->all());
        if ($image) {
            $product->image = $image->store('images', 'public');
            $product->save();
        }
        return redirect()->route('dashboard')->with('success', 'Produit ajouté avec succès.');
    }

    // Mettre à jour un produit
    public function update(ProductRequest $request, $id)
    {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())->findOrFail($id);

        $request->validated();

        $product->update($request->all());

        $image = $request->file('image');

        if ($image) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $image->store('images', 'public');
            $product->save();
        }

        return redirect()->route('dashboard')->with('success', 'Produit mis à jour avec succès.');
    }
    // Supprimer un produit
    public function destroy($id)
    {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())->findOrFail($id);

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Produit supprimé avec succès.');
    }
}
