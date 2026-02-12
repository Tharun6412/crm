<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Aging Invoice List</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            {{-- Filters --}}
            <form id="aging-invoices-search-form" name="aging-invoices-search-form" action="{{ url('reports/ageingReport/invoicesList') }}">
                @csrf
                <input type="hidden" name="ga_id" value="{{ request('ga_id') }}">
                <input type="hidden" name="range" value="{{ request('range') }}">
                <input type="hidden" name="is_filter" id="is_filter" value="1">
                <div class="d-flex justify-content-between">
                    <div class="row gx-1 mb-1">
                        <div class="col-auto">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text" id="key">Search</span>
                                <input type="text" name="key" id="key" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                    <div>
                        <x-auth.link href="{{ url('reports/ageingReport/agingInvoicesExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm"><i class="bi bi-plus-lg"></i>&nbsp;Export</x-auth.link>
                    </div>
                </div>
            </form>
            <div id="aging-invoices-list">
                @include('reports.consumer.aging-report.invoices-list-body')
            </div>
        </div>
    </div>
</div>
@include('scripts.ajax-form-search',['form' => 'aging-invoices']);