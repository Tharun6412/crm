{{-- Edit LPG ID form --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Update LPG Details</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            
            <div id="lpg-success"> 
                <form id="lpg-form" action="{{ url('consumers/register/domestic/lpgUpdate/'.$consumer->id) }}" method="POST">
                    @csrf
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-4 text-end" for="lpg_no">LPG Connections no (if any)&nbsp;:&nbsp;</label>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <input type="number" name="lpg_connections" id="lpg_connections" class="form-control" placeholder="LPG Connections" value="{{ $consumer->lpg_connections ?? '' }}"/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="col-form-label col-sm-4 text-end" for="lpg_id">LPG ID&nbsp;:<span class="text-danger">*</span></label>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <input name="lpg_id" id="lpg_id" class="form-control" placeholder="LPG ID" value="{{ $consumer->lpg_id }}" type="text"/>
                        </div>
                    </div>
                    <div id="lpg-error"></div>
                    <div class="row">
                        <div class="offset-sm-4 col-sm-4">
                            <button type="submit" class="btn btn-success"><i class="bi bi-save" aria-hidden="true"></i>&nbsp;Save</button>
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
@include('scripts.ajax-form-submit', ['form' => 'lpg'])
