@extends('layouts.app')

@section('content')
<div class="container">

    <<a href="{{ route('shoppingList.show') }}">View Shopping List</a>
    <!-- Search Form -->
    <div class="row">
        <div class="col-12">
            <form action="{{ route('home') }}" method="GET">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" placeholder="Search products" value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($products as $product)
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
                    <form action="{{ route('addToShoppingList', ['productName' => $product['name']]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Add to Shopping List</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="text-center">No products found.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="d-flex justify-content-center">
        {{ $products->appends(request()->input())->links() }}
    </div>
</div>
@endsection
