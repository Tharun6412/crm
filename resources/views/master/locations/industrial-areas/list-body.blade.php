{{-- Industrial Areas list body --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/location/industrial-areas') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-2">
           <span class="fw-semibold">({{ $industrial_areas->total() }})</span> Records found
        </div>
    </div>
    <div>
        <a href="{{ url('master/location/industrial-areas/create') }}" class="btn btn-outline-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a>
    </div>
</div>
@if ($industrial_areas->count() > 0)
    <div class="table-responsive mt-2" style="min-height: 300px;">
        <table class="table table-bordered table-bordared bg-white table-striped">
            <thead class="table-success">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Name</th>
                    <th>GA<x-master.ga-filter class="float-end" /></th>
                    <th>Added Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($industrial_areas as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->ga->name ?? '' }}</td>
                        <td>{{ $item->created_at?->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ url('master/location/industrial-areas/' . $item->id . '/edit') }}" class="link-modal fs-sm btn btn-outline-info btn-sm"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $industrial_areas->links('utils.paginator', ['modDiv' => 'ia-list']) }}
    </div>
@else
    <div class="alert alert-info">No records found!</div>
@endif
@include('scripts.link-modal')