@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Compare Prices for {{ $product['name'] }}</h1>
    <div class="row">
        @foreach($similarProducts as $similarProduct)
            <div class="col-sm-3 mb-3">
                <div class="card">
                    <img src="{{ $similarProduct['imgSrc'] ?? 'default-image.jpg' }}" 
                         class="card-img-top" 
                         alt="{{ $similarProduct['name'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $similarProduct['name'] }}</h5>
                        <p class="card-text">Price: ${{ $similarProduct['price'] }}</p>
                        <p class="card-text">Store: {{ $similarProduct['store'] }}</p>

                        <!-- Add to Shopping List Form -->
                         <!-- Add to Shopping List Form -->
                <form action="{{ route('shopping-list.add', ['productName' => $similarProduct['name']]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Add to Shopping List</button>
                </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection