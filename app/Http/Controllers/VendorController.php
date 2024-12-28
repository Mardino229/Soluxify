<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function dashboard()
    {
        $products = Auth::guard('vendor')->user()->products()->orderBy('created_at', 'desc')->get();
        return view('vendors.dashboard', compact('products'));
    }

    public function viewOrder ($reference) {
        $order = Order::where('reference', $reference)->first();
        return view('vendors.order', compact("order"));
    }
    public function viewOrders()
    {
        $orders = Auth::guard('vendor')->user()->orders()->with('products')->orderBy('created_at', 'desc')->get();

        return view('vendors.orders', compact('orders'));
    }

    public function update_payment_address (Request $request) {
        $request->validate([
            'kkiapay_public_key' => 'required',
            'kkiapay_secret_key' => 'required',
            'kkiapay_private_key' => 'required',
        ]);
        $user = Auth::guard('vendor')->user();
        $user->update([
            "kkiapay_public_key"=>$request->kkiapay_public_key,
            "kkiapay_secret_key"=>$request->kkiapay_secret_key,
            "kkiapay_private_key"=>$request->kkiapay_private_key,
        ]);
        config([
            'kkiapay.public_key' => $user->kkiapay_public_key,
            'kkiapay.secret_key' => $user->kkiapay_secret_key,
            'kkiapay.private_key' => $user->kkiapay_private_key,
        ]);
        return back()->with('success', "Kkiapay account updated successfully");
    }
}
