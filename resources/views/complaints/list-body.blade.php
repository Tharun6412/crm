{{-- Complaint list body --}}
@php
    use \App\Enums\ComplaintStatus;
@endphp
{{-- Search form --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <div class="input-group input-group-sm">
                <span class="input-group-text" id="search-key">Search</span>
                <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}" placeholder="search complaint no.">
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('calls') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $complaints->total() }}) Records found
        </div>
    </div>
    <div>
        <x-auth.link href="{{ url('calls/complaintExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-secondary btn-sm"><i class="bi bi-plus-lg"></i>&nbsp;Export</x-auth.link>
    </div>
</div>
{{-- Complaints / Calls list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>GA</th>
                <th>#Complaint</th>
                <th>Category</th>
                <th>CRN</th>
                <th>Consumer</th>
                <th>Segment<x-complaint.segment-filter/></th>
                <th>Estimated Close Date</th>
                <th>Closed Date</th>
                <th>Priority</th>
                <th nowrap>Status<x-complaint.statusFilter class="float-end" /></th>
                <th>Created At<x-master.date-filter /></th>
                <th width="2%" nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($complaints->count() > 0)
                @php
                    $now = \Carbon\Carbon::now();
                    $sort_by = (request()->has('sortBy')) ? request()->get('sortBy') : 'created_at';
                    $sort_order = (request()->has('sortOr')) ? request()->get('sortOr') : 'desc';
                    $sort_order_inverse = ($sort_order == 'asc') ? 'desc' : 'asc';
                    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
                    $i = (($complaints->currentPage() - 1) * $complaints->perPage())+1;
                @endphp
                @foreach ($complaints as $complaint)
                    @php
                        if ($complaint->category->resolution_type == 1) {
                            $difference = ceil(abs($now->diffInDays(\Carbon\Carbon::parse($complaint->estimated_closed_at))))."D";
                        }else {
                            $difference = numberFormat(abs($now->diffInHours(\Carbon\Carbon::parse($complaint->estimated_closed_at))), 2)."H";
                        }
                    @endphp
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td nowrap>{{ $complaint->ga->name ?? '' }}</td>
                        <td nowrap>
                            <x-auth.link href="{{ url('calls/'.$complaint->id) }}" class="link-modal">{{ $complaint->code }}</x-auth.link>
                        </td>
                        <td nowrap>{{ $complaint->category->name }}</td>
                        <td nowrap>
                            <x-auth.link href="{{ url('consumers/' . $complaint->consumer_id) }}" target="_blank">{{ $complaint->consumer->crn }}</x-auth.link>
                        </td>
                        <td nowrap>{{ ($complaint->consumer_id > 0) ? $complaint->consumer->name : $complaint->name }}</td>
                        <td nowrap>{{ $complaint->segment->name }}</td>
                        <td nowrap>
                            {{ $complaint->estimated_closed_at?->format('d-m-y H:i') }}
                            @if ($complaint->status_id != 5)
                                @if ($now > $complaint->estimated_closed_at)
                                    <span class="badge text-bg-danger">{{ "Expired " . $difference }}</span>
                                @else
                                    <span class="badge text-bg-success">{{ "Expires in " . $difference }}</span>
                                @endif
                            @endif
                        </td>
                        <td nowrap>{{ $complaint->closed_at?->format('d-m-Y H:i') }}</td>
                        <td nowrap>{{ $complaint->priority->name }}</td>
                        <td nowrap><x-complaint.status :status="$complaint->status"/></td>
                        <td nowrap>{{ dateFormat($complaint->created_at) }}</td>
                        <td>
                            {{-- list actions --}}
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/' . $complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</x-auth.link></li>
                                    {{--Complaint Status Dropdown--}}
                                    @if ($complaint->status_id == ComplaintStatus::REGISTER->value)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/'.$complaint->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Edit</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/assign/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Assign</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/close/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Close</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/cancel/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Cancel</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == ComplaintStatus::ASSIGN->value and (isAdmin() || auth()->id() == $complaint->assign->assigned_to))
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/inProgress/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;In Progres</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/investigate/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Investigate</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == ComplaintStatus::IN_PROGRESS->value OR $complaint->status_id == ComplaintStatus::INVESTIGATION->value)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/close/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Close</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == ComplaintStatus::CLOSE->value and $complaint->feedback == null)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('calls/feedback/'.$complaint->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Feedback</x-auth.link></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
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
<div>
    {{ $complaints->links('utils.paginator', ['modDiv' => 'complaints-list']) }}
</div>
@include('scripts.link-modal')