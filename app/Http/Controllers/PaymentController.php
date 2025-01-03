<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Kkiapay\Kkiapay;

class PaymentController extends Controller
{
    public function handleRedirect(Request $request)
    {
//        $transactionId = $request->query('transaction_id');
//
//        if (!$transactionId) {
//            return redirect()->route('checkout')->with('errors', "Invalid transaction");
//        }
//
//        $kkiapay = new Kkiapay(
//            env("KKIAPAY_PUBLIC_KEY"),
//            env("KKIAPAY_PRIVATE_KEY"),
//            env("KKIAPAY_SECRET_KEY"),
//            env("SANDBOX")? "true" : 'false',
//        );
//
//        $transaction = $kkiapay->verifyTransaction($transactionId);
//
//        if($transaction->status == "SUCCESS") {
            $this->placeOrder();
            return redirect()->route('my_orders')->with('success', 'Order placed successfully.');
//        }
//        return redirect()->route('checkout')->with('error', 'Payment is failed');
    }

    public function placeOrder()
    {
        $cart = session('cart', []);

        $total = 0;
        foreach ($cart as $item){
            $total = $total + $item['total'];
        }

        $order = Order::create([
            'client_id' => Auth::guard('client')->id(),
            'vendor_id' => Product::find($cart[0]['product']->id)->vendor_id,
            'total' => $total,
            'delivery_address' => Auth::guard('client')->user()->shipping_address,
            'reference' => $this->generateOrderReference(),
            'transaction_id' => "o",
        ]);

        foreach ($cart as $item) {
            $order->products()->attach($item['product']->id, [
                'quantity' => $item['quantity'],
                'total' => $item['quantity'] * $item['price']
            ]);
            $product = Product::find($item['product']->id);
            $product->update([
                'stock' => $product->stock - $item['quantity'],
                'sales' => $product->sales + $item['quantity']
            ]);
        }

        session()->forget('cart');
    }

    function generateOrderReference() {
        $user = Auth::guard('client')->user();
        $initials = strtoupper(substr($user->name, 0, 2));
        $randomNumbers = mt_rand(10000000, 99999999);
        $reference = "O".$initials . $randomNumbers;
        return $reference;
    }


}
