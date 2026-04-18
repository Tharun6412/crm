<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search.." value="{{ request()->key }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/location/districts') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            <span class="fw-semibold">({{ $districts->total() }})</span> Records found
        </div>
    </div>
    <div>
        <a href="{{ url('master/location/districts/create') }}" class="btn btn-outline-success link-modal"><i class="bi bi-plus-lg">&nbsp;Create</i></a>
    </div>
</div>
@if ($districts->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white table-striped">
            <thead class="table-success">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>District Code</th>
                    <th>District Name</th>
                    <th>GA<x-master.ga-filter class="float-end"/></th>
                    <th>State</th>
                    <th>Cluster</th>
                    <th>CAs</th>
                    <th>Status
                        @php
                            $status_array = [1 => 'Enable', 0 => 'Disable'];
                        @endphp
                        <x-admin.status-filter name='status' :data='$status_array' />
                    </th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($districts as $item)
                        <tr>
                            <td>{{ ($districts->currentPage()-1)* $districts->perpage()+$loop->iteration }}</td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->ga->name ?? '' }}</td>
                            <td>{{ $item->state->name ?? '' }}</td>
                            <td>{{ $item->cluster->name ?? '' }}</td>
                            <td>{{ $item->cas->count() ?? 0 }}</td>
                            <td><x-common.status :status="$item->status"/></td>
                            <td>
                                <a href="{{ url('master/location/districts/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm link-modal"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
<div>
    {{ $districts->links('utils.paginator', ['modDiv' => 'dist-list']) }}
</div>
@else
<div class="alert alert-info">No Records Found</div>
@endif
@include('scripts.link-modal')