{{-- Firm Types list body --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control form-control-sm" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('master/consumer/firmTypes') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $firm_types->count() }}) Records found
        </div>
    </div>
</div>
@if ($firm_types->count() > 0)
    <div class="table-responsive" style="min-height: 300px;">
        <table class="table table-bordered table-primary">
            <thead class="table-primary">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Added Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($firm_types as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->type == 1 ? "Commercial" : "Industrial" }}</td>
                        <td>{{ $item->status == 1 ? "Active" : "InActive" }}</td>
                        <td>{{ $item->created_at?->format('d-m-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="alert alert-info">No records found!</div>
@endif
@include('scripts.link-modal')