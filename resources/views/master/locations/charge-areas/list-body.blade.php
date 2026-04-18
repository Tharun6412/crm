{{-- Charge Areas list body --}}

<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/location/charge-areas') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            <span class="fw-semibold">({{ $charge_areas->total() }})</span> Records found
        </div>
    </div>
    <div>
        <a href="{{ url('master/location/charge-areas/create') }}" class="btn btn-outline-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a>
    </div>
</div>
@if ($charge_areas->count() > 0)
    <div class="table-responsive mt-1" style="min-height: 300px;">
        <table class="table table-bordered table-striped bg-white table-hover">
            <thead class="table-success">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>GA<x-master.ga-filter class="float-end" /></th>
                    <th>District
                        @if (request()->has('geo_area'))
                          <x-master.district-filter class="float-end" />  
                        @endif
                    </th>
                    <th>CA Code</th>
                    <th>CA Name</th>
                    <th>Areas</th>
                    <th>Status
                        @php
                            $status_array=[1 => 'Enable', 0 => 'Disable'];
                        @endphp
                        <x-admin.status-filter name="status" :data="$status_array"/>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($charge_areas as $item)
                    <tr>
                        <td>{{ ($charge_areas->currentPage()-1)* $charge_areas->perpage()+$loop->iteration }}</td>
                        <td>{{ $item->ga->name ?? '' }}</td>
                        <td>{{ $item->district->name ?? '' }}</td>
                        <td>{{ $item->code }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="text-end">{{ $item->areas->count() ?? 0 }}</td>
                        <td><x-common.status :status="$item->status"/></td>
                        <td>
                            <a href="{{ url('master/location/charge-areas/' . $item->id . '/edit') }}" class="btn btn-outline-primary btn-sm link-modal"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $charge_areas->links('utils.paginator', ['modDiv' => 'ca-list']) }}
       {{-- {{ $charge_areas->links('utils.cursor', ['modDiv' => 'ca-list']) }} --}} 
    </div>
@else
    <div class="alert alert-info">No records found!</div>
@endif
@include('scripts.link-modal')