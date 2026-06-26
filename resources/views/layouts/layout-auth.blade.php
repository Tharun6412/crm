<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Megha Gas&reg; Pulse @yield('title')</title>
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }} " type="image/x-icon">
        <!-- CSS files -->
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-538/css/bootstrap.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap-icons/bootstrap-icons.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
        <style>
            .background-radial-gradient {
                /* height: 100vh; */
                background-color: hsl(218, 41%, 15%);
                background-image: radial-gradient(650px circle at 0% 0%,
                hsl(218, 41%, 35%) 15%,
                hsl(218, 41%, 30%) 35%,
                hsl(218, 41%, 20%) 75%,
                hsl(218, 41%, 19%) 80%,
                transparent 100%),
                radial-gradient(1250px circle at 100% 100%,
                hsl(218, 41%, 45%) 15%,
                hsl(218, 41%, 30%) 35%,
                hsl(218, 41%, 20%) 75%,
                hsl(218, 41%, 19%) 80%,
                transparent 100%);
            }
            #radius-shape-1 {
                height: 220px;
                width: 220px;
                top: -60px;
                left: -130px;
                background: radial-gradient(#44006b, #ad1fff);
                overflow: hidden;
            }
            #radius-shape-2 {
                border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
                bottom: -60px;
                right: -110px;
                width: 300px;
                height: 300px;
                background: radial-gradient(#44006b, #ad1fff);
                overflow: hidden;
            }
            .bg-glass {
                background-color: hsla(0, 0%, 100%, 0.9) !important;
                backdrop-filter: saturate(200%) blur(25px);
            }
        </style>
        {{-- Dynamic CSS files / styles --}}
        @stack('styles')
        <script type="text/javascript">var WEBROOT = "{{ url('/') }}";</script>
        {{-- @include('partials.google-analytics') --}}
    </head>
    <body>
        <!-- Section: Design Block -->
    <section class="background-radial-gradient overflow-hidden">

        <div class="container px-md-5 text-center text-lg-start">
            <div class="row gx-lg-5 align-items-center vh-100">
                <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                    <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
                        MeghaGas<sup>&reg;</sup><br>
                        <span class="text-primary">Welcome to Pulse.!</span>
                    </h1>
                    <div class="fs-sm text-secondary">&copy;{{ date('Y') }}&nbsp;Megha City Gas Distribution Private Limited.</div>
                </div>
                <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>
                    {{-- Card --}}
                    <div class="card bg-glass">
                        <div class="card-body px-4 py-5 px-md-5">
                                <div class="img-responsive w-50 mb-3">
                                    <img src="{{ asset('img/meghagas_pulse.png') }}" alt="Logo" class="img-fluid">
                                </div>
                            @yield('page-content')
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- Section: Design Block -->

        <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('bootstrap-538/js/bootstrap.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
        {{-- Dynamic loading JS script file --}}
        @stack('scripts')
    </body>
</html>