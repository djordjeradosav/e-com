<?php

namespace App\Http\Controllers;

use App\Events\ProductStockLow;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    /**
     * Process checkout and create order
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Check stock availability for all items
        foreach ($cartItems as $item) {
            if (!$item->product->isInStock($item->quantity)) {
                return back()->with('error', "Not enough stock for {$item->product->name}.");
            }
        }

        DB::transaction(function () use ($user, $cartItems) {
            // Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'completed',
            ]);

            // Create order items and update stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price_at_purchase' => $cartItem->product->price,
                ]);

                // Decrease stock
                $cartItem->product->decreaseStock($cartItem->quantity);

                // Check if stock is low and trigger event
                if ($cartItem->product->fresh()->isLowStock()) {
                    event(new ProductStockLow($cartItem->product));
                }
            }

            // Clear cart
            $user->cartItems()->delete();
        });

        return redirect()->route('checkout.success')
            ->with('success', 'Order placed successfully!');
    }

    /**
     * Show checkout success page
     */
    public function success()
    {
        return Inertia::render('Checkout/Success');
    }
}