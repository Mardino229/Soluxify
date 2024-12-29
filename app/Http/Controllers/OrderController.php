<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        $vendor = Vendor::find($order->vendor_id);
//        $result = $this->payVendor($vendor->kkiapay_id, $order->total, $order->reference);
//        $order->update([
//            'payment_status' => 'paid',
//            'transaction_id' => $result['transaction_id']
//        ]);
            $order->status = "Validated";
            $order->save();
            return back()->with("success", "Operation successful. Order validated successfully");
//        }
//        return back()->with("error", "Operation unsuccessful. Order not validated. Transaction error");
    }
    public function cancelledOrder($reference) {
        $order = Order::where('reference', $reference)->first();
//        $kkiapay = new Kkiapay(
//            env("KKIAPAY_PUBLIC_KEY"),
//            env("KKIAPAY_PRIVATE_KEY"),
//            env("KKIAPAY_SECRET_KEY"),
//            env("SANDBOX")? "true" : 'false',
//        );
//        $kkiapay->refundTransaction($order->transaction_id);
//        $transaction = $kkiapay->verifyTransaction($order->transaction_id);
//
//        if($transaction->status == "REVERTED") {
            $order->status = "Cancelled";
            $order->save();
            foreach ($order->products as $product) {
                $product->update([
                    'stock' => $product->stock + $product->pivot->quantity,
                    'sales' => $product->sales - $product->pivot->quantity
                ]);
            }
            return back()->with("success", "Operation successful. Order cancelled successfully, Repaid successfully");
//        }
//
//        return back()->with("error", "Operation failed. Order cancellation failed, Repaid failed");
    }
    public function refusedOrder($reference) {
        $order = Order::where('reference', $reference)->first();
//        $kkiapay = new Kkiapay(
//            env("KKIAPAY_PUBLIC_KEY"),
//            env("KKIAPAY_PRIVATE_KEY"),
//            env("KKIAPAY_SECRET_KEY"),
//            env("SANDBOX")? "true" : 'false',
//        );
//        $kkiapay->refundTransaction($order->transaction_id);
//        $transaction = $kkiapay->verifyTransaction($order->transaction_id);
//
//        if($transaction->status == "REVERTED") {
            $order->status = "Refused";
            $order->payment_status = "Reverted";
            $order->save();
            foreach ($order->products as $product) {
                $product->update([
                    'stock' => $product->stock + $product->pivot->quantity,
                    'sales' => $product->sales - $product->pivot->quantity
                ]);
            }
            return back()->with("success", "Operation successful. Order refused successfully");
//        }
//
//        return back()->with("error", "Operation failed. Order Decline Failed, Repaid of client failed");

    }

    public function payVendor(string $kkiapay_id, float $amount, string $description)
    {
        try {
            $response = Http::withHeaders([
                'X-API-KEY' => env("KKIAPAY_PUBLIC_KEY"),
                'Content-Type' => 'application/json',
            ])->post(env('KKIAPAY_BASE_URL', 'https://api.kkiapay.me/api/v1') . '/transfers/send', [
                'amount' => $this->calculateVendorAmount($amount),
                'recipient' => $kkiapay_id,
                'description' => $description,
                'sandbox' => env('SANDBOX', false)
            ]);
            dd($response);
            if (!$response->successful()) {
                throw new \Exception($response->json('message', 'Une erreur est survenue'));
            }

            return [
                'success' => true,
                'transaction_id' => $response->json('transactionId'),
                'message' => 'Paiement effectué avec succès'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function calculateVendorAmount($total_amount): float|int
    {
        $commissionRate = env("COMMISSION", 0.1);
        return $total_amount * (1 - $commissionRate);
    }
}
