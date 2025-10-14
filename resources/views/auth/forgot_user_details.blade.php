{{-- Forgot user details --}}

<div>
    <dl>
        <dt>Employee ID</dt>
        <dd>{{ $user->emp_id }}</dd>
        <dt>Name</dt>
        <dd>{{ $user->first_name . ' ' . $user->last_name }}</dd>
        <dt>Mobile</dt>
        <dd>{{ $user->mobile }}</dd>
        <dt>E-Mail</dt>
        <dd>{{ $user->email }}</dd>
    </dl>
    <form action="{{ url('validateUserOtp/' . $user->id) }}" id="user-otp-form">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="row mb-3">
            <label for="otp" class="col-sm col-control-label">Enter OTP</label>
            <div class="col-sm-10">
                <input type="text" name="reg_otp" id="reg_otp" class="form-control" maxlength="6">
            </div>
        </div>
        <div id="user-otp-error" class="mb-3"></div>
        <div class="row">
            <div class="offset-sm-2 col-sm-10">
                <button type="submit" class="btn btn-success"><i class="bi bi-key"></i>&nbsp;Re-Generate password</button>
                {{-- <a href="{{ url('resendEmail/' . $user_source->id) }}" class="btn btn-link" id="resend-link">Resend email</a> --}}
                <div class="pt-2" id="mail-response">
                    <div class="alert alert-info">
                        OTP has been set to your email.
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="module">
    $(function(){
        $("#user-otp-form").submit(function(e) {
            e.preventDefault();
            $.post($(this).attr('action'), $(this).serializeArray(), function(response){
                window.location.href = response.url;
            }).fail(function(response){
                $('#user-otp-error').html('<div class="alert alert-danger mb-0">' + response.responseJSON.message + '</div>');
            });
        });
        // Resend email
        $("#resend-link").click(function(e){
            e.preventDefault();
            $.post($(this).attr('href'), {'_token': "{{ csrf_token() }}"}, function(response){
                $('#mail-response').html('<div class="alert alert-success">'+ response.success +'</div>');
            });
        });
    });
</script>