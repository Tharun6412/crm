{{-- User self registration password view --}}
@extends('layouts.layout_guest')

@section('title', 'Re-Generate Password')

@section('page-content')
    <div class="row">
        <div class="offset-md-3 col-md-6 col-sm-12">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Forgot Password - Re-Generate</h5>
                </div>
                <div class="card-body">
                    <form action="{{ url('updatePassword/' . session()->get('forgot_user')) }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <label for="password" class="col-sm-3 col-control-label">Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="password" id="password" class="form-control">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password_confirmation" class="col-sm-3 col-control-label">Re Type Password</label>
                            <div class="col-sm-9">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-success"><i class="bi bi-check2-square"></i>&nbsp;Register</button>
                                <a href="{{ url('cancelRegistration') }}" class="btn btn-link" id="register-cancel">Cancel</a>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="offset-sm-3 col-sm-9 form-text">
                                Your password must be minimum of 8 characters long, contain letters and numbers, and atleast one uppercase letter.
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection