<div>
    @if (empty($active_requests))
        <div class="bd-callout bd-callout-success bg-transparent card mt-0 border-success mb-3" id="date-request-success">
            <form id="date-request-form" action="{{ url('spot/dateChangeRequest/store/'.$prospect->id) }}" class="form-horizontal" method="post">
                @csrf
                <div class="row mb-1">
                    <h5 class="modal-title">Request for date change</h5>
                    <label for="status" class="col-form-label col-sm-4 text-end text-black">
                        Current Gas Expected Date&nbsp;:
                    </label>
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <input type="text" name="current_expected" id="current_expected" class="form-control form-control-sm" value="{{ $prospect->expected_date?->format('d-m-Y') }}" readonly>
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        </div>
                    </div>
                </div>
                <div class="row mb-1 ">
                    <label class="col-form-label col-sm-4 text-end text-black">New Gas Expected Date&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <input type="text" name="new_date" id="new_date" class="form-control form-control-sm datepicker"  placeholder="Select New Date">
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        </div>
                    <small class="text-danger" id="new_date-error"></small>
                    </div>
                </div>
                <div class="row mb-2">
                    <label class="col-form-label col-sm-4 text-end text-black">Notes&nbsp;<span class="error text-danger">*</span>&nbsp;:</label>
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <textarea name="note" id="note" class="form-control form-control-sm" placeholder="Enter Notes"></textarea>
                        </div>
                        <small class="text-danger" id="note-error"></small>
                    </div>
                </div>
                <div class="row mb-0">
                    <label class="col-form-label col-sm-4 text-end">&nbsp;</label>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-save2"></i>&nbsp;Request Date</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="$('#action_type').html('')"><i class="bi bi-x-lg"></i>&nbsp;Close</button>
                    </div>
                </div>
            </form>
        </div>
    @else
        <div class="alert alert-warning">A request is already in progress, please respond to it and create new request.</div>
    @endif
</div>
@include('scripts.ajax-file-submit', ['form' => 'date-request', 'callback' => 'reloadDateRequest()'])
<script type="text/javascript">
    //New Date
    $('#new_date').datepicker({format : 'dd-mm-yyyy', startDate: 'today',  autoHide : true});
    // Reload Date Request
    function reloadDateRequest()
    {
        $.get($('#reload-date-request').attr('href'), function(data) {
            $('#date-request').html(data);
        });
    }
</script>