{{-- Consumer conversions with status --}}

{{-- Dsiplay --}}
<div class="mb-2 text-end">
    <!-- Export -->
    <button type="button" id="exportBtn" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
</div>
<div class="table-responsive mt-2">
    <table class="table table-bordered table-hover align-middle table-striped" id="conversions_table">
        <thead class="align-middle">
            <tr>
                <th rowspan="2" class="table-primary">GA</th>
                <th colspan="3" class="text-center table-primary">Meter Upgrades</th>
                <th colspan="3" class="text-center table-success">Reconnections</th>
            </tr>
            <tr>
                <th class="text-end bg-primary bg-opacity-50">Balance</th>
                <th class="text-end bg-primary bg-opacity-50">Cumulative</th>
                <th class="text-end bg-primary bg-opacity-50">Upgraded</th>
                <th class="text-end bg-success bg-opacity-50">Balance</th>
                <th class="text-end bg-success bg-opacity-50">Cumulative</th>
                <th class="text-end bg-success bg-opacity-50">Reconnected</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($geo_areas as $ga)
                <tr>
                    <td>{{ $ga->name }}</td>
                    <td class="text-end">
                        <a href="{{ url('consumers') }}?{{ http_build_query([
                            'geo_area'=> [$ga->id], 
                            'cns_status' => [\App\Enums\ConsumerStatus::ACTIVATE->value], 
                            'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                            'segments' => [request()->conv_segment_id],
                        ]) }}" target="_blank">
                            {{ numberFormat($conversion_balance[$ga->id] ?? 0) }}
                        </a>
                    </td>
                    <td class="text-end">{{ numberFormat($conversion_cumulative[$ga->id] ?? 0) }}</td>
                    <td class="text-end">
                        <a href="{{ url('reports/consumer/conversions/prepaidConsumers') }}?{{ http_build_query([
                            'ga_id' => $ga->id,
                            'ga_name' => $ga->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'conv_segment_id' => request()->conv_segment_id,
                        ]) }}" class="link-modal">
                            {{ numberFormat($conversion_between[$ga->id] ?? 0) }}
                        </a>
                    </td>
                    <td class="text-end">
                        <a href="{{ url('consumers') }}?{{ http_build_query([
                            'geo_area'=> [$ga->id], 
                            'cns_status' => [\App\Enums\ConsumerStatus::TD->value],
                            'segments' => [request()->conv_segment_id],
                        ]) }}" target="_blank">
                            {{ numberFormat($recon_balance[$ga->id] ?? 0) }}
                        </a>
                    </td>
                    <td class="text-end">{{ numberFormat($recon_cumulative[$ga->id] ?? 0) }}</td>
                    <td class="text-end">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                            'status_id' => \App\Enums\ConsumerStatus::RECONNECT->value, 
                            'ga_id' => $ga->id,
                            'ga_name' => $ga->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'segment_id' => request()->segment_id,
                        ]) }}" class="link-modal">
                            {{ numberFormat($recon_between[$ga->id] ?? 0) }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="fw-semibold bg-info-subtle bg-opacity-10">
                <td class="text-end">Totals</td>
                <td class="text-end">{{ numberFormat(array_sum($conversion_balance)) }}</td>
                <td class="text-end">{{ numberFormat(array_sum($conversion_cumulative)) }}</td>
                <td class="text-end">
                    {{-- <a href="{{ url('reports/consumer/conversions/prepaidConsumers') }}?{{ http_build_query([
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'conv_segment_id' => request()->conv_segment_id,
                    ]) }}" class="link-modal">
                    </a> --}}
                    {{ numberFormat(array_sum($conversion_between)) }}
                </td>
                <td class="text-end">{{ numberFormat(array_sum($recon_balance)) }}</td>
                <td class="text-end">{{ numberFormat(array_sum($recon_cumulative)) }}</td>
                <td class="text-end">
                    {{-- <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::RECONNECT->value,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'segment_id' => request()->segment_id,
                    ]) }}" class="link-modal">
                    </a> --}}
                    {{ numberFormat(array_sum($recon_between)) }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
@include('scripts.link-modal')
@include('scripts.export-table', [
    'table' => 'conversions_table',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'conversions_reconnections_report',
    'sheet'    => 'Report',
])