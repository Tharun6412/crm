{{-- Meter Change list body --}}

{{-- Search form --}}
<div class="row gx-1 mb-1">
    <div class="col-auto">
        <div class="input-group">
            <span class="input-group-text" id="search-key">Search</span>
            <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}" placeholder="Enter CRN/Meter No.">
        </div>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
    </div>
    <div class="col-auto">
        <a href="{{ url('consumers/meterChange') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
    </div>
    <div class="col-auto">
       <div class="mt-2">({{ $meterChange->total() }}) Records found</div>
    </div>
</div>
{{-- Consumers Meter Change list --}}
<div class="table-responsive mt-2">
    <table class="table table-bordered table-hover align-middle">
        <thead class="table-success align-middle">
            <tr>
                <th width="1%" nowrap>S.No</th>
                <th>Consumer Number</th>
                <th class="text-end">Old Meter Number</th>
                <th class="text-end">Old Meter Consumption</th>
                <th class="text-end">New Meter Number</th>
                <th nowrap>Request Date</th>
                <th nowrap>Release Date</th>
                <th>Status</th>
                <th nowrap>Added By</th>
                <th nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($meterChange->count() > 0)
            @php
                $i = (($meterChange->currentPage() - 1) * $meterChange->perPage())+1;
            @endphp
                @foreach ($meterChange as $change)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td><a href="{{ url('consumers/'.$change->consumer_id) }}" target="_blank">{{ $change->consumer->crn }}</a></td>
                        <td class="text-end">{{ $change->meter?->meter_no }}</td>
                        <td class="text-end">{{ $change->consumption }}</td>
                        <td class="text-end">{{ $change->newMeter?->meter_no }}</td>
                        <td>{{ $change?->request_date?->format('d-m-Y') }}</td>
                        <td>{{ $change?->replace_date?->format('d-m-Y') }}</td>
                        <td>{{ $change->status_id == 1 ? "Pending" : "Completed" }}</td>
                        <td>{{ $change->createdBy?->name }}</td>
                        <td><a class="btn btn-primary btn-sm d-flex link-modal" href="{{ url('consumers/meterChange/'.$change->id) }}"><i class="bi bi-box-arrow-up-right"></i>&nbsp;View</a></td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11">
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