{{-- Charge Areas list body --}}

<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control form-control-sm" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/location/charge-areas') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $charge_areas->total() }}) Records found
        </div>
    </div>
    <div>
        <a href="{{ url('master/location/charge-areas/create') }}" class="btn btn-sm btn-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a>
    </div>
</div>
@if ($charge_areas->count() > 0)
    <div class="table-responsive" style="min-height: 300px;">
        <table class="table table-bordered table-primary">
            <thead class="table-primary">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>District</th>
                    <th>GA<x-master.ga-filter class="float-end" /></th>
                    <th>Areas Count</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($charge_areas as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->district->name ?? '' }}</td>
                        <td>{{ $item->ga->name ?? '' }}</td>
                        <td>{{ $item->areas->count() ?? 0 }}</td>
                        <td><x-common.status :status="$item->status"/></td>
                        <td>
                            <a href="{{ url('master/location/charge-areas/' . $item->id . '/edit') }}" class="link-modal fs-sm"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $charge_areas->links('utils.paginator', ['modDiv' => 'ca-list']) }}
    </div>
@else
    <div class="alert alert-info">No records found!</div>
@endif
@include('scripts.link-modal')