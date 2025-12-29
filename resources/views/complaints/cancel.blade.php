{{-- Cancel Complaint --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Cancel Complaint&nbsp;#{{ $complaint->code }}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer + Complaint Details --}}
            <div>
                <x-consumer.complaint-details :complaint="$complaint" class="bg-info-subtle"/>
            </div>
            <div id="cancel-success" class="mt-3">
                <form id="cancel-form" action="{{ url('calls/statusChange/'.$complaint->id.'/'.$status_id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <label class="col-form-label col-sm-3 text-end">Notes&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-sm-7">
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="my-1" id="cancel-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-7">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-x-circle" aria-hidden="true">&nbsp;</i>Cancel
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
@include('scripts.ajax-form-submit', ['form' => 'cancel'])