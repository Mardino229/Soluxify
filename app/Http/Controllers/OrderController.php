<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function viewOrder ($reference) {
        $order = Order::where('reference', $reference)->first();
        return view('clients.order', compact("order"));
    }
}
