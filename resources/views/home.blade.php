@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Video Header -->
    <div class="p-3 mb-4 text-center">
        <video autoplay loop muted style="max-width: 100%; height: auto;">
            <source src="{{ asset('video/offr-vid.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

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

    <style>
        .card-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 3em;
        }

        .card {
            min-height: 450px; /* Adjust this based on your card content */
        }

        .card img {
            max-height: 200px; /* Adjust this to control image height */
            object-fit: cover;
            padding: 10px; /* Add padding around the image */
            border-radius: 8px; /* Optional: Add rounded corners to the image */
        }
    </style>

    <!-- Success Alert Placeholder -->
    <div id="alert-container"></div>

    <!-- Product Cards -->
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
                    <p class="card-text">Provider: {{ ucfirst($product['provider']) }}</p>
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

    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
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
                        const alertContainer = document.getElementById('alert-container');
                        const alertMessage = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        alertContainer.innerHTML = alertMessage;

                        // Optionally, scroll to the alert
                        alertContainer.scrollIntoView({ behavior: 'smooth' });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const alertContainer = document.getElementById('alert-container');
                        const alertMessage = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                An error occurred. Please try again.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        alertContainer.innerHTML = alertMessage;
                    });
                });
            });
        });
    </script>

</div>
@endsection