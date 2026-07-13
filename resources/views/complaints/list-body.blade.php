{{-- Complaint list body --}}
@php
    use \App\Enums\ComplaintStatus;
@endphp
{{-- Search form --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <div class="input-group">
                <span class="input-group-text" id="search-key">Search</span>
                <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}" placeholder="Search CRN,Complaint No.">
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('calls') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-1">
           <span class="fw-semibold">({{ numberFormat($complaints->total()) }})</span> Records found
        </div>
    </div>
    <div>
        <a href="{{ url('calls/complaintExport') .'?'. http_build_query(request()->all()) }}" class="btn btn-outline-primary"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</a>
    </div>
</div>
{{-- Complaints / Calls list --}}
<div class="table-responsive mt-2" style="min-height: 500px;">
    @php
        $now = \Carbon\Carbon::now();
        $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
        $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
        $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
        $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
        $i = (($complaints->currentPage() - 1) * $complaints->perPage())+1;
    @endphp
    <table class="table table-bordered table-hover table-striped bg-white align-middle">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>GA <x-master.ga-filter class="float-end"/></th>
                <th nowrap>
                    <a href="{{ $complaints->appends(['sortBy' => 'code','sortOr' => $sort_order_inverse])->url($complaints->currentPage()) }}">
                        Complaint
                        @if ($sort_by == 'code')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th>Type</th>
                <th nowrap>
                    <div class="d-flex">
                        <div>Category &nbsp;</div>
                        <x-master.complaint-category-filter class="text-end float-end"/>
                    </div></th>
                <th nowrap>Sub Category
                    @if (request()->has('category'))
                        <x-master.complaint-sub-category-filter class="float-end" />
                    @endif
                </th>
                <th>CRN</th>
                <th>Consumer</th>
                <th>
                    <div class="d-flex">
                        <div>Segment&nbsp;</div>
                        <x-complaint.segment-filter  class="float-end"/>
                    </div>                   
                </th>
                <th nowrap>
                    <div class="d-flex">
                        <a href="{{ $complaints->appends(['sortBy' => 'created_at','sortOr' => $sort_order_inverse])->url($complaints->currentPage()) }}">Raised Date</a>&nbsp;
                            @if ($sort_by == 'created_at')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        <x-master.date-filter  class="float-end" />
                    </div> 
                </th>
                <th nowrap>Raised By<x-complaint.user-filter class="float-end"/></th>
                <th nowrap>
                    <a href="{{ $complaints->appends(['sortBy' => 'estimated_closed_at','sortOr' => $sort_order_inverse])->url($complaints->currentPage()) }}">
                        Est. Close Date
                        @if ($sort_by == 'estimated_closed_at')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>
                    <a href="{{ $complaints->appends(['sortBy' => 'closed_at','sortOr' => $sort_order_inverse])->url($complaints->currentPage()) }}">
                        Closed Date
                        @if ($sort_by == 'closed_at')
                            <i class="bi {{ $sort_icon }}"></i>
                        @endif
                    </a>
                </th>
                <th nowrap>Deviation</th>
                <th nowrap>PNGRB Category</th>
                <th nowrap>
                    <div class="d-flex">
                        <div>Status &nbsp;</div>
                        <x-complaint.statusFilter class="float-end" />
                    </div> 
                </th>
                <th nowrap>
                    <div class="d-flex flex-row gap-2">
                        <div>Feedback</div>
                        <div>
                            @php
                                $feedback_filters = [1 => 'Given', 0 => 'Pending'];
                            @endphp
                            <x-admin.status-filter name="pf" :data="$feedback_filters" class="float-end"/>
                        </div>
                    </div>
                </th>
                <th width="2%" nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($complaints->count() > 0)
                @foreach ($complaints as $complaint)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td nowrap>{{ $complaint->ga->name ?? '' }}</td>
                        <td nowrap>
                            <a href="{{ url('calls/'.$complaint?->id) }}" class="link-modal">{{ $complaint?->code }}</a>
                        </td>
                        <td>{{ $complaint->type?->name ?? ''}}</td>
                        <td nowrap>{{ $complaint->category?->parent?->name }}</td>
                        <td nowrap>{{ $complaint->category?->name }}</td>
                        <td nowrap>
                            <a href="{{ url('consumers/' . $complaint?->consumer_id) }}" target="_blank">{{ $complaint->consumer?->crn }}</a>
                        </td>
                        <td nowrap>{{ ($complaint->consumer_id > 0) ? $complaint->consumer?->name : $complaint?->name }}</td>
                        <td nowrap>{{ $complaint->segment?->name }}</td>
                        <td nowrap>{{ dateFormat($complaint->created_at) }}</td>
                        <td nowrap>{{ $complaint->createdBy?->name ?? ''}}</td>
                        <td nowrap>
                            {{ $complaint->estimated_closed_at?->format('d-m-y H:i') }}
                        </td>
                        <td nowrap>{{ $complaint->closed_at?->format('d-m-Y H:i') }}</td>
                        <td class="text-center"><x-complaint.day-hour-display :complaint="$complaint"/></td>
                        <td nowrap>{{ $complaint->category?->priority?->name }}</td>
                        <td nowrap><x-complaint.status :status="$complaint?->status"/></td>
                        <td nowrap>
                            @if ($complaint->feedback->count() > 0)
                                <x-complaint.rating :rating="$complaint->feedback->last()?->rating"/>
                            @else
                                NA
                            @endif
                        </td>
                        <td>
                            {{-- list actions --}}
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-modal" href="{{ url('calls/' . $complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</a></li>
                                    {{--Complaint Status Dropdown--}}
                                    @if ($complaint->status_id == ComplaintStatus::REGISTER->value)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/'.$complaint->id.'/edit') }}" action="edit"><i class="bi bi-chevron-right"></i>&nbsp;Edit</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/assign/'.$complaint->id) }}" action="asgn"><i class="bi bi-chevron-right"></i>&nbsp;Assign</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/close/'.$complaint->id) }}" action="close"><i class="bi bi-chevron-right"></i>&nbsp;Close</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/cancel/'.$complaint->id) }}" action="cncl"><i class="bi bi-chevron-right"></i>&nbsp;Cancel</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == ComplaintStatus::ASSIGN->value)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/inProgress/'.$complaint->id) }}" action="inprgs"><i class="bi bi-chevron-right"></i>&nbsp;In Progres</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/investigate/'.$complaint->id) }}" action="invstgn"><i class="bi bi-chevron-right"></i>&nbsp;Investigate</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == ComplaintStatus::IN_PROGRESS->value OR $complaint->status_id == ComplaintStatus::INVESTIGATION->value)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/close/'.$complaint->id) }}" action="close"><i class="bi bi-chevron-right"></i>&nbsp;Close</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == ComplaintStatus::CLOSE->value && ($complaint->feedback->count() == 0))
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/feedback/'.$complaint->id.'/edit') }}" action="fedbk"><i class="bi bi-chevron-right"></i>&nbsp;Feedback</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == ComplaintStatus::CLOSE->value AND in_array(7, $complaint->statushistory->pluck('status_id')->unique()->toArray()) AND ($complaint->feedback->count() < 2))
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/feedback/'.$complaint->id.'/edit') }}" action="fedbk"><i class="bi bi-chevron-right"></i>&nbsp;Feedback</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == ComplaintStatus::CLOSE->value && $complaint->feedback->count() == 1 && !in_array(ComplaintStatus::REOPEN->value,$complaint->statusHistory->pluck('status_id')->toArray()))
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/reopen/'.$complaint->id) }}" action="reopen"><i class="bi bi-chevron-right"></i>&nbsp;Reopen</x-auth.link></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="17">
                        <x-layouts.callout-info>No records found!</x->
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
{{--  Reset pagination parameters for paginator --}}
@php
    $complaints->appends(['sortBy' => $sort_by, 'sortOr' => $sort_order]);
@endphp
<div>
    {{ $complaints->links('utils.paginator', ['modDiv' => 'complaints-list']) }}
</div>
@include('scripts.link-modal')
