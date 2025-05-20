@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block mb-1 font-medium">Code</label>
        <input type="text" name="code" value="{{ old('code', $product->code ?? '') }}"
            class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block mb-1 font-medium">Name</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
            class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block mb-1 font-medium">Price</label>
        <input type="number" name="price" step="0.01" value="{{ old('price', $product->price ?? '') }}"
            class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block mb-1 font-medium">Image</label>
        <input type="file" name="image" class="w-full border rounded px-3 py-2">
        @if(isset($product) && $product->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $product->image) }}" class="w-20 h-20 object-cover" alt="Product Image">
        </div>
        @endif
    </div>
</div>

<div class="mt-6">
    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
    <a href="{{ route('products.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
</div>