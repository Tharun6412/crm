{{-- Login page --}}
@extends('layouts/layout-auth')

@section('page-content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <h1 class="fs-3 fw-semibold"><i class="bi bi-shield-lock"></i>&nbsp;Log In</h1>
    <form action="{{ url('login') }}" method="POST" enctype="multipart/form-data">
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
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="row">
            <div class="offset-sm-4 col-sm-8">
                <button class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right"></i>&nbsp;Login
                </button>
                <a href="{{ url('forgotPassword') }}" class="btn btn-link">Forgot Password?</a>
            </div>
        </div>
        <div class="mt-2 form-text text-center">
            Don't have account? <a href="{{ url('register') }}">Register</a>
        </div>
    </form>
@endsection