@extends('layouts.app')

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <div class="bg-light p-3 mb-4">
        <h3 class="text-center">
            <i class="fas fa-shopping-cart fa-2x" style="color: black;" ></i> Shopping List
        </h3>
    </div>

    <div class="row">
        @forelse($products as $product)
            @if(is_array($product) && !empty($product['name']))
                <div class="col-sm-3 mb-3">
                    <div class="card">
                        @if(isset($product['imgSrc']))
                            <img src="{{ $product['imgSrc'] }}" class="card-img-top" alt="{{ $product['name'] ?? 'No Name' }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product['name'] ?? 'No Name' }}</h5>
                            <p class="card-text">Price: ${{ $product['price'] ?? 'N/A' }}</p>
                            <p class="card-text">Unit Price: {{ $product['unitPrice'] ?? 'N/A' }}</p>
                            <p class="card-text">Provider: {{ ucfirst($product['provider'] ?? 'Unknown') }}</p>
                            <form action="{{ route('removeFromShoppingList', ['productName' => $product['name']]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-12">
                <p class="text-center">Your shopping list is empty.</p>
            </div>
        @endforelse
    </div>

       <!-- Pagination -->
       <div class="row">
        <div class="col-12">
            {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
