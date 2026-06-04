<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $address = \App\Models\Address::where('id', $request->address_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty'
            ], 400);
        }

        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,

            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        // Cart::where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'redirect_url' => "/orders/{$order->id}",
            'message' => 'Order created successfully',
            'order' => [
                'id' => $order->id,
                'address_id' => $order->address_id,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at,
            ]
        ], 201);
    }

    public function getOrders(Request $request)
        {
            $orders = Order::where('user_id', $request->user()->id)
                ->latest()
                ->get();

            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);
        }

        public function getOrder($id, Request $request)
        {
            $order = Order::with([
                'address',
                'items.product'
            ])
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'order' => $order
            ]);
        }
}
