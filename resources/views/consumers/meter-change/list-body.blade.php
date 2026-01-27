{{-- Meter Change list body --}}

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
        <a href="{{ url('consumers/meterChange') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
    </div>
    <div class="col-auto">
        ({{ $meterChange->total() }}) Records found
    </div>
    <div class="col-auto float-end">
        <x-auth.link href="{{ url('consumers/meterChange/2/edit') }}" class="btn btn-success btn-sm link-modal">Create</x-auth.link>
    </div>
</div>
{{-- Consumers Meter Change list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S.No</th>
                <th>Consumer Number</th>
                <th>Old Meter Number</th>
                <th>Old Meter Consumption</th>
                <th>New Meter Number</th>
                <th>Request Date</th>
                <th>Release Date</th>
                <th>Status</th>
                <th>Added By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($meterChange->count() > 0)
            @php
                $i = (($meterChange->currentPage() - 1) * $meterChange->perPage())+1;
            @endphp
                @foreach ($meterChange as $change)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $change->consumer->crn }}</td>
                        <td>{{ $change->meter->meter_no }}</td>
                        <td>{{ $change->consumption }}</td>
                        <td>{{ $change->newMeter->meter_no }}</td>
                        <td>{{ $change?->request_date?->format('d-m-Y') }}</td>
                        <td>{{ $change?->replace_date?->format('d-m-Y') }}</td>
                        <td>{{ $change->status_id == 1 ? "Pending" : "Completed" }}</td>
                        <td>{{ $change?->createdBy->name }}</td>
                        <td><a class="btn btn-info btn-sm link-modal" href="{{ url('consumers/meterChange/'.$change->id) }}">view</a></td>
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
    {{ $meterChange->links('utils.paginator', ['modDiv' => 'meter-change-list']) }}
</div>
@include('scripts.link-modal')