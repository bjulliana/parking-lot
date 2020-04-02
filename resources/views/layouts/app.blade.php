<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Parking Garage') }}</title>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">

</head>
<body>
<div id="app">
    <div class="menu-top">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="d-flex flex-grow-1">
                <span class="w-100 d-lg-none d-block"><!-- hidden spacer to center brand on mobile --></span>
                <a class="navbar-brand" href="/">{{ __('Parking Garage') }}</a>
                <div class="w-100 text-right">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNavbar7">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
            <div class="collapse navbar-collapse flex-grow-1 text-right" id="myNavbar7">
                <ul class="navbar-nav ml-auto flex-nowrap">
                    <li class="nav-item">
                        <a href="{{ route('tickets.new') }}" class="nav-link">{{ __('Issue New Ticket') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('tickets.all') }}" class="nav-link">{{ __('Manage Tickets') }}</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<main class="main-content">
    @yield('content')
</main>
</body>
</html>
