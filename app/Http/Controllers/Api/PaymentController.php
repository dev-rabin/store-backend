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
        
        \Log::info('A1XPAY HEADERS', [
            'apiKey_exists' => !empty($apiKey),
            'merchantId_exists' => !empty($merchantId),
            'api_key' => $apiKey,
            'merchant_id' => $merchantId,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ]);

        \Log::info('A1XPAY REQUEST BODY', [
            'amount' => $amount,
            'merchant_order_id' => $merchantOrderId,
            'redirect_url' => env('A1XPAY_REDIRECT_URL'),
            'webhook_url' => env('A1XPAY_WEBHOOK_URL'),
        ]);

        // For Production
        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey,
            'X-Signature' => $signature,
            'X-Timestamp' => $timestamp,
        ])->post('https://api.a1xpay.com/api/v1/create-order', [
            'amount' => $amount,
            'merchant_order_id' => $merchantOrderId,
            'redirect_url' => env('A1XPAY_REDIRECT_URL'),
            'webhook_url' => env('A1XPAY_WEBHOOK_URL'),
        ]);
        
         \Log::info('A1XPAY CONFIG', [
            'api_key_exists' => !empty(env('A1XPAY_API_KEY')),
            'merchant_id_exists' => !empty(env('A1XPAY_MERCHANT_ID')),
            'secret_salt_exists' => !empty(env('A1XPAY_SECRET_SALT')),
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
    \Log::info('================ WEBHOOK START ================');

    try {

        $payload = $request->getContent();

        \Log::info('PAYMENT WEBHOOK HIT');

        \Log::info('WEBHOOK HEADERS', $request->headers->all());

        \Log::info('WEBHOOK RAW BODY', [
            'body' => $payload
        ]);

        $data = json_decode($payload, true);

        \Log::info('WEBHOOK JSON', [
            'data' => $data
        ]);

        $signature = $request->header('X-Signature');
        $timestamp = $request->header('X-Timestamp');

        \Log::info('SIGNATURE DATA', [
            'signature' => $signature,
            'timestamp' => $timestamp,
            'secret_exists' => !empty(env('A1XPAY_SECRET_SALT'))
        ]);

        $canonical =
            ($data['txn_id'] ?? '') . '|' .
            ($data['status'] ?? '') . '|' .
            ($data['amount'] ?? '') . '|' .
            ($data['merchant_order_id'] ?? '') . '|' .
            $timestamp;

        \Log::info('CANONICAL STRING', [
            'canonical' => $canonical
        ]);

        $expected = hash_hmac(
            'sha256',
            $canonical,
            env('A1XPAY_SECRET_SALT')
        );

        \Log::info('SIGNATURE CHECK', [
            'expected' => $expected,
            'received' => $signature,
            'matched' => hash_equals($expected, $signature ?? '')
        ]);

        // TEMPORARILY DISABLE THIS
        /*
        if (!hash_equals($expected, $signature)) {
            return response()->json([
                'message' => 'Invalid signature'
            ], 401);
        }
        */

        \Log::info('STATUS CHECK', [
            'status' => $data['status'] ?? null
        ]);

        if (($data['status'] ?? '') === 'success') {

            \Log::info('SEARCHING ORDER', [
                'merchant_order_id' => $data['merchant_order_id'] ?? null
            ]);

            $order = Order::where(
                'order_number',
                $data['merchant_order_id']
            )->first();

            \Log::info('ORDER RESULT', [
                'found' => $order ? true : false,
                'order_id' => $order?->id
            ]);

            if ($order) {

                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'transaction_id' => $data['txn_id'] ?? null,
                    'paid_at' => now(),
                    'payment_response' => $data,
                ]);

                \Log::info('ORDER UPDATED SUCCESSFULLY', [
                    'order_id' => $order->id
                ]);
            }
        }

        return response()->json([
            'received' => true
        ]);

    } catch (\Exception $e) {

        \Log::error('WEBHOOK EXCEPTION', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        return response()->json([
            'success' => false
        ], 500);
    }
}

    public function getOrderDetails($orderNumber)
    {
        $order = Order::where(
            'order_number',
            $orderNumber
        )->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order_number' => $order->order_number,
            'transaction_id' => $order->transaction_id,
            'payment_status' => $order->payment_status,
            'status' => $order->status,
        ]);
    }

}