@extends('layouts.app')

@section('content')
<div class="container">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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
</div>
@endsection
