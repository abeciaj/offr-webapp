@extends('layouts.app')

@section('content')
<div class="container">

    <a href="{{ route('shoppingList.show') }}">View Shopping List</a>

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
                <div class="form-group">
                    <label for="sort_by_price">Sort by Price:</label>
                    <select name="sort_by_price" id="sort_by_price" class="form-control">
                        <option value="" {{ request('sort_by_price') == '' ? 'selected' : '' }}>Default</option>
                        <option value="asc" {{ request('sort_by_price') == 'asc' ? 'selected' : '' }}>Low to High</option>
                        <option value="desc" {{ request('sort_by_price') == 'desc' ? 'selected' : '' }}>High to Low</option>
                    </select>
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
                    <p class="card-text">Provider: {{ ucfirst($product['provider']) }}</p> <!-- Display provider -->
                    @if(isset($product['href']))
                    <a href="{{ $product['href'] }}" class="btn btn-primary">View Product</a>
                    @endif
                    <form class="add-to-shopping-list" method="POST">
                        @csrf
                        <input type="hidden" name="product[name]" value="{{ $product['name'] }}">
                        <input type="hidden" name="product[price]" value="{{ $product['price'] }}">
                        <input type="hidden" name="product[imgSrc]" value="{{ $product['imgSrc'] }}">
                        <input type="hidden" name="product[unitPrice]" value="{{ $product['unitPrice'] }}">
                        <input type="hidden" name="product[provider]" value="{{ $product['provider'] }}">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.add-to-shopping-list');

        forms.forEach(function (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route("addToShoppingList") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });
    });
</script>

@endsection
