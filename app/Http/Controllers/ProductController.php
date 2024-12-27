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

    public function view($reference) {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())
            ->where('reference',$reference)->first();

        return view('clients.product', compact('product'));
    }

    // Enregistrer un nouveau produit
    public function store(ProductRequest $request)
    {
        if (Auth::guard('vendor')->user()->kkiapay_id == "") {
            return back()->with("error", "Please fill your billing address in section My Account->Payment Data");
        }

        $request->validated();

        $image = $request->file('image');
        $request['reference'] = $this->generateProducReference();
        $product = Auth::guard('vendor')->user()->products()->create($request->all());
        if ($image) {
            $product->image = $image->store('images', 'public');
            $product->save();
        }
        return redirect()->route('dashboard')->with('success', 'Product created successfully');
    }

    // Mettre à jour un produit
    public function update(ProductRequest $request, $reference)
    {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())
            ->where('reference',$reference)->first();;

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

        return redirect()->route('dashboard')->with('success', 'Product updated successfully');
    }
    // Supprimer un produit
    public function destroy($reference)
    {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())
            ->where('reference',$reference)->first();

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('dashboard')->with('success', 'Product deleted successfully');
    }

    function generateProducReference() {
        $user = Auth::guard('vendor')->user();
        $initials = strtoupper(substr($user->name, 0, 2));
        $randomNumbers = mt_rand(10000000, 99999999);
        $reference = "P".$initials . $randomNumbers;
        return $reference;
    }

}
