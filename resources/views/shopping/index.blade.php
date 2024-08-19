<!-- resources/views/shopping/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shopping List</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if ($shoppingList)
        <ul class="list-group">
            @foreach ($shoppingList as $productName => $product)
                <li class="list-group-item">
                    {{ $product['name'] }} - ${{ $product['price'] }}
                    <form action="{{ route('shopping-list.remove', ['productName' => $productName]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm float-right">Remove</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @else
        <p>Your shopping list is empty.</p>
    @endif
</div>
@endsection