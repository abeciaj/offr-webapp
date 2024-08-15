@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($products as $product)
        <div class="col-sm-3 mb-3">
            <div class="card">
                @if(isset($product['imgSrc']))
                <img src="{{ $product['imgSrc'] }}" class="card-img-top" alt="{{ $product['name'] }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product['name'] }}</h5>
                    <p class="card-text">Price: ${{ $product['price'] }}</p>
                    <p class="card-text">Unit Price: {{ $product['unitPrice'] }}</p>
                    @if(isset($product['href']))
                    <a href="{{ $product['href'] }}" class="btn btn-primary">View Product</a>
                    @endif
                    <a href="{{ route('comparePrices', ['productName' => $product['name']]) }}" class="btn btn-primary">Compare Prices</a>

                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
