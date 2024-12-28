<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Kkiapay\Kkiapay;

class OrderController extends Controller
{
    public function viewOrder ($reference) {
        $order = Order::where('reference', $reference)->first();
        return view('clients.order', compact("order"));
    }

    public function validateOrder($reference) {
        $order = Order::where('reference', $reference)->first();
        if ($order->status == "Cancelled") {
            return back()->with("error", "Operation failed. We cannot validate cancelled order");
        }
        $order->status = "Validated";
        $order->save();
        return back()->with("success", "Operation successful. Order validated successfully");
    }
    public function cancelledOrder($reference) {
        $order = Order::where('reference', $reference)->first();
        $vendor = Vendor::find($order->vendor_id);
        $kkiapay = new Kkiapay(
            $vendor->kkiapay_public_key,
            $vendor->kkiapay_private_key,
            $vendor->kkiapay_secret,
            $sandbox = true
        );
        $kkiapay->refundTransaction($order->transaction_id);
        $transaction = $kkiapay->verifyTransaction($order->transaction_id);

        if($transaction->status == "REVERTED") {
            $order->status = "Cancelled";
            $order->save();
            foreach ($order->products as $product) {
                $product->update([
                    'stock' => $product->stock + $product->pivot->quantity,
                    'sales' => $product->sales - $product->pivot->quantity
                ]);
            }
            return back()->with("success", "Operation successful. Order cancelled successfully, Repaid successfully");
        }

        return back()->with("error", "Operation failed. Order cancellation failed, Repaid failed");
    }
    public function refusedOrder($reference) {
        $order = Order::where('reference', $reference)->first();
        $vendor = Vendor::find($order->vendor_id);
        $kkiapay = new Kkiapay(
            $vendor->kkiapay_public_key,
            $vendor->kkiapay_private_key,
            $vendor->kkiapay_secret,
            $sandbox = true
        );
        $kkiapay->refundTransaction($order->transaction_id);
        $transaction = $kkiapay->verifyTransaction($order->transaction_id);

        if($transaction->status == "REVERTED") {
            $order->status = "Cancelled";
            $order->save();
            foreach ($order->products as $product) {
                $product->update([
                    'stock' => $product->stock + $product->pivot->quantity,
                    'sales' => $product->sales - $product->pivot->quantity
                ]);
            }
            $order->status = "Refused";
            $order->save();
            foreach ($order->products as $product) {
                $product->update([
                    'stock' => $product->stock + $product->pivot->quantity,
                    'sales' => $product->sales - $product->pivot->quantity
                ]);
            }
            return back()->with("success", "Operation successful. Order refused successfully, , Client repaid successfully");
        }

        return back()->with("error", "Operation failed. Order Decline Failed, Repaid of client failed");

    }
}
