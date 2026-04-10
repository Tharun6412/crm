{{-- Prepaid Sent to HES form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Send Consumer Details to HES</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="mt-2" id="hes-submit-success">
                <form action="{{ url('consumers/prepaid/hesSubmit/'.$consumer->id) }}" name="hes-submit-form" id="hes-submit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <x-consumer.basic-details :consumer="$consumer" class="bg-warning-subtle" />
                    <div>
                        <table class="table table-bordered">
                            <tbody>
                                <input type="hidden" name="meter_id" id="meter_id" value="{{ $consumer->activeMeter->id }}">
                                <tr>
                                    <td class="bg-light">Meter Serial No</td>
                                    <td><input type="text" class="form-control" name="meter_sr_no" id="meter_sr_no" value="{{ $consumer->activeMeter->meter_serial_no }}" placeholder="PG**********" ></td>
                                </tr>
                                <tr>
                                    <td class="bg-light">Meter No</td>
                                    <td><input type="text" class="form-control" name="meter_no" id="meter_no" value="{{ $consumer->activeMeter->meter_no }}" placeholder="PN**********"></td>
                                </tr>
                                <tr>
                                    <td class="bg-light">Price Group</td>
                                    <td>
                                        <select class="form-select" name="price_group_id" id="price_group_id">
                                            <option value="">All</option>
                                            @foreach ($price_groups as $price)
                                                <option value="{{ $price->id }}" @selected($consumer->price_group_id == $price->id)>{{ $price->code }}-{{ $price->price }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-light">VCF</td>
                                    <td><input type="text" class="form-control" name="vcf" id="vcf" value={{ numberFormat($consumer->activeMeter?->vcf,3) }} placeholder="Please enter the VCF."></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-danger mt-3" id="hes-submit-error"></div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-rocket-takeoff"></i>&nbsp;Send To HES
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'hes-submit'])
