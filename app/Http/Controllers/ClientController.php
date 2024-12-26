<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{

    public function home()
    {
        $products = Product::all();
        return view('clients.home', compact('products'));
    }

    public function addToCart(Product $product, Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($product->stock < $request->quantity) {
            return back()->withErrors(['stock' => 'Stock insuffisant.']);
        }

        session()->push('cart', [
            'product' => $product,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $request->quantity,
            'image' => $product->image(),
            'total' => $request->quantity*$product->price
        ]);

        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function removeFromCart ($name) {

        $cart = session('cart', []);

        $cart = array_filter($cart, function ($item) use ($name) {
            return $item['name'] != $name;
        });

        session(['cart' => $cart]);

        return back()->with('success', 'Produit retiré du panier.');
    }

    public function viewCart() {
        return view('clients.checkout');
    }

    public function placeOrder()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Le panier est vide.']);
        }

        $order = Order::create([
            'client_id' => Auth::guard('client')->id(),
            'vendor_id' => Product::find($cart[0]['product']->id)->vendor_id,
            'reference' => $this->generateOrderReference()
        ]);

        foreach ($cart as $item) {
            $order->products()->attach($item['product']->id, ['quantity' => $item['quantity']]);
        }

        session()->forget('cart');

        return redirect()->route('my_orders')->with('success', 'Commande passée avec succès.');
    }

    public function orderHistory()
    {
        $orders = Auth::guard('client')->user()->orders()->with('products')->get();

        return view('clients.orders', compact('orders'));
    }

    function generateOrderReference() {
        $user = Auth::guard('client')->user();
        $initials = strtoupper(substr($user->name, 0, 2));
        $randomNumbers = mt_rand(100000, 999999);
        $reference = $initials . $randomNumbers;
        return $reference;
    }
}
