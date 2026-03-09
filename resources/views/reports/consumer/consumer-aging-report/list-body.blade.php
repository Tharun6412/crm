<form name="consumer-aging-reports-search-form" id="consumer-aging-reports-search-form"  action="{{ url('reports/consumer/consumerAgeingReport') }}" method="get">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
        <!-- LEFT: Filters -->
        <div class="d-flex flex-wrap align-items-center gap-2">
            <div class="border border-secondary rounded px-2 py-2 d-flex align-items-center gap-1">
                <span class="fw-semibold mb-0">Segment</span>
                <div class="w-auto"><x-master.segment-filter /></div>
            </div>
            <div>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-search"></i>
                </button>
                <a href="{{ url('reports/consumer/consumerAgeingReport') }}" class="btn btn-warning">
                   <i class="bi bi-arrow-clockwise"></i>
                </a>
            </div>
        </div>
        <div>
            <!-- Export -->
            <button type="button" id="exportBtn" class="btn btn-outline-info text-end"><i class="bi bi-download"></i>&nbsp;Export</button>
        </div>
    </div>
</form>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fs-5" id="not_active-tab" data-bs-toggle="tab" data-bs-target="#not_active" type="button" role="tab" aria-controls="not_active" aria-selected="true"><i class="bi bi-x"></i>&nbsp;Not Activated</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fs-5" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="false"><i class="bi bi-check2"></i>&nbsp;Activated</button>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="not_active" role="tabpanel" aria-labelledby="not_active-tab">
         <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped bg-white page-sort">
                <thead class="table-success">
                    <tr>
                        <th nowrap="nowrap">S No.</th>
                        <th>GA Name</th>
                        <th>< 90 Days</th>
                        <th>90–120 Days</th>
                        <th>> 120 Days</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse($not_active as $ga)
                        <tr>
                            <td width="1%" nowrap>{{ $i++ }}</td>
                            <td>{{ $ga->ga_name }}</td>
                            @foreach([
                                'inactive_upto_90' => '90-',
                                'inactive_90_120' => '90-120',
                                'inactive_gt_120'  => '120+'
                            ] as $field => $range)

                                <td>
                                    <a href="{{ url('reports/consumer/consumerAgeingReport/consumersList') }}?{{ http_build_query([
                                        'geo_area' => [$ga->ga_id],
                                        'range' => $range,
                                        'connection_type_id' => request()->connection_type,
                                        'segments' => request()->segment_id ?? [],
                                        'cns_status' => [\App\Enums\ConsumerStatus::REGISTER->value, \App\Enums\ConsumerStatus::ACCEPT->value, \App\Enums\ConsumerStatus::EXECUTE->value, \App\Enums\ConsumerStatus::HSC->value, ],
                                        'type' => 1,
                                        ]) }}" class="aging-link" target="_blank">{{ numberFormat($ga->$field) }}
                                    </a>
                                </td>

                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No GA Records Found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <th colspan="2" class="text-end">Total</th>
                        <th>{{ numberFormat($not_active->sum('inactive_upto_90')) }}</th>
                        <th>{{ numberFormat($not_active->sum('inactive_90_120')) }}</th>
                        <th>{{ numberFormat($not_active->sum('inactive_gt_120')) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="tab-pane fade" id="active" role="tabpanel" aria-labelledby="active-tab">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped bg-white page-sort">
                <thead class="table-success">
                    <tr>
                        <th nowrap="nowrap">S No.</th>
                        <th>GA Name</th>
                        <th>< 90 Days</th>
                        <th>90–120 Days</th>
                        <th>> 120 Days</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse($active as $ga)
                        <tr>
                            <td width="1%" nowrap>{{ $i++ }}</td>
                            <td>{{ $ga->ga_name }}</td>
                            @foreach([
                                'active_upto_90' => '90-',
                                'active_90_120' => '90-120',
                                'active_gt_120'  => '120+'
                            ] as $field => $range)
    
                                <td>
                                    <a href="{{ url('reports/consumer/consumerAgeingReport/consumersList') }}?{{ http_build_query([
                                        'geo_area' => [$ga->ga_id],
                                        'range' => $range,
                                        'connection_type_id' => request()->connection_type,
                                        'segments' => request()->segment_id ?? [],
                                        'cns_status' => [\App\Enums\ConsumerStatus::ACTIVATE->value],
                                        'type'=> 2,
                                        ]) }}" class="aging-link" target="_blank">{{ numberFormat($ga->$field) }}
                                    </a>
                                </td>
    
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No GA Records Found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-info">
                        <th colspan="2" class="text-end">Total</th>
                        <th>{{ numberFormat($active->sum('active_upto_90')) }}</th>
                        <th>{{ numberFormat($active->sum('active_90_120')) }}</th>
                        <th>{{ numberFormat($active->sum('active_gt_120')) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@push('scripts')
    @include('scripts.export-table', [
            'button' => 'exportBtn',
            'tabBased' => true,
            'filename' => 'Consumers_Report',
            'sheet'    => 'Report',
        ])
@endpush