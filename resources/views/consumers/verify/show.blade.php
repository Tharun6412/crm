<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Verification Details - {{ $verification->consumer->crn ?? '' }} </h4>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
            </div>
        </div>
    </div>
</div>