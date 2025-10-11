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
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-icons/bootstrap-icons.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
        {{-- Dynamic CSS files / styles --}}
        @stack('styles')
        <script type="text/javascript">var WEBROOT = "{{ url('/') }}";</script>
    </head>
    <body>
        <div>
            @yield('page-content')
        </div>
        <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('bootstrap-538/js/bootstrap.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
        {{-- Dynamic loading JS script file --}}
        @stack('scripts')
    </body>
</html>