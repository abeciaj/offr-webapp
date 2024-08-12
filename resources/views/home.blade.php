@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($products as $product)
        <div class="col-sm-3 mb-3">
            <div class="card">
                @if(isset($product['imgSrc']))
                <img src="{{ $product['imgSrc'] }}" class="card-img-top" alt="{{ $product['name'] ?? 'Product Image' }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product['name'] ?? 'No Name Available' }}</h5>
                    <p class="card-text">Price: ${{ $product['price'] ?? 'N/A' }}</p>
                    <p class="card-text">Unit Price: {{ $product['unitPrice'] ?? 'N/A' }}</p>
                    @if(isset($product['href']))
                    <a href="{{ $product['href'] }}" class="btn btn-primary">View Product</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Ensure the pagination links are called only once -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection