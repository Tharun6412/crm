{{-- User self registration otp view --}}
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
    <form action="{{ url('validateRegisterOtp/' . $user->id) }}" id="reg_self_otp_form">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <div class="row mb-3">
            <label for="otp" class="col-sm col-form-label text-end">OTP</label>
            <div class="col-sm-9">
                <input type="text" name="reg_otp" id="reg_otp" class="form-control" maxlength="6" placeholder="Enter OTP">
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success"><i class="bi bi-key"></i>&nbsp;Generate password</button>
            <a href="{{ url('resendEmail/' . $user->id) }}" class="btn btn-link" id="resend-link">Resend email</a>
            <div class="pt-2" id="mail-response">
                <div class="alert alert-info">
                    OTP has been set to your email.
                </div>
            </div>
        </div>
    </form>
</div>
<script type="module">
    $(function(){
        $("#reg_self_otp_form").submit(function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            let formData = new FormData(this);
            $('#reg_self_otp_form').find('.text-danger').remove();
            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    window.location.href = response.url;
                },
                error: function(response) {
                    $.each(response.responseJSON.errors, function(key, value) {
                        $('#reg_self_otp_form').find('#'+key).parent().append('<small class="text-danger">'+ value +'</small>');
                    });
                }
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