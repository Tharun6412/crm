{{-- Complaint In-Progress --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Complaint In-Progress&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint Details --}}
            <div>
                <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
            </div>
            {{-- Status History --}}
            <div>
                <x-consumer.complaint-statushistroy :complaint="$complaint" class="bg-info-subtle"/>
            </div>
            {{-- Feedback --}}
            <hr/>
            <div id="inprogress-success" class="mt-3">
                <h4>In-Progress</h4>
                <form id="inprogress-form" action="{{ url('calls/statusChange/'.$complaint->id.'/'.$status_id) }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-2 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="mb-2" id="inprogress-error"></div>
                    <div class="row">
                        <div class="offset-sm-2 col-sm-10">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="bi bi-save" aria-hidden="true">&nbsp;</i>Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x">&nbsp;</i>Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'inprogress'])
