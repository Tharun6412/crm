{{-- User registration view --}}

@extends('layouts.layout-auth')

@section('title', 'Registration')

@section('page-content')
    <div class="d-flex justify-content-center m-5">
        <div class="card w-50">
            <div class="card-header">
                <h5 class="card-title">User Registration</h5>
            </div>
            <div class="card-body">
                <form action="{{ url('register') }}" method="post">
                    @csrf
                    <div class="row mb-3">
                        <label for="Name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="email" class="col-sm-2 col-control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="email" id="email" value="{{ old('email') }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="emp_id" class="col-sm-2 col-control-label">Emp Id</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="emp_id" id="emp_id" value="{{ old('emp_id') }}">
                            @error('emp_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="password" class="col-sm-2 col-control-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password" id="password">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="password_confirmation" class="col-sm-2 col-control-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-success">Register</button>
                            <a href="{{ url('login') }}" class="btn btn-link">Login</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection