<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Discount;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Cart::with('product')->get();
        $products = Product::all();

        // Hitung subtotal
        $subtotal = $items->sum(fn($item) => $item->product->price * $item->quantity);

        // Ambil diskon yang valid
        $discount = session('applied_discount');
        $discountAmount = 0;

        if ($discount) {
            $type = $discount['type'];
            $value = $discount['value'];
            $productId = $discount['product_id'] ?? null;

            if ($type === 'percentage') {
                $discountAmount = $subtotal * ($value / 100);
            } elseif ($type === 'fixed') {
                $discountAmount = $value;
            } elseif ($type === 'product_specific' && $productId) {
                foreach ($items as $item) {
                    if ($item->product_id == $productId) {
                        $discountAmount = $item->product->price * $item->quantity * ($value / 100);
                        break;
                    }
                }
            }
        }

        $total = $subtotal - $discountAmount;

        return view('carts.index', compact('items', 'products', 'subtotal', 'discountAmount', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('product_id', $request->product_id)->first();

        if ($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
        } else {
            Cart::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('carts.index')->with('success', 'Item added to cart');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('carts.index')->with('success', 'Cart updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();

        return redirect()->route('carts.index')->with('success', 'Item removed');
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string',
        ]);

        $discount = Discount::where('code', $request->discount_code)->first();

        if (!$discount) {
            return back()->withErrors(['discount_code' => 'Invalid discount code.']);
        }

        // Ambil semua cart item (tanpa user)
        $items = Cart::with('product')->get();

        if ($items->isEmpty()) {
            return back()->withErrors(['discount_code' => 'Cart is empty.']);
        }

        // Hitung subtotal
        $subtotal = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $discountAmount = 0;

        $now = now();


        \Log::info([
            'now_day' => $now->format('l'),
            'now_time' => $now->format('H:i:s'),
            'discount_day' => $discount->day,
            'start_time' => $discount->start_time,
            'end_time' => $discount->end_time,
        ]);

        switch ($discount->type) {
            case 'percentage':
                $discountAmount = ($subtotal * $discount->value) / 100;
                break;

            case 'fixed':
                $discountAmount = $discount->value;
                break;

            case 'product_specific':
                $item = $items->firstWhere('product_id', $discount->product_id);
                if ($item) {
                    $discountAmount = $discount->value * $item->quantity;
                }
                break;

            case 'time_based':
                $now = now();
                if (
                    strtolower($now->format('l')) === strtolower($discount->day) &&
                    $now->format('H:i:s') >= $discount->start_time &&
                    $now->format('H:i:s') <= $discount->end_time
                ) {
                    $discountAmount = ($subtotal * $discount->value) / 100;
                } else {
                    return back()->withErrors(['discount_code' => 'This discount is not active now.']);
                }
                break;

            case 'price_threshold':
                foreach ($items as $item) {
                    if ($item->product->price > 400000) {
                        $discountAmount += ($item->product->price * $item->quantity) * ($discount->value / 100);
                    }
                }
                break;
        }

        $discountAmount = min($discountAmount, $subtotal);

        session([
            'applied_discount' => [
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value,
                'product_id' => $discount->product_id,
                'amount' => $discountAmount,
            ]
        ]);

        return back()->with('success', 'Discount applied.');
    }

    public function removeDiscount()
    {
        session()->forget('applied_discount');
        return back()->with('success', 'Discount removed.');
    }

}
