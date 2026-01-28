{{-- File browser, Sub view of browser.browse --}}

<h3>File browser</h3>
<div class="border p-2">
    <form action="{{ url('dc/search') }}" class="row" id="dc-search-form">
        <div class="col-auto">
            <div class="input-group">
                <span class="input-group-text">Search</span>
                <input type="text" name="search_key" class="form-control form-control-sm" placeholder="Document number or name...">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
            </div>
        </div>
    </form>
</div>
<div id="dc-list"></div>
{{-- Load scripts --}}
@include('scripts.ajax-form-search', ['form' => 'dc'])