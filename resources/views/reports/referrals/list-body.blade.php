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
            <strong>({{ $ref_requests->total() }})</strong> Records found
        </div>
    </div>
    <div>
        {{-- <button type="button" class="btn btn-outline-warning" onclick="$('#filterArea').toggleClass('d-none')">
            <i class="bi bi-funnel"></i>
        </button> --}}
        {{-- @php
            $params = request()->query();
        @endphp
        <x-auth.link href="{!! url('consumers/consumerExport'). '?' . http_build_query($params) !!}" class="btn btn-outline-primary" action="exprt">
            <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
        </x-auth.link> --}}
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
    $i = (($ref_requests->currentPage() - 1) * $ref_requests->perPage())+1;
@endphp
{{-- Consumers list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort table-striped align-middle">
        <thead class="table-success align-middle">
            <tr>
                <th colspan="7" class="text-center">Referrer</th>
                <th colspan="2" class="text-center">Referral</th>
                <th colspan="3" class="text-center">Referral Consumers</th>
                <th width="2%" nowrap rowspan="2">Actions</th>
            </tr>
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>Referral Date <x-master.date-filter/></th>
                <th nowrap>
                    <a href="{{ $ref_requests->appends(['sortBy' => 'crn','sortOr' => $sort_order_inverse])->url($ref_requests->currentPage()) }}">
                        CRN
                        @if ($sort_by == 'crn')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ $ref_requests->appends(['sortBy' => 'fname','sortOr' => $sort_order_inverse])->url($ref_requests->currentPage()) }}">
                        Name
                        @if ($sort_by == 'fname')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>Referral Code</th>
                <th>Status<x-consumer.statusFilter class="float-end" /></th>
                <th>GA<x-master.gaFilter class="float-end" /></th>
                <th>Name</th>
                <th>Phone</th>
                <th>CRN</th>
                <th>Status</th>
                <th>Referral Status</th>
                {{-- <th width="2%" nowrap rowspan="2">Actions</th> --}}
            </tr>
        </thead>
        <tbody>
            @if ($ref_requests->count() > 0)
                @foreach ($ref_requests as $referral)
                    @php
                        $consumers = $referral->referralConsumers;
                        $rowspan = max($consumers->count(), 1);
                        $firstCon = $consumers->first();
                    @endphp
                    <tr>
                        <td rowspan="{{ $rowspan }}" class="text-center">{{ $loop->iteration }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $referral->created_at->format('d-m-Y') }}</td>
                        <td rowspan="{{ $rowspan }}" nowrap>
                            <i class="bi bi-{{ ($referral->consumer->connection_type_id == 1) ? 'speedometer2' : 'wifi'}}"></i>
                            <a href="{{ url('consumers/' . $referral->consumer->id) }}" target="_blank">
                                {{ $referral->consumer->crn ?? $referral->consumer->t_crn }}
                            </a>
                        </td>
                        {{-- <td>{{ $consumer->connectType->name }}</td> --}}
                        <td rowspan="{{ $rowspan }}" width="20%">{{ $referral->consumer->name }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $referral->consumer?->consumerData?->reference_code }}</td>
                        <td rowspan="{{ $rowspan }}">
                            <x-consumer.status :status="$referral->consumer->status" mode='full' />
                        </td>
                        <td rowspan="{{ $rowspan }}">{{ $referral->consumer->ga->name }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $referral->name }}</td>
                        <td rowspan="{{ $rowspan }}">{{ maskNumber($referral->phone) }}</td>
                        {{-- First consumer inline in the same <tr> --}}
                        @if ($firstCon)
                            <td>{{ $firstCon->consumer?->crn ?? $firstCon->consumer?->t_crn }}</td>
                            <td><x-consumer.status :status="$firstCon->consumer?->status" mode='full' /></td>
                            <td>{{ $firstCon->status == 1 ? 'Earned' : 'Processing' }}</td>
                        @else
                            <td>-</td><td>-</td><td>-</td>
                        @endif
                        <td rowspan="{{ $rowspan }}"><a href="{{ url('reports/referrals/'.$referral->id) }}" class="btn btn-info btn-sm link-modal">
                            <i class="bi bi-eye">View</i>
                        </a></td>
                    </tr>
                    {{-- Remaining consumers each in their own <tr> --}}
                    @foreach ($consumers->skip(1) as $con)
                        <tr>
                            <td>{{ $con->consumer?->crn ?? $con->consumer?->t_crn }}</td>
                            <td><x-consumer.status :status="$con->consumer?->status" mode='full' /></td>
                            <td>{{ $con->status == 1 ? 'Earned' : 'Processing' }}</td>
                        </tr>
                    @endforeach
                @endforeach
            @else
                <tr>
                    <td colspan="13">
                        <x-layouts.callout-info>No records found!</x->
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
{{--  Reset pagination parameters for paginator --}}
@php
    $ref_requests->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
@endphp
<div>
    {{ $ref_requests->links('utils.paginator', ['modDiv' => 'referrals-list']) }}
</div>
@include('scripts.link-modal')
@include('scripts.bs-popover')
