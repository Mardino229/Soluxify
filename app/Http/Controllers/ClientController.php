<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
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
        $client = Auth::guard('client')->user();
        if (!$client->shipping_address) {
            return back()->with("error", "Please fill your shipping address in section Account->Shipping Data");
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stock is out of stock');
        }

        session()->push('cart', [
            'product' => $product,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $request->quantity,
            'image' => $product->image(),
            'total' => $request->quantity*$product->price
        ]);

        return back()->with('success', 'Product added to cart successfully!');
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
        $cart = session('cart', []);
        if (!empty($cart)) {
            $kkiapay_public_key = Vendor::find(Product::find($cart[0]['product']->id)->vendor_id)->kkiapay_id;
            return view('clients.checkout', compact( "kkiapay_public_key"));
        }
        return view('clients.checkout');
    }

//    public function placeOrder()
//    {
//
//        $cart = session('cart', []);
//        if (empty($cart)) {
//            return back()->with('error', 'The cart is empty');
//        }
//
//        $total = 0;
//        foreach ($cart as $item){
//            $total = $total + $item['total'];
//        }
//
//        $order = Order::create([
//            'client_id' => Auth::guard('client')->id(),
//            'vendor_id' => Product::find($cart[0]['product']->id)->vendor_id,
//            'total' => $total,
//            'delivery_address' => Auth::guard('client')->user()->shipping_address,
//            'reference' => $this->generateOrderReference()
//        ]);
//
//        foreach ($cart as $item) {
//            $order->products()->attach($item['product']->id, [
//                'quantity' => $item['quantity'],
//                'total' => $item['quantity'] * $item['price']
//            ]);
//            $product = Product::find($item['product']->id);
//            $product->update([
//                'stock' => $product->stock - $item['quantity'],
//                'sales' => $product->sales + $item['quantity']
//            ]);
//        }
//
//        session()->forget('cart');
//
//        return redirect()->route('my_orders')->with('success', 'Order placed successfully.');
//    }

    public function orderHistory()
    {
        $orders = Auth::guard('client')->user()->orders()->with('products')->orderBy('created_at', 'desc')->get();

        return view('clients.orders', compact('orders'));
    }

    public function update_shipping_address (Request $request) {
        $request->validate([
            'shipping_address' => 'required',
        ]);
        $user = Auth::guard('client')->user();
        $user->update([
            "shipping_address"=>$request->shipping_address,
        ]);
        return back()->with('success', "Shipping address updated successfully");
    }
//
//    function generateOrderReference() {
//        $user = Auth::guard('client')->user();
//        $initials = strtoupper(substr($user->name, 0, 2));
//        $randomNumbers = mt_rand(10000000, 99999999);
//        $reference = "O".$initials . $randomNumbers;
//        return $reference;
//    }
}
