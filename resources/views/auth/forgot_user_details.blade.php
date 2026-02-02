{{-- Forgot user details --}}

<div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="bg-light">Employee ID</td>
                <td class="bg-white">{{ $user->emp_id }}</td>
            </tr>
            <tr>
                <td class="bg-light">Name</td>
                <td class="bg-white">{{ $user->name }}</td>
            </tr>
            <tr>
                <td class="bg-light">Email</td>
                <td class="bg-white">{{ $user->email }}</td>
            </tr>
        </tbody>
    </table>
    <form action="{{ url('validateUserOtp/' . $user->id) }}" id="user-otp-form">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="row mb-3">
            <label for="otp" class="col-sm col-form-label text-end">OTP</label>
            <div class="col-sm-10">
                <input type="text" name="reg_otp" id="reg_otp" class="form-control" maxlength="6" placeholder="Enter OTP">
            </div>
        </div>
        <div id="user-otp-error" class="mb-3"></div>
        <div class="row">
            <div class="offset-sm-2 col-sm-10">
                <button type="submit" class="btn btn-success"><i class="bi bi-key"></i>&nbsp;Re-Generate password</button>
                {{-- <a href="{{ url('resendEmail/' . $user_source->id) }}" class="btn btn-link" id="resend-link">Resend email</a> --}}
                <div class="pt-2" id="mail-response">
                    <div class="alert alert-info">
                        OTP has been set to your email. {{ $otp }}
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