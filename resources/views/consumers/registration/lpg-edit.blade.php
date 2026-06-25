{{-- Create Ticket --}}
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
                    <h5>LPG Details :</h5>
                    <div class="row mb-2">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label class="form-label" for="lpg_id">LPG ID&nbsp;:<span class="text-danger">*</span></label>
                            <input name="lpg_id" id="lpg_id" class="form-control" placeholder="LPG ID" value="{{ $consumer->lpg_id }}" type="text"/>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label class="form-label" for="lpg_no">LPG Connections no (if any)&nbsp;:&nbsp;</label>
                            <input type="number" name="lpg_connections" id="lpg_connections" class="form-control" placeholder="LPG Connections" value="{{ $consumer->lpg_connections ?? '' }}"/>
                        </div>
                    </div>
                    <div id="lpg-error"></div>
                    <div class="row">
                        <div class="offset-sm-3 col-sm-7">
                            <button type="submit" class="btn btn-outline-success"><i class="bi bi-plus" aria-hidden="true">&nbsp;</i>Update</button>
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
