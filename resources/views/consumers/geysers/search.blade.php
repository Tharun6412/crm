{{-- Search and Create Geyser connection--}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Create Geyser Connection</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            {{-- Seach form --}}
            <form action="{{ url('consumers/geysers/search') }}" id="consumer-search-form" method="GET">
                <div class="rounded bg-info-subtle p-2 mb-2">
                    <div class="d-flex justify-content-between">
                        <h5 class="me-2 align-content-center">Search</h5>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Enter CRN" value="{{ request()->search }}">
                            <button type="submit" class="btn btn-success">GO</button>
                        </div>
                    </div>
                </div>
            </form>
            <div id="geyser-success">
                <div id="add-geyser" class="mb-3">
                    <div id="consumer-list" >
                        {{-- Geyser --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.ajax-form-search', ['form' => 'consumer'])