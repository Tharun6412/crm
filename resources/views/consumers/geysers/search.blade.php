<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Search</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form action="{{ url('consumers/geysers/search') }}" id="consumer-search-form" method="GET">
                <div class="rounded bg-info-subtle p-3 mb-3">
                    <div class="row justify-content-sm-center">
                        <div class="col-sm-6">
                            <h3>Consumer Search</h3>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Enter CRN" value="{{ request()->search }}">
                                <button type="submit" class="btn btn-success">GO</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="geyser-success">
                <div id="add-geyser" class="mb-3">
                    <div id="consumer-list" ></div>
                </div>
                
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-search', ['form' => 'consumer'])