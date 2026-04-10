{{-- Close Complaint --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Close Complaint&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint Details --}}
            <div>
                <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
            </div>
            <div id="close-success" class="mt-3">
                <form id="close-form" action="{{ url('calls/closeComplaint/'.$complaint->id.'/'.$status_id) }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-3 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    @if ($complaint->consumer && $complaint->type_id != \App\Enums\ComplaintType::ENQUIRY->value) 
                        <div class="row mb-2" id="send_otp">
                            <div class="offset-sm-3 col-sm-7">
                                <button type="button" class="btn btn-success" onclick="closeOTP({{ $complaint->id }})">
                                    <i class="bi bi-check2-all" aria-hidden="true">&nbsp;</i>Send OTP
                                </button>
                            </div>
                        </div>
                        <div class="d-none" id="close_cmp">
                        <div class="row">
                            <label class="col-form-label col-sm-3 text-end">OTP&nbsp;:<span class="text-danger">*</span></label>
                            <div class="col-sm-7">
                                <input type="text" name="otp" id="otp" placeholder="Enter OTP"class="form-control form-control-sm"/>
                            </div>
                        </div>
                        <div class="offset-sm-3 col-sm-7 mb-3 alert alert-success" role="alert">
                            <small id="otp-msg"></small>
                            <button type="button" class="btn-close position-absolute top-0 end-0 p-2" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="offset-sm-3 col-sm-7" id="close-error"></div>
                        <div class="offset-sm-3 col-sm-7">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-all" aria-hidden="true">&nbsp;</i>Close complaint
                            </button>
                            <button type="button" class="btn btn-info" id="resendOtp" onclick="resendOTP({{ $complaint->id }})">
                                <i class="bi bi-check2-all" aria-hidden="true">&nbsp;</i>Resend OTP
                            </button>
                        </div>
                    </div>
                    @else
                        <div class="offset-sm-3 col-sm-7" id="close-error"></div>
                        <div class="offset-sm-3 col-sm-7">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check2-all" aria-hidden="true">&nbsp;</i>Close complaint
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'close'])
<script type="text/javascript">
    // Close OTP Function
    function closeOTP(complaint_id)
    {
        $('#send_otp').addClass('d-none');
        $('#close_cmp').removeClass('d-none');
        $.post("{{ url('calls/closeOTP') }}", {'id' : complaint_id, '_token' : '{{ csrf_token() }}'}, function(data) {
            $('#otp-msg').html(data);
        });
    }

    // Resend OTP Function
    function resendOTP(complaint_id)
    {
        $('#close_cmp').removeClass('d-none');
        $.post("{{ url('calls/resendOTP') }}", {'id' : complaint_id, '_token' : '{{ csrf_token() }}'}, function(data) {
            $('#otp-msg').html(data.message);
            if(data.count > 2) {
                $('#resendOtp').prop('disabled', true)
                    .removeClass('btn-info')
                    .addClass('btn-secondary')
                    .text('Resend limit reached');
            }
        });
    }
</script>