<form name="consumer-aging-reports-search-form" id="consumer-aging-reports-search-form"  action="{{ url('reports/consumer/consumerAgeingReport') }}" method="get">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
        <!-- LEFT: Filters -->
        <div class="d-flex flex-wrap align-items-center gap-1">
            <div>
                <div class="form-control">
                    <span class="mb-0">Segment&nbsp;&nbsp;</span>
                    <div class="w-auto float-end"><x-master.segment-filter /></div>
                </div>
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
            <button type="button" id="exportBtn" class="btn btn-outline-info text-end"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
        </div>
    </div>
</form>
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fs-5 border border-bottom-0 me-2" id="not_active-tab" data-bs-toggle="tab" data-bs-target="#not_active" type="button" role="tab" aria-controls="not_active" aria-selected="true"><i class="bi bi-person-x"></i>&nbsp;Not Activated</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fs-5 border border-bottom-0" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="false"><i class="bi bi-person-check"></i>&nbsp;Activated</button>
    </li>
</ul>
<div class="tab-content border border-top-0 bg-white p-2" id="myTabContent">
    <div class="tab-pane fade show active" id="not_active" role="tabpanel" aria-labelledby="not_active-tab">
         <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped bg-white page-sort">
                <thead class="table-success">
                    <tr>
                        <th nowrap="nowrap">S No.</th>
                        <th>GA Name</th>
                        <th class="text-end"><= 30 Days</th>
                        <th class="text-end">31–60 Days</th>
                        <th class="text-end">61–90 Days</th>
                        <th class="text-end">91–180 Days</th>
                        <th class="text-end">> 180 Days</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse($not_active as $ga)
                        <tr>
                            <td width="1%" nowrap class="text-center">{{ $i++ }}</td>
                            <td>{{ $ga->ga_name }}</td>
                            @foreach([
                                'inactive_upto_30' => '30-',
                                'inactive_31_60' => '31-60',
                                'inactive_61_90' => '61-90',
                                'inactive_91_180' => '91-180',
                                'inactive_gt_180'  => '180+'
                            ] as $field => $range)

                                <td class="text-end">
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
                        <th class="text-end">{{ numberFormat($not_active->sum('inactive_upto_30')) }}</th>
                        <th class="text-end">{{ numberFormat($not_active->sum('inactive_31_60')) }}</th>
                        <th class="text-end">{{ numberFormat($not_active->sum('inactive_61_90')) }}</th>
                        <th class="text-end">{{ numberFormat($not_active->sum('inactive_91_180')) }}</th>
                        <th class="text-end">{{ numberFormat($not_active->sum('inactive_gt_180')) }}</th>
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
                        <th class="text-end"><= 30 Days</th>
                        <th class="text-end">31–60 Days</th>
                        <th class="text-end">61–90 Days</th>
                        <th class="text-end">91–180 Days</th>
                        <th class="text-end">> 180 Days</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @forelse($active as $ga)
                        <tr>
                            <td width="1%" nowrap class="text-center">{{ $i++ }}</td>
                            <td>{{ $ga->ga_name }}</td>
                            @foreach([
                                'active_upto_30' => '30-',
                                'active_31_60' => '31-60',
                                'active_61_90' => '61-90',
                                'active_91_180' => '91-180',
                                'active_gt_180'  => '180+'
                            ] as $field => $range)
    
                                <td class="text-end">
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
                        <th class="text-end">{{ numberFormat($active->sum('active_upto_30')) }}</th>
                        <th class="text-end">{{ numberFormat($active->sum('active_31_60')) }}</th>
                        <th class="text-end">{{ numberFormat($active->sum('active_61_90')) }}</th>
                        <th class="text-end">{{ numberFormat($active->sum('active_91_180')) }}</th>
                        <th class="text-end">{{ numberFormat($active->sum('active_gt_180')) }}</th>
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