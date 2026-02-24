{{-- Business Types list body --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control form-control-sm" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/location/business-types') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $business_types->count() }}) Records found
        </div>
    </div>
    <div>
        {{-- <a href="{{ url('master/location/business-types/create') }}" class="btn btn-sm btn-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a> --}}
    </div>
</div>
@if ($business_types->count() > 0)
    <div class="table-responsive" style="min-height: 300px;">
        <table class="table table-bordered table-primary">
            <thead class="table-primary">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Added Date</th>
                    {{-- <th>Actions</th> --}}
                </tr>
            </thead>
            <tbody>
                @foreach ($business_types as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->status == 1 ? "Active" : "InActive" }}</td>
                        <td>{{ $item->created_at?->format('d-m-Y') }}</td>
                        {{-- <td>
                            <a href="{{ url('master/location/business-types/' . $item->id . '/edit') }}" class="link-modal fs-sm"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">No records found!</div>
@endif
@include('scripts.link-modal')