@extends('layouts/layout-auth')

@push('styles')
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
@endpush

@section('page-content')
    {{-- @dd(request()->attributes->get('user_module_actions')) --}}
    <!-- Section: Design Block -->
    <section class="background-radial-gradient overflow-hidden">

        <div class="container px-md-5 text-center text-lg-start">
            <div class="row gx-lg-5 align-items-center vh-100">
                <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                    <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
                        MeghaGas<sup>&reg;</sup><br>
                        <span class="text-primary">Welcome to Portal!</span>
                    </h1>
                    <div class="fs-sm text-secondary">&copy;{{ date('Y') }}&nbsp;Megha City Gas Distribution Private Limited.</div>
                </div>
                <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>
                    {{-- Login card --}}
                    <div class="card bg-glass">
                        <div class="card-body px-4 py-5 px-md-5">
                            <div class="img-responsive w-75 mb-3">
                                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="img-fluid">
                            </div>
                            <h1 class="fs-3 fw-semibold"><i class="bi bi-shield-lock"></i>&nbsp;Log In</h1>
                            <form accept="{{ url('login') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="emp_id" class="col-sm-4 col-form-label"><i class="bi bi-person"></i>&nbsp;Employee ID</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="emp_id" id="emp_id" class="form-control" value="{{ old('emp_id') }}">
                                        @error('emp_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="password" class="col-sm-4 col-form-label"><i class="bi bi-key"></i>&nbsp;Password</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="password" id="password" class="form-control">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button class="btn btn-primary">
                                            <i class="bi bi-box-arrow-in-right"></i>&nbsp;Login
                                        </button>
                                        <a href="#" class="btn btn-link">Forgot Password?</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- Section: Design Block -->

@endsection