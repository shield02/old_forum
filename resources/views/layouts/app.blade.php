<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Forum') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check()
        ]) !!};
    </script>

    <!-- Styles -->
    <style>
        body { padding-bottom: 100px; }
        .level { display: flex; align-items: center; }
        .flex { flex: 1; }
        .card { margin-bottom: 20px; }
        .mr-1 { margin-right: 1em; }
        .ml-a { margin-left: auto; }
        [v-cloak] { display: none; }
    </style>

    @yield('header')

</head>
<body>
    <div id="app">
        @include('layouts.nav')
        
        <main class="py-4">
            @yield('content')

            <flash-component message="{{ session('flash') }}"></flash-component>

        </main>
    </div>

    <!-- Scripts -->
    @yield('scripts')
    
</body>
</html>
