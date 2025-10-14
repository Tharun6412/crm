{{-- Login view --}}

@extends('layouts.layout_guest_user')

@section('title', 'Login')

@section('page-content')
    <div>
        <h2 class="mb-0">Welcome To Megha Gas!</h2>
        <span>Please sign-in to your account.</span>
        <form action="{{ url('login') }}" method="post" class="mt-3">
            @csrf
            <div class="mb-3">
                <label for="emp_id" class="form-label">Employee-Id</label>
                <input type="text" class="form-control" name="emp_id" id="emp_id" value="{{ old('emp_id') }}">
                @error('emp_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <label for="password" class="form-label">Password</label>
                    <a href="{{ url('forgotPassword') }}">Forgot your password?</a>
                </div>
                <input type="password" class="form-control" name="password" id="password">
                @error('password')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-success"><i class="bi bi-box-arrow-in-left"></i>&nbsp;Login</button>
            </div>
            <div class="text-center">
                <a href="{{ url('register') }}" class="btn btn-link">Register your account</a>
            </div>
        </form>
    </div>
@endsection