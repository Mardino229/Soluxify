<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function dashboard()
    {
        $products = Auth::guard('vendor')->user()->products;
        return view('vendors.dashboard', compact('products'));
    }

    public function addProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
        ]);

        Auth::guard('vendor')->user()->products()->create($request->all());

        return back()->with('success', 'Produit ajouté avec succès.');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())->findOrFail($id);

        $product->update($request->all());

        return back()->with('success', 'Produit modifié avec succès.');
    }

    public function deleteProduct($id)
    {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())->findOrFail($id);

        $product->delete();

        return back()->with('success', 'Produit supprimé avec succès.');
    }

    public function viewOrders()
    {
        $orders = Auth::guard('vendor')->user()->orders()->with('products.client')->get();

        return view('vendors.orders', compact('orders'));
    }
}
