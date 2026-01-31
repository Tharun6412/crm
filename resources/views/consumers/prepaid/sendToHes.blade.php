{{-- Prepaid Sent to HES form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Send Consumer Details to HES</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="{{ url('consumers/prepaid/hesSubmit/'.$consumer->id) }}" name="hes-submit-form" id="hes-submit-form" method="post">
                @csrf
                @method('PUT')
                <x-consumer.basic-details :consumer="$consumer" class="bg-warning-subtle" />
                <div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-light">Meter Serial No</td>
                                <td>{{ $consumer->activeMeter->meter_serial_no }}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Meter No</td>
                                <td>{{ $consumer->activeMeter->meter_no }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-rocket-takeoff"></i>&nbsp;Send To HES
                    </button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>