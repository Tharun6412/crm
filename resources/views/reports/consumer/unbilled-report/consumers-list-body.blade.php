<input type="hidden" name="ga_id" value="{{ request('ga_id') }}">
<input type="hidden" name="aging" value="{{ request('aging') }}">
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text" id="key">Search</span>
                <input type="text" name="key" id="key" class="form-control" value="{{ request()->key }}">
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
        </div>
        {{-- <div class="col-auto">
            <a href="{{ url('reports/unbilled/list') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div> --}}
        <div class="col-auto mt-1">
            <strong>({{ $consumers->total() }})</strong> Records found
        </div>
    </div>
    <div>
        {{-- <x-auth.link href="{{ url('reports/unbilled/listExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm"><i class="bi bi-plus-lg"></i>&nbsp;Export</x-auth.link> --}}
        <a href="{{ url('reports/unbilled/listExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-outline-info">
            <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
        </a>
    </div>
</div>
@php
    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'cns_consumers.created_at';
    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
    $i = (($consumers->currentPage() - 1) * $consumers->perPage())+1;
@endphp
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover table-striped bg-white page-sort">
        <thead class="table-success">
            <tr>
                <th rowspan="2" width="1%" nowrap>S No</th>
                <th rowspan="2">
                    <a href="{{ $consumers->appends(['sortBy' => 'crn','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                        CRN
                        @if ($sort_by == 'crn')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th rowspan="2">
                    <a href="{{ $consumers->appends(['sortBy' => 'fname','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                        Name
                        @if ($sort_by == 'fname')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th rowspan="2" nowrap>Segment<x-master.segmentFilter class="float-end" /></th>
                {{-- <th>Connection Type<x-master.connection-type-filter class="float-end" /></th> --}}
                <th rowspan="2">Status</th>
                <th rowspan="2">GA<x-master.gaFilter class="float-end" /></th>
                <th rowspan="2">Activation Date</th>
                <th colspan="5" class="text-center">Last Invoice Details</th>
                <th rowspan="2">Days</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Number</th>
                <th>Consumption</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
             @forelse($consumers as $index => $consumer)
             @php
                $latest_inv = $consumer->latestInvoice()->latest()->first();
                $activationDate = $consumer->statusHistory->first()?->created_at ? \Carbon\Carbon::parse($consumer->statusHistory->first()?->created_at) : null;
                $invDate   = $latest_inv?->invoice_date   ? \Carbon\Carbon::parse($latest_inv?->invoice_date)   : null;
 
                $days = $invDate ? ceil($invDate->diffInDays(now()->startOfDay())) : ($activationDate ? ceil($activationDate->diffInDays(now()->startOfDay()))        // register → activate
                        : '-');
             @endphp
            <tr>
                <td>{{ $i++ }}</td>
                <td><i class="bi bi-{{ ($consumer->connection_type_id == 1) ? 'speedometer2' : 'wifi'}}"></i>
                            <a href="{{ url('consumers/' . $consumer->id) }}" target="_blank">
                            {{ $consumer->crn }}
                            </a>
                </td>
                <td>{{ $consumer->name}}</td>
                <td>{{ $consumer->segment->name }}</td>
                {{-- <td>{{ $consumer->connectType->name }}</td> --}}
                <td>
                    <x-consumer.status :status="$consumer->status" mode='full' />
                </td>
                <td>{{ $consumer->ga->name ?? '-' }}</td>
                <td>{{ $consumer->statusHistory->first()?->created_at?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $latest_inv?->invoice_date?->format('d-m-Y') }}</td>
                <td>{{ $latest_inv?->invoice_number }}</td>
                <td>{{ $latest_inv?->consumption?->net_consumption }}</td>
                <td>{{ numberFormat($latest_inv?->payable_amount, 2) }}</td>
                <td>{{ $latest_inv?->status?->name }}</td>
                <td>{{ $days }} Days</td>
            </tr>
            @empty
            <tr>
                <td colspan="16" class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                    No unbilled consumers found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{--  Reset pagination parameters for paginator --}}
@php
    $consumers->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
@endphp
<div>
    {{ $consumers->links('utils.paginator', ['modDiv' => 'unbilled-consumers-list']) }}
</div>
