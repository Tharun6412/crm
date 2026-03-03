<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Megha Gas&reg; @yield('title')</title>
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }} " type="image/x-icon">
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-538/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/datepicker.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-icons/bootstrap-icons.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/theme.css') }}" id="app-style">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/navbar.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
        {{-- Dynamic CSS files / styles --}}
        @stack('styles')
        <script type="text/javascript">var WEBROOT = "{{ url('/') }}";</script>
    </head>
    <body>
        <div class="wrapper position-relative">
            {{-- Header Component Class --}}
            <x-layouts.header/>
            {{-- Navigation Component class --}}
            <x-layouts.navigation/>
            <div class="content-page ps-0">
                @isset($dashboard)
                    {{-- No Title bar --}}
                @else
                    <div class="d-flex justify-content-between p-3 bg-secondary-subtle">
                        <h3 class="mb-0 fs-5 fw-bold">@yield('page-title')</h3>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                @yield('breadcrumb')
                                <li class="breadcrumb-item active" aria-current="page">@yield('page-title')</li>
                            </ol>
                        </nav>
                    </div>
                @endisset
                <div class="content container-fluid p-3 pe-0 bg-light text-dark rounded-6 shadow-sm">
                    @yield('page-content')
                </div>
                {{-- Footer Component --}}
                <x-layouts.footer/>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('bootstrap-538/js/bootstrap.bundle.min.js') }}"></script>
        {{-- Side nav bar --}}
        <script type="text/javascript" src="{{ asset('js/navbar.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
        {{-- Dynamic loading JS script file --}}
        @stack('scripts')
    </body>
</html>