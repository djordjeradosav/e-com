<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart
     */
    public function index(Request $request): Response
    {
        $cartItems = $request->user()
            ->cartItems()
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return Inertia::render('Cart/Index', [
            'cartItems' => $cartItems,
            'total' => number_format($total, 2),
        ]);
    }

    /**
     * Add a product to the cart
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($validated['product_id']);

            // Check if enough stock is available
            if (!$product->isInStock($validated['quantity'])) {
                return redirect()->back()->with('error', 'Not enough stock available for ' . $product->name);
            }

            // Check if item already exists in cart
            $cartItem = CartItem::where('user_id', $request->user()->id)
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($cartItem) {
                // Update quantity
                $newQuantity = $cartItem->quantity + $validated['quantity'];
                
                if (!$product->isInStock($newQuantity)) {
                    return redirect()->back()->with('error', 'Not enough stock available. Maximum available: ' . $product->stock_quantity);
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                // Create new cart item
                CartItem::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                ]);
            }

            return redirect()->back()->with('success', 'Product added to cart!');
            
        } catch (\Exception $e) {
            Log::error('Cart store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add product to cart: ' . $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        try {
            // Ensure the cart item belongs to the authenticated user
            if ($cartItem->user_id !== $request->user()->id) {
                abort(403, 'Unauthorized');
            }

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
            ]);

            // Check stock availability
            if (!$cartItem->product->isInStock($validated['quantity'])) {
                return redirect()->back()->with('error', 'Not enough stock available. Maximum: ' . $cartItem->product->stock_quantity);
            }

            $cartItem->update(['quantity' => $validated['quantity']]);

            return redirect()->back()->with('success', 'Cart updated!');
            
        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update cart: ' . $e->getMessage());
        }
    }

    /**
     * Remove item from cart
     */
    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        try {
            // Ensure the cart item belongs to the authenticated user
            if ($cartItem->user_id !== $request->user()->id) {
                abort(403, 'Unauthorized');
            }

            $cartItem->delete();

            return redirect()->back()->with('success', 'Item removed from cart!');
            
        } catch (\Exception $e) {
            Log::error('Cart destroy error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to remove item: ' . $e->getMessage());
        }
    }
}