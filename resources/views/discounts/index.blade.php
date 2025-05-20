@extends('layouts.app')

@section('title', 'Discount List')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Discount List</h1>
    <a href="{{ route('discounts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Add
        Discount</a>
</div>

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200 text-left">
            <th class="px-4 py-2">Code</th>
            <th class="px-4 py-2">Type</th>
            <th class="px-4 py-2">Value</th>
            <th class="px-4 py-2">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($discounts as $discount)
        <tr class="border-t">
            <td class="px-4 py-2">{{ $discount->code }}</td>
            <td class="px-4 py-2">{{ $discount->type }}</td>
            <td class="px-4 py-2">{{ $discount->value }}</td>
            <td class="px-4 py-2">
                <a href="{{ route('discounts.edit', $discount) }}" class="text-blue-600 hover:underline">Edit</a>
                <form action="{{ route('discounts.destroy', $discount) }}" method="POST" class="inline ml-2"
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
@endsection