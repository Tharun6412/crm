{{-- User registration view --}}

@extends('layouts.layout-auth')

@section('title', 'Registration')

@section('page-content')
    <h1 class="fs-3 fw-semibold"><i class="bi bi-shield-lock"></i>&nbsp;Register</h1>
    <div id="self-register-container">
        <form action="{{ url('register-verify') }}" id="register-verify">
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
            <div class="row">
                <div class="offset-sm-4 col-sm-8">
                    <button type="submit" class="btn btn-success"><i class="bi bi-person-exclamation"></i>&nbsp;Verify</button>
                    <a href="{{ url('login') }}" class="btn btn-link">LogIn</a>
                </div>
            </div>
        </form>
    </div>
    <script type="module">
        $(function(){
            $('#register-verify').submit(function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                let formData = new FormData(this);
                $('#register-verify').find('.text-danger').remove();
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#self-register-container').html(data);
                    },
                    error: function(response) {
                        $.each(response.responseJSON.errors, function(key, value) {
                            $('#register-verify').find('#'+key).parent().append('<small class="text-danger">'+ value +'</small>');
                        });
                    }
                });
            });
        });
    </script>
@endsection