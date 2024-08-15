@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Shopping List</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(count($shoppingList) > 0)
        <div class="row">
            @foreach($shoppingList as $productName => $product)
                <div class="col-sm-3 mb-3">
                    <div class="card">
                        <img src="{{ $product['imgSrc'] ?? 'default-image.jpg' }}" class="card-img-top" alt="{{ $productName }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $productName }}</h5>
                            <p class="card-text">Price: ${{ $product['price'] }}</p>
                            <p class="card-text">Store: {{ $product['store'] ?? 'N/A' }}</p>

                            <!-- Remove from Shopping List Button -->
                            <form action="{{ route('shopping-list.remove', ['productName' => $productName]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Remove from Shopping List</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Your shopping list is empty.</p>
    @endif
</div>
@endsection