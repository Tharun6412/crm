{{-- User registration view --}}

@extends('layouts.layout_guest')

@section('title', 'Registration')

@push('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endpush

@section('page-content')
    <div class="row">
        <div class="offset-md-3 col-md-6 col-sm-12">
            {{-- <div class="d-flex justify-content-center m-5"> --}}
                <div class="card w-100">
                    <div class="card-header">
                        <h5 class="card-title">User Registration</h5>
                    </div>
                    <div class="card-body" id="self-register-container">
                        <form action="{{ url('register-verify') }}" id="register-verify">
                            @csrf
                            <div class="row mb-3">
                                <label for="emp_id" class="col-sm-3 col-control-label">Employee Id</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="emp_id" id="emp_id" value="{{ old('emp_id') }}">
                                    @error('emp_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="offset-sm-3 col-sm-9">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-person-exclamation"></i>&nbsp;Verify</button>
                                    <a href="{{ url('login') }}" class="btn btn-link">Login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            {{-- </div> --}}
        </div>
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