{{-- Refund list body --}}
@php
    use \App\Enums\RefundStatus;
@endphp
{{-- Search form --}}
<div class="d-flex justify-content-between">
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
            <a href="{{ url('consumers/refunds') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-2">
        <span class="fw-semibold">({{ $refunds_list->total() }})</span> Records found
        </div>
    </div>
    <div>
        @if($refunds_list->count() > 0)
        {{-- <a href="{{ url('reports/consumer/refundReportExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-outline-info"> --}}
            <x-auth.link href="{{ url('reports/consumer/refundReportExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-outline-info" action="exprt">
                <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
            </x-auth.link>
        @endif
    </div>
</div>
{{-- Consumers list --}}
<div class="table-responsive mt-2" style="min-height: 500px;">
    <table class="table table-bordered table-striped bg-white">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>Consumer Number</th>
                <th>GA<x-master.ga-filter class="float-end"/></th>
                <th>Request Number</th>
                <th class="text-end">Refund Amount&nbsp;(&#8377;)</th>
                <th class="text-end">Refunded&nbsp;(&#8377;)</th>
                <th>Status<x-master.refund-status-filter class="float-end"/></th>
                <th class="text-center">Added Date</th>
                <th width="2%" nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($refunds_list->count() > 0)
                @php
                    $now = \Carbon\Carbon::now();
                    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
                    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
                    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
                    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
                    $i = (($refunds_list->currentPage() - 1) * $refunds_list->perPage())+1;
                    $tot_amt = $ref_amt = 0;
                @endphp
                @foreach ($refunds_list as $list)
                    @php
                        $tot_amt += ($list->refund_amount ?? 0);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td><a href="{{ url('consumers/'.$list->consumer_id) }}" target="_blank">{{ $list->consumer->crn }}</a></td>
                        <td>{{ $list->consumer->ga->name }}</td>
                        <td><a href="{{ url('consumers/refunds/'.$list->id) }}" class="link-modal">{{ $list->request_no }}</a></td>
                        <td class="text-end">{{ numberFormat($list->refund_amount ?? 0, 2) }}</td>
                        @if ($list->status_id == \App\Enums\RefundStatus::CLOSE->value)
                            @php
                                $ref_amt += $list->refund_amount;
                            @endphp
                            <td class="text-end">{{ numberFormat($list->refund_amount, 2) }}</td>
                        @else 
                            <td class="text-end">0</td>
                        @endif
                        <td>{{ $list->status?->name }}</td>
                        <td class="text-center">{{ dateFormat($list->created_at) }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-modal" href="{{ url('consumers/refunds/' . $list->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</a></li>
                                    @if ($list->status_id == RefundStatus::REQUEST->value)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/refunds/process/' . $list->id) }}" action="prcs"><i class="bi bi-chevron-right"></i>&nbsp;Process</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/refunds/editDocument/'.$list->id) }}" action="doc"><i class="bi bi-chevron-right"></i>&nbsp;Upload Document</x-auth.link></li>
                                    @endif
                                    @if ($list->status_id == RefundStatus::PROCESS->value)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/refunds/approve/' . $list->id) }}" action="apprv"><i class="bi bi-chevron-right"></i>&nbsp;Approve</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/refunds/editDocument/'.$list->id) }}" action="doc"><i class="bi bi-chevron-right"></i>&nbsp;Upload Document</x-auth.link></li>

                                    @endif
                                    @if ($list->status_id == RefundStatus::APPROVE->value)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/refunds/editDocument/'.$list->id) }}" action="doc"><i class="bi bi-chevron-right"></i>&nbsp;Upload Document</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('consumers/refunds/close/' . $list->id) }}" action="close"><i class="bi bi-chevron-right"></i>&nbsp;Close</x-auth.link></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr class="bg-info-subtle fw-semibold text-end">
                    <td colspan="4" class="text-end">Total</td>
                    <td>{{ numberFormat($tot_amt, 2) }}</td>
                    <td>{{ numberFormat($ref_amt, 2) }}</td>
                    <td colspan="3"></td>
                </tr>
            @else
                <tr>
                    <td colspan="9">
                        <x-layouts.callout-info>No records found!</x->
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div>
    @if ($refunds_list->count() > 0)
        {{ $refunds_list->links('utils.paginator', ['modDiv' => 'refunds-list']) }}
    @endif
</div>
@include('scripts.link-modal')