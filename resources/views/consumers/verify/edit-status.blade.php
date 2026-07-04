<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Verification Details - {{ $verification->consumer->crn ?? ''}}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div>
                <x-consumer.basic-details :consumer="$verification->consumer" class="bg-info-subtle" />
                @php
                    if($verification->status == 1){
                        $status = "Verified Success";
                    }else{
                        $status = "Verified Issue";
                    }
                @endphp
                <div class="row g-2 pb-2 my-2 p-2 bg-warning-subtle rounded">
                    <div class="col-sm-2 text-end fw-semibold">Verified By : </div>
                    <div class="col-sm-4">{{ $verification->createdBy->name ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Verified Status : </div>
                    <div class="col-sm-4">{{ $status ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Verified Date : </div>
                    <div class="col-sm-4">{{ $verification->created_at->format('d-m-Y H:i')}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Issues : </div>
                    <div class="col-sm-4">{{ $verification->remarks ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Updated By : </div>
                    <div class="col-sm-4">{{ $verification->updatedBy->name ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Remarks : </div>
                    <div class="col-sm-4">{{ $verification->updated_remarks ?? ''}}</div>
                    <div class="col-sm-2 text-end fw-semibold">Updated Date : </div>
                    <div class="col-sm-4">{{ $verification->updated_at->format('d-m-Y H:i')}}</div>
                </div>
                <x-consumer.verifysteps :verification="$verification" class="bg-warning-subtle" />
            </div>
            <h4>Update Verification Status :</h4>
            <div id="verify-success">
                <form id="verify-form" action="{{ url('consumers/verify/updateStatus/'.$verification->id) }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Verification Status : </label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="0" @selected($verification->status == 0)>
                                    Verified Issue
                                </option>
                                <option value="1" @selected($verification->status == 1)>
                                    Verified Success
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="updated_remarks" class="form-label">Remarks : </label>
                            <textarea id="updated_remarks" name="updated_remarks" rows="3" class="form-control" placeholder="Enter remarks"></textarea>
                        </div>
                    </div>
                    <div id="verify-error"></div>
                    <div class="mt-2 mb-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
            </div>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit',['form' => 'verify'])