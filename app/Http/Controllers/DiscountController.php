<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Models\Product;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = Discount::all();
        return view('discounts.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('discounts.create', [
            'products' => $products,
            'discount' => null // untuk hindari error optional($discount)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiscountRequest $request)
    {
        $request->validate([
            'code' => 'required|unique:discounts',
            'type' => 'required',
            'value' => 'nullable|numeric',
            'product_id' => 'nullable|string',
            'min_price' => 'nullable|numeric',
            'day' => 'nullable|string',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        Discount::create($request->all());

        return redirect()->route('discounts.index')->with('success', 'Discount created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Discount $discount)
    {
        $products = Product::all();
        return view('discounts.edit', compact('discount', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        $request->validate([
            'code' => 'required|unique:discounts,code,' . $discount->id,
            'type' => 'required',
            'value' => 'nullable|numeric',
            'product_id' => 'nullable|string',
            'min_price' => 'nullable|numeric',
            'day' => 'nullable|string',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        $discount->update($request->all());

        return redirect()->route('discounts.index')->with('success', 'Discount updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('discounts.index')->with('success', 'Discount deleted.');
    }
}
