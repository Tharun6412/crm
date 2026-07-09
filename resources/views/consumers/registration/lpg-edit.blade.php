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
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label class="form-label" for="lpg_consumer_number">LPG Consumer Number&nbsp;:<span class="text-danger">*</span></label>
                            <input name="lpg_consumer_number" id="lpg_consumer_number" class="form-control" placeholder="LPG Consumer Number" value="{{ $consumer->consumerData?->lpg_consumer_number  }}" type="text"/>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label class="form-label" for="lpg_id">LPG ID&nbsp;:<span class="text-danger">*</span></label>
                            <input name="lpg_id" id="lpg_id" class="form-control" placeholder="LPG ID" value="{{ $consumer->consumerData?->lpg_id }}" type="text"/>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label class="form-label" for="lpg_omc_id">LPG OMC Type&nbsp;:<span class="text-danger">*</span></label>
                            <select name="lpg_omc_id" id="lpg_omc_id" class="form-select">
                                <option value="">Select OMC Type</option>
                                @foreach ($omcs as $omc )
                                    <option value="{{ $omc->id }}"@selected($consumer->consumerData?->lpg_omc_id == $omc->id)>{{ $omc->name }}</option>                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label class="form-label" for="registered_mobile">LPG Registered Mobile&nbsp;:<span class="text-danger">*</span></label>
                            <input type="text" name="registered_mobile" id="registered_mobile" class="form-control" placeholder="Registered Mobile" value="{{ $consumer->consumerData?->registered_mobile }}"/>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label class="form-label" for="lpg_connections">LPG Connections no (if any)&nbsp;:&nbsp;</label>
                            <input type="number" name="lpg_connections" id="lpg_connections" class="form-control" placeholder="LPG Connections" value="{{ $consumer->consumerData?->lpg_connections }}"/>
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
