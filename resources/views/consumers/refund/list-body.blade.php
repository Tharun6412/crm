{{-- Refund list body --}}

{{-- Search form --}}
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
        <a href="{{ url('consumers') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
    </div>
    <div class="col-auto">
        ({{ $refunds_list->total() }}) Records found
    </div>
</div>
{{-- Consumers list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>Consumer Number</th>
                <th>Request Number</th>
                <th>Status</th>
                <th>Created At</th>
                <th width="2%" nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($refunds_list->count() > 0)
                @foreach ($refunds_list as $list)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><x-auth.link href="{{ url('consumers/'.$list->consumer_id) }}" target="_blank">{{ $list->consumer->crn }}</x-auth.link></td>
                        <td><x-auth.link href="{{ url('consumers/refunds/'.$list->id) }}" class="link-modal">{{ $list->request_no }}</x-auth.link></td>
                        <td>{{ $list->status?->name }}</td>
                        <td>{{ dateFormat($list->created_at) }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ url('consumers/refunds/' . $list->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</a></li>
                                    @if ($list->status_id == 1)
                                        <li><a class="dropdown-item link-modal" href="{{ url('consumers/refunds/process/' . $list->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Process</a></li>
                                    @endif
                                    @if ($list->status_id == 2)
                                        <li><a class="dropdown-item link-modal" href="{{ url('consumers/refunds/approve/' . $list->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Approve</a></li>
                                    @endif
                                    @if ($list->status_id == 3)
                                        <li><a class="dropdown-item link-modal" href="{{ url('consumers/refunds/close/' . $list->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;Close</a></li>
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
    {{ $refunds_list->links('utils.paginator', ['modDiv' => 'refunds-list']) }}
</div>
@include('scripts.link-modal')