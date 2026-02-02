{{-- User password Reset view --}}
@extends('layouts.layout-auth')

@section('title', 'Re-Generate Password')

@section('page-content')
    <h1 class="fs-3 fw-semibold"><i class="bi bi-shield-lock"></i>&nbsp;Generate Password</h1>
    <form action="{{ url('updatePassword/' . session()->get('forgot_user')) }}" method="POST">
        @csrf
        <div class="row mb-3">
            <label for="password" class="col-sm-4 col-form-label text-end">Password</label>
            <div class="col-sm-8">
                <input type="password" name="password" id="password" class="form-control">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <label for="password_confirmation" class="col-sm-4 col-form-label text-end">Re-Type Password</label>
            <div class="col-sm-8">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
            @error('password_confirmation')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="row mb-3">
            <div class="offset-sm-4 col-sm-8">
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i>&nbsp;Save Password</button>
                <a href="{{ url('cancelReset') }}" class="btn btn-link" id="register-cancel">Cancel</a>
            </div>
        </div>
        <div class="text-center form-text">
            Your password must be minimum of 8 characters long, contain letters and numbers, and atleast one uppercase letter.
        </div>
    </form>
@endsection