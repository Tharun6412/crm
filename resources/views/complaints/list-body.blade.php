{{-- Complaint list body --}}
{{-- Search form --}}
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
        <a href="{{ url('complaints') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
    </div>
    <div class="col-auto">
        ({{ $complaints->total() }}) Records found
    </div>
    <div class="col-auto float-end">
        <x-auth.link href="{{ url('complaints/create/1') }}" class="btn btn-success btn-sm link-modal">Create</x-auth.link>
    </div>
</div>
{{-- Consumers list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover">
        <thead class="table-primary">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>Consumer Number</th>
                <th>Complaint Number</th>
                <th>Priority</th>
                <th>Segment</th>
                <th>Estimated Close Date</th>
                <th>Status</th>
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
                            $difference = ceil($now->diffInDays(\Carbon\Carbon::parse($complaint->estimated_closed_at)))." Days";
                        }else {
                            $difference = numberFormat(abs($now->diffInHours(\Carbon\Carbon::parse($complaint->estimated_closed_at))), 2)." Hours";
                        }
                    @endphp
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td><x-auth.link href="{{ url('consumers/' . $complaint->consumer_id) }}" target="_blank">{{ $complaint->consumer->crn }}</x-auth.link></td>
                        <td><x-auth.link href="{{ url('complaints/'.$complaint->id) }}" class="link-modal">{{ $complaint->code }}</x-auth.link></td>
                        <td>{{ $complaint->priority->name }}</td>
                        <td>{{ $complaint->segment->name }}</td>
                        <td>{{ $complaint->estimated_closed_at }}
                            @if ($now > $complaint->estimated_closed_at)
                                <small class="bg bg-danger-subtle">(&nbsp;{{ "Expired in ".$difference }}&nbsp;)</small>
                            @else
                                <small class="bg bg-success-subtle">(&nbsp;{{ "Expires in ".$difference }}&nbsp;)</small>
                            @endif
                        </td>
                        <td>{{ $complaint->status->name }}</td>
                        <td>{{ dateFormat($complaint->created_at) }}</td>
                        <td>
                            {{-- Consumers list actions --}}
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><x-auth.link class="dropdown-item link-modal" href="{{ url('complaints/' . $complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</x-auth.link></li>
                                    {{--Complaint Status Dropdown--}}
                                    @if ($complaint->status_id == 1)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('complaints/'.$complaint->id.'/edit') }}"><i class="bi bi-chevron-right"></i>&nbsp;Edit</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('complaints/assign/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Assign</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('complaints/close/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Close</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('complaints/cancel/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Cancel</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == 2 and (auth()->id() == $complaint->assign->assigned_to))
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('complaints/inProgress/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;In Progres</x-auth.link></li>
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('complaints/investigate/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Investigate</x-auth.link></li>
                                    @endif
                                    @if ($complaint->status_id == 3)
                                        <li><x-auth.link class="dropdown-item link-modal" href="{{ url('complaints/close/'.$complaint->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Close</x-auth.link></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
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
    {{ $complaints->links('utils.paginator', ['modDiv' => 'complaints-list']) }}
</div>
@include('scripts.link-modal')