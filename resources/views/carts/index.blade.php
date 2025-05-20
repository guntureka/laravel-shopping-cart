@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<h1 class="text-xl font-bold mb-4">Shopping Cart</h1>

{{-- Add item --}}
<form action="{{ route('carts.store') }}" method="POST" class="mb-6">
    @csrf
    <div class="flex items-center gap-4">
        <select name="product_id" class="border px-3 py-2 rounded">
            @foreach ($products as $product)
            <option value="{{ $product->id }}">{{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.')
                }}</option>
            @endforeach
        </select>
        <input type="number" name="quantity" value="1" class="border px-3 py-2 rounded w-20">
        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add</button>
    </div>
</form>

{{-- Cart table --}}
<table class="w-full border border-collapse">
    <thead class="bg-gray-200">
        <tr>
            <th class="p-2 text-left">Product</th>
            <th class="p-2 text-left">Qty</th>
            <th class="p-2 text-left">Price</th>
            <th class="p-2 text-left">Total</th>
            <th class="p-2"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
        <tr class="border-t">
            <td class="p-2 flex items-center">
                @if ($item->product->image)
                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                    class="w-12 h-12 object-cover mr-3 rounded">
                @endif
                <div class="flex flex-col gap-2">
                    <span class="text-lg font-bold">{{ $item->product->name }}</span>
                    <span class="text-sm">{{ $item->product->code }}</span>
                </div>
            </td>
            <td class="p-2">
                <div class="flex items-center">
                    {{-- Kurangi quantity --}}
                    <form action="{{ route('carts.update', $item) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PUT')
                        <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}"
                            class="text-xl bg-gray-200 hover:bg-gray-300 px-2 rounded text-center" @if($item->quantity
                            <= 1) disabled @endif>
                                -
                        </button>
                    </form>

                    <input type="number" value="{{ $item->quantity }}" disabled
                        class="border px-2 py-1 w-12 text-center mx-2 bg-gray-100">

                    {{-- Tambah quantity --}}
                    <form action="{{ route('carts.update', $item) }}" method="POST" class="inline-block">
                        @csrf
                        @method('PUT')
                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                            class="text-xl bg-gray-200 hover:bg-gray-300 px-2 rounded text-center">
                            +
                        </button>
                    </form>
                </div>
            </td>
            <td class="p-2">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
            <td class="p-2">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
            <td class="p-2">
                <form action="{{ route('carts.destroy', $item) }}" method="POST" class="inline ml-2"
                    onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- Subtotal --}}
<div class="mt-4 text-right font-bold text-lg">
    Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}
</div>

{{-- Apply Discount Button --}}
<div class="mt-4 text-right">
    <button onclick="document.getElementById('discountModal').classList.remove('hidden')"
        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
        Apply Discount
    </button>
</div>

{{-- Discount Modal --}}
<div id="discountModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md relative">
        <h2 class="text-xl font-bold mb-4">Apply Discount Code</h2>
        <form action="{{ route('carts.applyDiscount') }}" method="POST">
            @csrf
            <input type="text" name="discount_code" placeholder="Enter discount code"
                class="border px-3 py-2 rounded w-full mb-3 @error('discount_code') border-red-500 @enderror"
                value="{{ session('applied_discount.code') }}">

            @error('discount_code')
            <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
            @enderror

            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('discountModal').classList.add('hidden')"
                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                    Apply
                </button>
            </div>
        </form>

        {{-- Close button (X) --}}
        <button onclick="document.getElementById('discountModal').classList.add('hidden')"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
            &times;
        </button>
    </div>
</div>

@error('discount_code')
<p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
@enderror

{{-- Show Discount --}}
@if(session('applied_discount'))
<div class="mt-2 text-right text-green-700 font-medium">
    Discount ({{ session('applied_discount.code') }}): -Rp {{ number_format(session('applied_discount.amount'), 0, ',',
    '.') }}

    <form action="{{ route('carts.removeDiscount') }}" method="POST" class="inline ml-2">
        @csrf
        <button class="text-red-600 text-sm hover:underline" type="submit">Remove</button>
    </form>
</div>
<div class="text-right font-bold text-xl">
    Total: Rp {{ number_format($subtotal - session('applied_discount.amount'), 0, ',', '.') }}
</div>
@endif
@endsection