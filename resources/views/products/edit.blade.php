@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Product</h1>

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
    <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data"
    class="bg-white p-6 shadow rounded">
    @method('PUT')
    @include('products.form')
</form>
@endsection