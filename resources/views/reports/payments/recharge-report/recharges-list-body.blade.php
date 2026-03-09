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
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('reports/consumer/recharge/List') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $consumers->total() }}) Records found
        </div>
    </div>
    {{-- <div>
        <a href="{{ url('reports/consumer/consumerAgeingReport/consumerExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
        </a>
    </div> --}}
</div>
@php
    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    $i = (($consumers->currentPage() - 1) * $consumers->perPage())+1;
@endphp
{{-- Consumers list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>
                    <a href="{{ $consumers->appends(['sortBy' => 'cns_consumers.crn','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                        CRN
                        @if ($sort_by == 'cns_consumers.crn')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ $consumers->appends(['sortBy' => 'cns_consumers.fname','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                        Name
                        @if ($sort_by == 'cns_consumers.fname')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th>Segment<x-master.segmentFilter class="float-end" /></th>
                <th>GA<x-master.gaFilter class="float-end" /></th>
                <th>District
                    @if (request()->has('geo_area'))
                        <x-master.district-filter class="float-end"/>
                    @endif
                </th>
                <th>Amount</th>
                <th>
                    <a href="{{ $consumers->appends(['sortBy' => 'pay_recharges.recharge_date','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                        Recharge Date
                        @if ($sort_by == 'pay_recharges.recharge_date')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a><x-master.date-filter />
                </th>
                {{-- <th width="2%" nowrap>Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @if ($consumers->count() > 0)
                @foreach ($consumers as $consumer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <i class="bi bi-{{ ($consumer->connection_type_id == 1) ? 'speedometer2' : 'wifi'}}"></i>
                            <x-auth.link href="{{ url('consumers/' . $consumer->id) }}">
                            {{ $consumer->crn }}
                            </x-auth.link>
                        </td>
                        <td>{{ $consumer->consumer->name }}</td>
                        <td>{{ $consumer->consumer->segment->name }}</td>
                        <td>{{ $consumer->consumer->ga->name }}</td>
                        <td>{{ $consumer->consumer->district->name }}</td>
                        <td>{{ numberFormat($consumer->amount,2) }}</td>
                        <td>{{ dateFormat($consumer->recharge_date) }}</td>
                    </tr>
                @endforeach
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
    $consumers->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
@endphp
<div>
    {{ $consumers->links('utils.paginator', ['modDiv' => 'consumer-recharges-list']) }}
</div>
@include('scripts.link-modal')