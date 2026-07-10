{{-- Consumers list body --}}
{{-- Search form --}}
<div class="d-flex justify-content-between pb-2">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <div class="input-group">
                <span class="input-group-text" id="search-key">Search</span>
                <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}">
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url()->current() }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-2">
            <strong>({{ $consumers->total() }})</strong> Records found
        </div>
    </div>
    <div>
        <button type="button" class="btn btn-outline-warning" onclick="$('#filterArea').toggleClass('d-none')">
            <i class="bi bi-funnel"></i>
        </button>
        @php
            $params = request()->query();
            if ($status?->id) {
                $params['status_id'] = $status->id;
            }
        @endphp
        <x-auth.link href="{!! url('consumers/consumerExport'). '?' . http_build_query($params) !!}" class="btn btn-outline-primary" action="exprt">
            <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
        </x-auth.link>
        {{-- <a href="{{ url('consumers/consumerExport') }}">Q Export</a> --}}
    </div>
</div>
<div class="bg-body-secondary p-2 mb-1 {{ (request()->has('connection_type_id') OR request()->has('charge_area') OR request()->has('area')) ? '' : 'd-none' }}" id="filterArea">
    @include('consumers.consumers.list-body-filter')
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
    <table class="table table-bordered table-hover page-sort table-striped align-middle">
        <thead class="table-success align-middle">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th nowrap>
                    <a href="{{ $consumers->appends(['sortBy' => 'crn','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                        CRN
                        @if ($sort_by == 'crn')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ $consumers->appends(['sortBy' => 'fname','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                        Name
                        @if ($sort_by == 'fname')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th><div class="d-flex gap-1">Segment<x-master.segmentFilter class="float-end" /></div></th>
                <th><div class="d-flex gap-1">Status<x-consumer.statusFilter class="float-end" /></div></th>
                <th><div class="d-flex gap-2">GA<x-master.gaFilter class="float-end" /></div></th>
                <th>District
                    @if (request()->has('geo_area'))
                        <x-master.district-filter class="float-end"/>
                    @endif
                </th>
                <th><div class="d-flex">Scheme<x-master.scheme-filter class="float-end"/></div></th>
                <th nowrap>
                    <div class="d-flex">
                        <a href="{{ $consumers->appends(['sortBy' => 'created_at','sortOr' => $sort_order_inverse])->url($consumers->currentPage()) }}">
                            Created Date
                            @if ($sort_by == 'created_at')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        </a><x-master.date-filter />
                    </div>
                </th>
                <th nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($consumers->count() > 0)
                @foreach ($consumers as $consumer)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td nowrap>
                            <i class="bi bi-{{ ($consumer->connection_type_id == 1) ? 'speedometer2' : 'wifi'}}"></i>
                            <a href="{{ url('consumers/' . $consumer->id) }}" target="_blank">
                                {{ $consumer->crn ?? $consumer->t_crn }}
                            </a>
                        </td>
                        {{-- <td>{{ $consumer->connectType->name }}</td> --}}
                        <td width="20%">{{ $consumer->name }}</td>
                        <td>{{ $consumer->segment->name }}</td>
                        <td>
                            <x-consumer.status :status="$consumer->status" mode='full' />
                        </td>
                        <td nowrap>{{ $consumer->ga->name }}</td>
                        <td>{{ $consumer->district->name }}</td>
                        <td>
                            @if ($consumer->scheme?->scheme?->id != null)
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="popover"  data-bs-trigger="hover focus" data-bs-placement="top"  data-bs-content="{{ $consumer->scheme?->scheme?->name }}">{{ $consumer->scheme?->scheme?->code }}</button>
                            @endif
                        </td>
                        <td>{{ dateFormat($consumer->created_at) }}</td>
                        <td>
                            @include('consumers.consumers.list-actions')
                        </td>
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
    {{ $consumers->links('utils.paginator', ['modDiv' => 'consumers-list']) }}
</div>
@include('scripts.link-modal')
@include('scripts.bs-popover')
