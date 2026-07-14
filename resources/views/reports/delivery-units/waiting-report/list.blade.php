@if ($delivery_units->count() > 0)
    <div class="d-flex flex-row justify-content-between pb-3">
        <div>&nbsp;</div>
        <div>
            <button type="button" id="exportBtn" class="btn btn-outline-info btn-md"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
        </div>
    </div>
@endif
<div class="table-responsive">
    <table class="table table-bordered" id="du-report"> 
        <thead class="table-secondary">
            <tr>
                <th rowspan="2">S.No</th>
                <th rowspan="2">Name</th>
                <th rowspan="2">Delivery Manager</th>
                <th rowspan="2">Geo Area</th>
                <th rowspan="2">Department</th>
                <th colspan="5" class="text-center">Consumers Awaiting Action</th>
            </tr>
            <tr>
                @foreach ($status_list as $list)
                    <th class="text-center">{{ $list->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if ($delivery_units->count() > 0)
                @foreach ($delivery_units as $du)    
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a href="{{ url('lms/deliveryUnits/'.$du->id) }}" class="link-modal">{{ $du->name }}</a>&nbsp;</td>
                        <td>{{ $du->duIncharge?->name }}</td>
                        <td>{{ $du->ga->name }}</td>
                        <td>{{ $du->department?->name }}</td>
                        @foreach ($status_list as $list1)
                            @php
                                $status_val = $list1->id - 1;
                            @endphp
                            @if (isset($count[$du->id]) && $count[$du->id]['status_id'] == $status_val)
                                <td class="text-center">
                                    <a href="{{ url('reports/deliveryUnits/getDuTeamsAssigned/'.$du->id) }}?{{ http_build_query(['total' => $count[$du->id]['total'] ?? 0]) }}" class="link-canvas">{{ $count[$du->id]['total'] ?? 0 }}</a>
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr class="alert alert-danger">
                    <td colspan="10">No records found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@include('scripts.export-table', [
    'table' => 'du-report',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'delivery-unit-unassigned',
    'sheet'    => 'Report',
])
@include('scripts.link-modal')
@include('scripts.link-canvas')