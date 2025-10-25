<div class="bd-callout bd-callout-danger bg-transparent card mt-0 border-danger mb-3" id="cancel-status-success">
    <form id="cancel-status-form" class="form-horizontal" action="{{ url('spot/prospectStatus/updateCancelStatus/'.$prospect->id) }}" method="post">
        @csrf
        <div class="row mb-2">
            <label class="col-form-label col-sm-4 text-end">Cancel Note&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <textarea name="notes" id="notes" class="form-control form-control-sm" placeholder="Enter Note"></textarea>
                </div>
                <small class="text-danger" id="notes-error"></small>
            </div>
        </div>
        <div class="row">
            <label class="col-form-label col-sm-4 text-end">&nbsp;</label>
            <div class="col-md-6">
                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x-square"></i>&nbsp;Cancel</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="$('#action-type').html('')"><i class="bi bi-x-lg"></i>&nbsp;Close</button>
            </div>
        </div>
    </form>
</div>
@include('scripts.ajax-file-submit', ['form' => 'cancel-status', 'callback' => 'reloadStatusHistory()'])
