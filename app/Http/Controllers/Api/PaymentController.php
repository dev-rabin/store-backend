<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Order;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Order already paid'
            ], 400);
        }

        $apiKey = env('A1XPAY_API_KEY');
        $secretSalt = env('A1XPAY_SECRET_SALT');
        $merchantId = env('A1XPAY_MERCHANT_ID');

        $amount = number_format($order->total_amount, 2, '.', '');

        $merchantOrderId = $order->order_number;

        $timestamp = (string) time();

        $canonical = "{$amount}|{$merchantOrderId}|{$timestamp}|{$merchantId}";

        $signature = hash_hmac(
            'sha256',
            $canonical,
            $secretSalt
        );

        // For Local 
        // $response = Http::withHeaders([
        //     'X-Api-Key' => $apiKey,
        //     'X-Signature' => $signature,
        //     'X-Timestamp' => $timestamp,
        // ])->post('https://api.a1xpay.com/api/v1/create-order', [
        //     'amount' => $amount,
        //     'merchant_order_id' => $merchantOrderId,
        //     'redirect_url' => env('A1XPAY_REDIRECT_URL'),
        //     'webhook_url' => env('A1XPAY_WEBHOOK_URL'),
        // ]);

        // For Production 
        $response = Http::withoutVerifying()
        ->withHeaders([
            'X-Api-Key' => $apiKey,
            'X-Signature' => $signature,
            'X-Timestamp' => $timestamp,
        ])
        ->post('https://api.a1xpay.com/api/v1/create-order', [
            'amount' => $amount,
            'merchant_order_id' => $merchantOrderId,
            'redirect_url' => env('A1XPAY_REDIRECT_URL'),
            'webhook_url' => env('A1XPAY_WEBHOOK_URL'),
        ]);

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway error',
                'response' => $response->json(),
            ], 500);
        }

        $data = $response->json();

        $order->update([
            'payment_method' => 'A1XPAY',
            'gateway_order_id' => $data['txn_id'] ?? null,
            'payment_response' => $data,
        ]);

        return response()->json([
            'success' => true,
            'checkout_url' => $data['checkout_url'] ?? null,
            'gateway_response' => $data,
        ]);
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();

        $signature = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');

        $data = json_decode($payload, true);

        $canonical =
            $data['txn_id'] . '|' .
            $data['status'] . '|' .
            $data['amount'] . '|' .
            $data['merchant_order_id'] . '|' .
            $timestamp;

        $expected = hash_hmac(
            'sha256',
            $canonical,
            env('A1XPAY_SECRET_SALT')
        );

        // Enable after testing
        // if (!hash_equals($expected, $signature)) {
        //     return response()->json([
        //         'message' => 'Invalid signature'
        //     ], 401);
        // }

        if (($data['status'] ?? '') === 'success') {

            $order = Order::where(
                'order_number',
                $data['merchant_order_id']
            )->first();

            if ($order && $order->payment_status !== 'paid') {

                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'transaction_id' => $data['txn_id'],
                    'paid_at' => now(),
                    'payment_response' => $data,
                ]);
            }
        }

        return response()->json([
            'received' => true
        ]);
    }
}