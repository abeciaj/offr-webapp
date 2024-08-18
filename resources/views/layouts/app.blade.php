<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('images/logo2.png') }}" type="image/png">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Offr App') }}</title>

    <style>
    .pagination .page-item .page-link {
        color: #6c757d; /* Gray color for text */
        background-color: #f8f9fa; /* Light gray background */
        border: 1px solid #dee2e6; /* Gray border */
    }

    .pagination .page-item.active .page-link {
        color: #fff; /* White text for active page */
        background-color: #6c757d; /* Gray background for active page */
        border: 1px solid #6c757d; /* Matching border color for active page */
    }

    .pagination .page-item .page-link:hover {
        color: #495057; /* Darker gray on hover */
        background-color: #e9ecef; /* Light gray background on hover */
        border-color: #dee2e6; /* Matching border color on hover */
    }
    </style>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body  style="min-height:90vh;">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/home') }}">
                    <!-- {{ config('app.name', 'Offr App') }} -->
                    <img src="{{ asset('images/logo2.png') }}" alt="logo" class="navbar-logo" style="width: 100px; height: auto;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <!-- Left Side Of Navbar -->
    <ul class="navbar-nav mr-auto">
        <!-- Optional: Add links or content on the left side of the navbar -->
    </ul>

    <!-- Right Side Of Navbar -->
    <ul class="navbar-nav ml-auto">
        
        <!-- Authentication Links -->
        @guest
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-primary mx-2" href="{{ route('login') }}">
                        {{ __('Login') }}
                    </a>
                </li>
            @endif
            @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-secondary mx-2" href="{{ route('register') }}">
                        {{ __('Register') }}
                    </a>
                </li>
            @endif
        @else
         <!-- Shopping List Link as Button -->
<li class="nav-item">
    <a class="nav-link btn btn-outline-success text-dark mx-2" href="{{ route('shoppingList.show') }}">
        <i class="fas fa-shopping-cart"></i> <!-- Shopping Cart Icon -->
        {{ __('View Shopping List') }}
    </a>
</li>

<li class="nav-item">
    <a class="nav-link btn btn-outline-info mx-2" href="home/profile">
        <i class="fas fa-user"></i> <!-- User Profile Icon -->
        {{ __('Profile') }}
    </a>
</li>

<li class="nav-item">
    <a class="nav-link btn btn-outline-danger mx-2" href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i> <!-- Logout Icon -->
        {{ __('Logout') }}
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</li>
        @endguest
    </ul>
</div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

   <!-- Footer -->
<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container text-center text-md-left">
        <div class="row text-center text-md-left">

            <!-- About Us Section -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">About Us</h5>
                <p>
                We are a team of Master's students from Torrens University, specializing in Software Development Management. Offr is our project designed to help shoppers compare prices across multiple stores, ensuring they find the best deals and make informed purchasing decisions.
                </p>
            </div>

            <!-- Get in Touch Section -->
            <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Get in Touch</h5>
                <p>
                    <i class="fas fa-map-marker-alt mr-3"></i> 295 Flinders street Torrens University.
                </p>
                <p>
                    <i class="fas fa-envelope mr-3"></i> support@offrapp.com
                </p>
                <p>
                    <i class="fas fa-phone mr-3"></i> +180 111 225 678
                </p>
            </div>

            <!-- Pages Section -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Pages</h5><p>
                    <a href="{{ url('/home') }}" class="text-white" style="text-decoration: none;">Home</a>
                </p>
              

               
                <p>
                    <a  href="{{ route('shoppingList.show') }}" class="text-white" style="text-decoration: none;">Shopping List</a>
                </p>
                <p>
                    <a href="home/profile" class="text-white" style="text-decoration: none;">Profile</a>

                </p>
                
            </div>

            <!-- Subscription Section -->
            <div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
                <h5 class="text-uppercase mb-4 font-weight-bold text-warning">Subscribe</h5>
                <p>Subscribe to our mailing list to get the latest updates.</p>
                <form class="form-inline">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Enter your email" aria-label="Subscribe" aria-describedby="subscribe-btn">
                        <div class="input-group-append">
                            <button class="btn btn-outline-warning" type="button" id="subscribe-btn">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="row d-flex justify-content-center mt-4">
            <div class="col-md-7 col-lg-8">
                <p class="text-center text-white">© 2024 All Rights Reserved by
                    <a href="#" class="text-warning" style="text-decoration: none;">
                        <strong>Sam Areeba Aayshma Jaylln</strong>
                    </a>
                </p>
            </div>
        </div>
    </div>
</footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
