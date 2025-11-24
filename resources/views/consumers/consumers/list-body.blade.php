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
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-chevron-right"></i>&nbsp;Action1</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-chevron-right"></i>&nbsp;Action2</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="bi bi-chevron-right"></i>&nbsp;Action3</a></li>
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
    {{ $consumers->links('utils.paginator', ['modDiv' => 'consumers-list']) }}
</div>