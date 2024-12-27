<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

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
        $order->status = "Cancelled";
        $order->save();
        foreach ($order->products as $product) {
            $product->update([
                'stock' => $product->stock + $product->pivot->quantity,
                'sales' => $product->sales - $product->pivot->quantity
            ]);
        }
        return back()->with("success", "Operation successful. Order cancelled successfully");
    }
    public function refusedOrder($reference) {
        $order = Order::where('reference', $reference)->first();
        $order->status = "Refused";
        $order->save();
        foreach ($order->products as $product) {
            $product->update([
                'stock' => $product->stock + $product->pivot->quantity,
                'sales' => $product->sales - $product->pivot->quantity
            ]);
        }
        return back()->with("success", "Operation successful. Order refused successfully");
    }
}
