<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search.." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/location/subareas') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mb-1">
            <span class="fw-semibold">{{ numberFormat($subareas->total())}}</span>Records Found
        </div>
    </div>
    <div>
        <a href="{{ url('master/location/subareas/create') }}" class="btn btn-outline-success link-modal"><i class="bi bi-plus-lg"></i>Create</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>GA<x-master.ga-filter class="float-end" /></th>
                <th>District
                    @if (request()->has('geo_area'))
                        <x-master.district-filter class="float-end"/> 
                    @endif
                </th>
                <th>CA
                    @if (request()->has('district'))
                        <x-master.charge-area-filter class="float-end"/> 
                    @endif
                </th>
                <th>Area
                    @if (request()->has('charge_area'))
                        <x-master.area-filter class="float-end"/>
                    @endif
                </th>
                <th>Sub Area</th>
                <th>Created Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($subareas->count()>0)
                @foreach ($subareas as $sub)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $sub->area->ca->ga->name ?? ''}}</td>
                        <td>{{ $sub->area->ca->district->name ?? ''}}</td>
                        <td>{{ $sub->area->ca->name ?? ''}}</td>
                        <td>{{ $sub->area->name ?? ''}}</td>
                        <td>{{ $sub->name }}</td>
                        <td>{{ dateFormat($sub->created_at) }}</td>
                        <td>
                            <a class="btn btn-outline-primary link-modal" href="{{ url('master/location/subareas/'.$sub->id.'/edit') }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10"> No Records Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div>
    {{ $subareas->links('utils.paginator',['modDiv' => 'subareas-list']) }}
</div>
@include('scripts.link-modal')