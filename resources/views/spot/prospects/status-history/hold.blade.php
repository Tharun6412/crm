<div class="bd-callout bd-callout-warning bg-transparent card mt-0 border-warning mb-3" id="hold-status-success">
    <form id="hold-status-form" class="form-horizontal" action="{{ url('spot/prospectStatus/updateHoldStatus/'.$prospect->id) }}" method="post">
        @csrf
        <div class="row mb-2">
            <label class="col-form-label col-sm-4 text-end">Hold Note&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
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
                <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-x-square"></i>&nbsp;Hold</button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="$('#action-type').html('')"><i class="bi bi-x-lg"></i>&nbsp;Close</button>
            </div>
        </div>
    </form>
</div>
@include('scripts.ajax-file-submit', ['form' => 'hold-status', 'callback' => 'reloadStatusHistory()'])
