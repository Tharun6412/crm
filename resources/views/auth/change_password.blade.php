{{-- Change passowrd --}}

@extends('layouts.layout')

@section('title', 'Change Password')

@section('page-title', 'Change Password')

@section('page-content')
    <div class="bg-white p-4 border">
        <form action="{{ url('changePassword') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <label for="current_password" class="col-sm-2">Current password<span class="text-danger">*</span></label>
                <div class="col-sm-4">
                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Enter Current Password">
                    @error('current_password')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="password" class="col-sm-2">New password<span class="text-danger">*</span></label>
                <div class="col-sm-4">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter New Password">
                    @error('password')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="password_confirmation" class="col-sm-2">Confirm password<span class="text-danger">*</span></label>
                <div class="col-sm-4">
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Enter Confirm Password">
                     @error('password_confirmation')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-9">
                    <button type="submit" class="btn btn-success"><i class="bi bi-shield-check"></i>&nbsp;Change password</button>
                    <a href="{{ url('profile') }}" class="btn btn-outline-primary"><i class="bi bi-arrow-return-left"></i>Back</a>
                </div>
            </div>
        </form>
    </div>
@endsection