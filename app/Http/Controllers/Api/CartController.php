<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
        {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $user = $request->user();

            $cart = Cart::where('user_id', $user->id)
                ->where('product_id', $request->product_id)
                ->first();

            if ($cart) {
                $cart->increment('quantity');
            } else {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $request->product_id,
                    'quantity' => 1
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart'
            ]);
        }

    public function getCart(Request $request)
        {
            $cart = Cart::with('product')
                ->where('user_id', $request->user()->id)
                ->get();

            return response()->json([
                'success' => true,
                'cart' => $cart
            ]);
        }

    public function updateCart(Request $request, $id)
        {
            $cart = Cart::findOrFail($id);

            $cart->update([
                'quantity' => $request->quantity
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated'
            ]);
        }

    public function removeCart($id)
        {
            Cart::findOrFail($id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed'
            ]);
        }
}
