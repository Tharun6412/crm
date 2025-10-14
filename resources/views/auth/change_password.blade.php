{{-- Change passowrd --}}

@extends('layouts.layout')

@section('title', 'Change Password')

@section('page-title', 'Change Password')

@section('page-content')
    <div class="w-50">
        <form action="{{ url('changePassword') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <label for="current_password" class="col-sm-3">Current password</label>
                <div class="col-sm-9">
                    <input type="password" name="current_password" id="current_password" class="form-control">
                    @error('current_password')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-sm-3">New password</label>
                <div class="col-sm-9">
                    <input type="password" name="password" id="password" class="form-control">
                    @error('password')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="password_confirmation" class="col-sm-3">Confirm password</label>
                <div class="col-sm-9">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
            </div>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-9">
                    <button type="submit" class="btn btn-success"><i class="bi bi-shield-check"></i>&nbsp;Change password</button>
                    <a href="{{ url('profile') }}" class="btn btn-link">Back</a>
                </div>
            </div>
        </form>
    </div>
@endsection