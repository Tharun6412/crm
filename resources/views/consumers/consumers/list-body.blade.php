{{-- Consumers list --}}

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>CRN</th>
                <th>Name</th>
                <th>Segment</th>
                <th>Status</th>
                <th>GA</th>
                <th>Scheme</th>
                <th>Created At</th>
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
                        <td>{{ $consumer->status->name }}</td>
                        <td>{{ $consumer->ga->name }}</td>
                        <td></td>
                        <td>{{ date('d.m.Y') }}</td>
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