<div>
    {{-- Filters --}}
    <form id="invoices-search-form" name="invoices-search-form" action="{{ url('reports/invoiceReport') }}">
        <input type="hidden" name="ga_id" value="{{ request('ga_id') }}">
        <input type="hidden" name="range" value="{{ request('range') }}">
        <input type="hidden" name="invoice_type" value="{{ request('invoice_type') }}">
        {{-- <input type="hidden" name="is_filter" id="is_filter" value="1"> --}}
        <div class="d-flex justify-content-between">
            <div class="row gx-1 mb-1">
                <div class="col-auto">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" id="key">Search</span>
                        <input type="text" name="key" id="key" class="form-control" value="">
                    </div>
                </div>
                <div class="col-auto">

                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
                </div>
            </div>
            {{-- <div>
                <x-auth.link href="{{ url('reports/ageingReport/agingInvoicesExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm"><i class="bi bi-plus-lg"></i>&nbsp;Export</x-auth.link>
            </div> --}}
        </div>
    </form>
</div>
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th nowrap>S No</th>
                <th nowrap>Invoice No</th>
                <th nowrap>Invoice Date<x-master.date-filter /></th>
                <th nowrap>Invoice Type</th>
                <th nowrap>CRN</th>
                <th nowrap>Consumer Name</th>
                <th nowrap>Segment<x-master.segment-filter /></th>
                <th nowrap>District<x-master.district-filter /></th>
                <th nowrap>GA<x-master.ga-filter /></th>
                <th nowrap>State</th>
                <th nowrap>Consumption</th>
                <th nowrap>Due Date</th>
                <th class="text-end" nowrap>Invoice Amount</th>
                <th class="text-end" nowrap>Balance Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = (($invoices->currentPage() - 1) * $invoices->perPage())+1;
            @endphp
            @forelse($invoices as $inv)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $inv->invoice_number }}</td>
                    <td>{{ dateFormat($inv->invoice_date) }}</td>
                    <td>{{ $inv->invoiceType->name }}</td>
                    <td>{{ $inv->consumer->crn }}</td>
                    <td>{{ $inv->consumer->name }}</td>
                    <td>{{ $inv->consumer->segment->name }}</td>
                    <td>{{ $inv->consumer->district->name }}</td>
                    <td>{{ $inv->consumer->ga->name }}</td>
                    <td>{{ $inv->consumer->state->name }}</td>
                    <td>{{ $inv->net_consumption ?? 0 }}</td>
                    <td>{{ dateFormat($inv->due_date) }}</td>
                    <td class="text-end">{{ numberFormat($inv->payable_amount, 2) }}</td>
                    <td class="text-end">{{ numberFormat($inv->balance_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">No Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end" colspan="10">Total</th>
                <th>{{ numberFormat($invoices->sum('net_consumption'),2) }}</th>
                <th></th>
                <th class="text-end">{{ numberFormat($invoices->sum('payable_amount'),2) }}</th>
                <th class="text-end">{{ numberFormat($invoices->sum('balance_amount'),2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>

<div>
    {{ $invoices->links('utils.paginator', ['modDiv' => 'invoices-list']) }}
</div>
@include('scripts.ajax-form-search',['form' => 'invoices']);
