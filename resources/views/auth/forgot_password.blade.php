{{-- Forgot password view --}}

@extends('layouts.layout_guest')

@section('title', 'Forgot password')

@section('page-content')
    <div class="row">
        <div class="offset-md-3 col-md-6 col-sm-12">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Forgot Your Password?</h5>
                </div>
                <div class="card-body">
                <div class="card-body" id="user-verify-success">
                    <form action="{{ url('userVerify') }}" id="user-verify-form">
                        @csrf
                        <div class="row mb-3">
                            <label for="emp_id" class="col-sm-3 col-control-label">Employee Id</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="emp_id" id="emp_id" value="{{ old('emp_id') }}">
                            </div>
                        </div>
                        <div id="user-verify-error" class="mb-3"></div>
                        <div class="row">
                            <div class="offset-sm-3 col-sm-9">
                                <button type="submit" class="btn btn-success"><i class="bi bi-person-exclamation"></i>&nbsp;Verify</button>
                                <a href="{{ url('login') }}" class="btn btn-link">Login</a>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        $(function(){
            $('#user-verify-form').submit(function(e){
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serializeArray(), function(data){
                    $('#user-verify-success').html(data);
                }).fail(function(response){
                    $('#user-verify-error').html('<div class="alert alert-danger mb-0">' + response.responseJSON.message + '</div>');
                });
            });
        });
    </script>
@endsection