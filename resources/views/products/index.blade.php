@extends('layouts.app')

@section('title', 'Product List')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Product List</h1>
    <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Add
        Product</a>
</div>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200 text-left">
            <th class="px-4 py-2">Image</th>
            <th class="px-4 py-2">Code</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Price</th>
            <th class="px-4 py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr class="border-t">
            <td class="px-4 py-2">
                @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-16 h-16 object-cover">
                @else
                <span class="text-gray-500">No image</span>
                @endif
            </td>
            <td class="px-4 py-2">{{ $product->code }}</td>
            <td class="px-4 py-2">{{ $product->name }}</td>
            <td class="px-4 py-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
            <td class="px-4 py-2">
                <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline ml-2"
                    onsubmit="return confirm('Delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection