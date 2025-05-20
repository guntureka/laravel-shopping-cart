@csrf

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block mb-1 font-medium">Code</label>
        <input type="text" name="code" value="{{ old('code', $discount->code ?? '') }}"
            class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block mb-1 font-medium">Type</label>
        <select name="type" class="w-full border rounded px-3 py-2">
            @foreach(['percentage', 'fixed', 'product_specific', 'time_based', 'price_threshold'] as $type)
            <option value="{{ $type }}" @selected(old('type', $discount->type ?? '') == $type)>{{ ucfirst($type) }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1 font-medium">Value</label>
        <input type="number" step="0.01" name="value" value="{{ old('value', $discount->value ?? '') }}"
            class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block mb-1 font-medium">Product (if applicable)</label>
        <select name="product_id" class="w-full border rounded px-3 py-2">
            <option value="">-- Select Product --</option>
            @foreach($products as $product)
            <option value="{{ $product->id }}" {{ (old('product_id', optional($discount)->product_id) == $product->id) ?
                'selected'
                : '' }}>
                {{ $product->code }} - {{ $product->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1 font-medium">Min Price</label>
        <input type="number" name="min_price" value="{{ old('min_price', $discount->min_price ?? '') }}"
            class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block mb-1 font-medium">Day</label>
        <select name="day" class="w-full border rounded px-3 py-2">
            <option value="">-- Select Day --</option>
            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
            <option value="{{ $day }}" @selected(old('day', $discount->day ?? '') == $day)>
                {{ $day }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block mb-1 font-medium">Start Time</label>
        <input type="time" name="start_time" value="{{ old('start_time', $discount->start_time ?? '') }}"
            class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block mb-1 font-medium">End Time</label>
        <input type="time" name="end_time" value="{{ old('end_time', $discount->end_time ?? '') }}"
            class="w-full border rounded px-3 py-2">
    </div>
</div>

<div class="mt-6">
    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
    <a href="{{ route('discounts.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
</div>