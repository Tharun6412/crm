{{-- Consumers list body --}}
{{-- Search form --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text" id="search-key">Search</span>
                <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}">
            </div>
        </div>
        <div class="col-auto">
            <select name="status" id="status" class="form-select form-select-sm">
                <option value="">Select</option>
                <option value={{ \App\Enums\ConsumerStatus::PRE_REGISTER->value }} @selected(request()->status == 1)>TR</option>
                <option value={{ \App\Enums\ConsumerStatus::REGISTER->value }} @selected(request()->status == 2)>Register</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('reports/consumer/sdDetails') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $sd_amounts->total() }}) Records found
        </div>
    </div>
    <div>
        <a href="{{ url('reports/consumer/sdReportExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
        </a>
    </div>
</div>
@php
    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    $i = (($sd_amounts->currentPage() - 1) * $sd_amounts->perPage())+1;
@endphp
{{-- Consumers list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>CRN</th>
                <th>GA<x-master.ga-filter class="float-end" /></th>
                <th>Segment<x-master.segment-filter class="float-end" /></th>
                <th>Connection Type<x-master.connection-type-filter class="float-end"/></th>
                <th>Scheme<x-master.scheme-filter class="float-end"/></th>
                <th class="text-end">Total Deposit</th>
                <th class="text-end">Paid Deposit</th>
                <th class="text-end">Balance</th>
                <th>
                    <a href="{{ $sd_amounts->appends(['sortBy' => 'created_at','sortOr' => $sort_order_inverse])->url($sd_amounts->currentPage()) }}">
                        Added Date
                        @if ($sort_by == 'created_at')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a><x-master.date-filter class="float-end"/>
                </th>
            </tr>
        </thead>
        <tbody>
            @if ($sd_amounts->count() > 0)
                @php
                    $total_dep = $paid_dep = $bal_dep = 0;
                @endphp
                @foreach ($sd_amounts as $amount)
                    @php
                        $total_dep += ($amount->scheme->total_deposit ?? 0);
                        $paid_dep += ($amount->scheme->paid_deposit ?? 0);
                        $bal_dep += ($amount->scheme->balance ?? 0);
                    @endphp
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td><a href="{{ url('consumers/'.$amount->id) }}" target="_blank">{{ $amount->crn }}</a></td>
                        <td>{{ $amount->ga->name }}</td>
                        <td>{{ $amount->segment->name }}</td>
                        <td>{{ $amount->connectType->name }}</td>
                        <td>{{ $amount->scheme->scheme->name }}</td>
                        <td class="text-end">{{ $amount->scheme->total_deposit }}</td>
                        <td class="text-end">{{ $amount->scheme->paid_deposit }}</td>
                        <td class="text-end">{{ $amount->scheme->balance }}</td>
                        <td>{{ $amount->created_at->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
                <tr class="fw-semibold">
                    <td colspan="6" class="text-end">Totals</td>
                    <td class="text-end">{{ numberFormat($total_dep) }}</td>
                    <td class="text-end">{{ numberFormat($paid_dep) }}</td>
                    <td class="text-end">{{ numberFormat($bal_dep) }}</td>
                </tr>
            @else
                <tr>
                    <td colspan="10">
                        <x-layouts.callout-info>No records found!</x->
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
{{--  Reset pagination parameters for paginator --}}
@php
    $sd_amounts->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
@endphp
<div>
    {{ $sd_amounts->links('utils.paginator', ['modDiv' => 'sd-details-list']) }}
</div>
@include('scripts.link-modal')