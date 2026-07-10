<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Verification - {{ $consumer->crn ?? ''}}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            @if($consumer->verification)
                <x-consumer.basic-details :consumer="$consumer" class="bg-info-subtle" />
                {{-- @php
                    if($consumer->verification->status == 1){
                        $status = "Verified Success";
                    }else{
                        $status = "Verified Issue";
                    }
                @endphp --}}
                 <div class="row g-2 pb-2 my-2 p-2 bg-warning-subtle rounded">
                    <div class="col-sm-2 text-end fw-semibold">Verified By : </div>
                    <div class="col-sm-4">{{ $consumer->verification->createdBy->name ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Verified Status : </div>
                    <div class="col-sm-4">
                        <span class="badge {{ $consumer->verification->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {!! $consumer->verification->status == 1 ? '<i class="bi bi-check-circle">&nbsp;</i>Verified Success' : '<i class="bi bi-gear">&nbsp;</i>Verified Issue' !!}
                        </span>
                    </div>
                    <div class="col-sm-2 text-end fw-semibold">Verified Date : </div>
                    <div class="col-sm-4">{{ $consumer->verification->created_at->format('d-m-Y H:i')}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Issues : </div>
                    <div class="col-sm-4">{{ $consumer->verification->remarks ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Updated By : </div>
                    <div class="col-sm-4">{{ $consumer->verification->updatedBy->name ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Remarks : </div>
                    <div class="col-sm-4">{{ $consumer->verification->updated_remarks ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Updated Date : </div>
                    <div class="col-sm-4">{{ $consumer->verification->updated_at->format('d-m-Y H:i')}}</div>
                </div>
                <x-consumer.verifysteps :verification="$consumer->verification" />  
            @else  
            <x-consumer.basic-details :consumer="$consumer" class="bg-info-subtle" />
            <div id="verify-success">
                <form id="verify-form" action="{{ url('consumers/verify/update/'.$consumer->id) }}" method="POST">
                    @csrf
                    <h4 class="fw-semibold p-2 text-dark">Please verify the consumer by completing the following verification steps.</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="p-3 border border-1 border-warning rounded-3 bg-warning bg-opacity-10">
                                <div class="row">
                                    @foreach ($steps as $step)
                                        <div class="col-4">
                                            <div class="mb-2">
                                                <label class="fw-bold">{{ $loop->iteration }}.&nbsp;{{ $step->name }}
                                                    @if($step->description)
                                                        <a class="text-primary" data-bs-toggle="tooltip" data-bs-placement="right" title="{{ $step->description }}" style="cursor:pointer;"><i class="bi bi-info-circle"></i></a>
                                                    @endif
                                                </label>
                                                <div class="mt-2">
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input verify-status" name="status[{{ $step->id }}]" value="1" data-step="{{ $step->id }}">
                                                        <label class="form-check-label">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="radio" class="form-check-input verify-status" name="status[{{ $step->id }}]" value="0" data-step="{{ $step->id }}">
                                                        <label class="form-check-label">No</label>
                                                    </div>
                                                    <div id="remarks{{ $step->id }}" class="mt-2 d-none">
                                                        <textarea class="form-control" name="remarks[{{ $step->id }}]" rows="2" placeholder="Enter issues"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 mt-3">
                        <label class="fw-bold">Remarks : </label>
                        <textarea name="issues" class="form-control" placeholder="Remarks"></textarea>
                    </div>
                    <div id="verify-error"></div>
                    <div class="text-center mt-3 mb-2">
                         <button type="submit" form="verify-form" class="btn btn-success btn-sm"><i class="bi bi-check"></i>&nbsp;Submit Details</button>
                    </div>
                </form>
            </div>
            @endif
            <div class="modal-footer">               
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
            </div>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit',['form' => 'verify'])
<script>
$(document).on('change', '.verify-status', function () {

    let stepId = $(this).data('step');

    if ($(this).val() == '0') {
        $('#remarks' + stepId).removeClass('d-none');
    } else {
        $('#remarks' + stepId).addClass('d-none');
    }
});
</script>