<!--

=========================================================
* Neumorphism UI - v1.0.0
=========================================================

* Product Page: https://themesberg.com/product/ui-kits/neumorphism-ui
* Copyright 2020 Themesberg MIT LICENSE (https://www.themesberg.com/licensing#mit)

* Coded by https://themesberg.com

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="canonical" href="https://themesberg.com/product/ui-kits/neumorphism-ui/" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    <!-- <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet"> -->
    <link href="{{ secure_asset('css/main.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ secure_asset('js/app.js') }}" defer async></script>
    <script src="{{ secure_asset('js/script.js') }}" defer async></script>

    <script async defer src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ config('googlemaps.key', null) }}&libraries=places&callback=run"></script>
    <!-- <script src="{{ secure_asset('js/script.js') }}" defer></script> -->
    <!-- Favicon -->
<link rel="apple-touch-icon" sizes="128x128" href="{{ secure_asset('images/icons/icon-128x128.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('images/icons/icon-72x72.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('images/icons/icon-72x72.png') }}">
<link rel="manifest" href="{{ secure_asset('img/favicon/site.webmanifest') }}">
<link rel="mask-icon" href="{{ secure_asset('img/favicon/safari-pinned-tab.svg') }}"  color="#ffffff">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">

<!-- Fontawesome -->
<link type="text/css" href="{{ secure_asset('css/all.min.css') }}" rel="stylesheet">

<!-- Pixel CSS -->
<link type="text/css" href="{{ secure_asset('css/neumorphism.css') }}" rel="stylesheet">
@laravelPWA
</head>

<body>
<header class="header-global">
    <nav id="navbar-main" aria-label="Primary navigation" class="navbar navbar-main navbar-expand-lg navbar-theme-primary headroom navbar-dark navbar-transparent navbar-theme-primary">
        <div class="container position-relative">
            <a class="navbar-brand shadow-soft py-2 px-3 rounded border border-light mr-lg-4" href="{{ secure_asset('/') }}">
                <img class="navbar-brand-dark" src="{{ secure_asset('images/icons/icon-72x72.png') }}" alt="Logo light">
                <img class="navbar-brand-light" src="{{ secure_asset('images/icons/icon-72x72.png') }}" alt="Logo dark">
                <span class="ml-2 text-dark">KatMana</span>
            </a>            
            <div class="d-flex align-items-center">
                <div class="navbar-collapse collapse" id="navbar_global">
                    <div class="navbar-collapse-header">
                        <div class="row">
                            <div class="col-6 collapse-brand">
                                <a href="{{ url('/') }}" class="navbar-brand shadow-soft py-2 px-3 rounded border border-light">
                                    <img src="{{ secure_asset('images/icons/icon-72x72.png') }}" alt="Themesberg logo">
                                </a>
                            </div>
                            <div class="col-6 collapse-close">
                                <a href="#navbar_global" class="fas fa-times" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" title="close" aria-label="Toggle navigation"></a>
                            </div>
                        </div>
                    </div>
                    <div class="nav-wrapper position-relative">
                        <ul class="nav nav-pills nav-fill flex-column flex-sm-row">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0 " href="{{ url('/') }}">{{ __('Nearby') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0" href="{{ route('planner') }}">{{ __('Planner') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0" href="{{ route('login') }}">{{ __('Admin') }}</a>
                                </li>
                                <!-- @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif -->
                            @else
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0" href="{{ route('station.index') }}">{{ __('Station') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
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
                <button class="navbar-toggler ml-2" type="button" data-toggle="collapse" data-target="#navbar_global" aria-controls="navbar_global" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </nav>
</header>
        
    <section class="section section bg-soft pb-5 overflow-hidden z-2">
     
</section>
    
            @yield('content')
     

</body>

</html>