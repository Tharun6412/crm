{{-- Areas list body --}}

<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/location/areas') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-1">
            <span class="fw-semibold">({{ $areas->count() }})</span> Records found
        </div>
    </div>
    <div>
        <a href="{{ url('master/location/areas/create') }}" class="btn btn-outline-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a>
    </div>
</div>

@if ($areas->count() > 0)
    <div class="table-responsive" style="min-height: 400px;">
        <table class="table table-bordered table-striped table-hover bg-white">
            <thead class="table-success">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Name</th>
                    <th>CA</th>
                    <th>District</th>
                    <th>GA<x-master.ga-filter class="float-end" /></th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($areas as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->ca->name ?? '' }}</td>
                        <td>{{ $item->ca->district->name ?? '' }}</td>
                        <td>{{ $item->ca->ga->name ?? '' }}</td>
                        <td><x-common.status :status="$item->status"/></td>
                        <td>
                            <a href="{{ url('master/location/areas/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm link-modal"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $areas->links('utils.paginator', ['modDiv' => 'area-list']) }}
    </div>
@else
    <div class="alert alert-info">No records found!</div>
@endif
{{-- Scripts --}}
@include('scripts.link-modal')