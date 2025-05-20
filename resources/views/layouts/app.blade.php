<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Discount Management')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
    <!-- Header/Navbar -->
    <header class="bg-white shadow mb-6">
        <div class="max-w-5xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">
                🛒 Shopping Cart
            </h1>
            <nav class="space-x-4">
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-500">Products</a>
                <a href="{{ route('discounts.index') }}" class="text-gray-700 hover:text-blue-500">Discounts</a>
                <a href="{{ route('carts.index') }}" class="text-gray-700 hover:text-blue-500">Cart</a>
            </nav>
        </div>
    </header>

    <!-- Page Content -->
    <div class="max-w-5xl mx-auto px-4">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @yield('content')
    </div>
</body>

</html>