{{-- Consumers list body --}}

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
        ({{ $consumers->total() }}) Records found
    </div>
</div>
{{-- Consumers list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>CRN</th>
                <th>Name</th>
                <th>Segment<x-master.segmentFilter class="float-end" /></th>
                <th>Status<x-consumer.statusFilter class="float-end" /></th>
                <th>GA<x-master.gaFilter class="float-end" /></th>
                <th>Scheme</th>
                <th>Created At<x-master.date-Filter /></th>
                <th width="2%" nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($consumers->count() > 0)
                @foreach ($consumers as $consumer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $consumer->crn }}</td>
                        <td>{{ $consumer->name }}</td>
                        <td>{{ $consumer->segment->name }}</td>
                        <td>
                            <x-consumer.status :status="$consumer->status" />
                        </td>
                        <td>{{ $consumer->ga->name }}</td>
                        <td>{{ $consumer->scheme?->scheme?->name }}</td>
                        <td>{{ dateFormat($consumer->created_at) }}</td>
                        <td>
                            @include('consumers.consumers.list-actions')
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
    {{ $consumers->links('utils.paginator', ['modDiv' => 'consumers-list']) }}
</div>
@include('scripts.link-modal')