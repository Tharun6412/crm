{{-- SD Report list body Between Dates Based on GA --}}
@php
    // Total counts by scheme
    $consumer_count = [];
    $total_count = [];
@endphp
{{-- <div>
    <!-- Export -->
    <button type="button" id="exportBtn" class="btn btn-outline-info text-end"><i class="bi bi-download"></i>&nbsp;Export</button>
</div> --}}
<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped" id="monthly-sale">
        <thead class="table-success align-middle">
            <tr>
                <th width="1%" nowrap rowspan="2">S No</th>
                <th rowspan="2">GA Name</th>
                <th rowspan="2">GA Code</th>
                @if (($prepaid_schemes->count() > 0))
                    <th colspan="{{ ($prepaid_schemes->count() ?? 0) }}" class="text-center">Prepaid Schemes</th>
                @endif
                @if (($postpaid_schemes->count() > 0))
                    <th colspan="{{ ($postpaid_schemes->count() ?? 0) }}" class="text-center">Postpaid Schemes</th>
                @endif
                <th class="text-end" rowspan="2">Total Consumers</th>
                <th class="text-end" rowspan="2">Total Deposit</th>
                <th class="text-end" rowspan="2">Paid Deposit</th>
                <th class="text-end" rowspan="2">Balance Deposit</th>
            </tr>
            <tr>
                @if ($prepaid_schemes)
                    @foreach ($prepaid_schemes as $scheme)
                            @php
                                // print_r($scheme->toArray());
                                $consumer_count[$scheme->id] = 0;
                            @endphp
                            <th class="text-end"><button type="button" class="btn btn-outline-primary" data-bs-toggle="popover"  data-bs-trigger="hover focus" data-bs-placement="top" data-bs-title="{{ $scheme->name }}({{ $scheme->code }})" data-bs-content="{{ 'Security : '.$scheme->security }}<br/>{{ 'Consumption: '.$scheme->consumption }}<br/>{{ 'Total Deposit: '.$scheme->total_deposit }}">{{ $scheme->code }}</button></th>
                    @endforeach
                @endif
                @if ($postpaid_schemes)
                    @foreach ($postpaid_schemes as $scheme)
                            @php
                                // print_r($scheme->toArray());
                                $consumer_count[$scheme->id] = 0;
                            @endphp
                            <th class="text-end"><button type="button" class="btn btn-outline-primary" data-bs-toggle="popover"  data-bs-trigger="hover focus" data-bs-placement="top" data-bs-title="{{ $scheme->name }}({{ $scheme->code }})" data-bs-content="{{ 'Security : '.$scheme->security }}<br/>{{ 'Consumption: '.$scheme->consumption }}<br/>{{ 'Total Deposit: '.$scheme->total_deposit }}">{{ $scheme->code }}</button></th>
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $total_dep = $paid_dep = $balance_dep = 0;
            @endphp
            @foreach ($geo_areas as $ga)
                @php
                    // Append to URL
                    $append_data = 'date_from='.request()->date_from.'&date_to='.request()->date_to.'&geo_area[]='.$ga->id.(request()->has('connection_type_id') 
        ? '&'.http_build_query(['connection_type_id' => request()->connection_type_id]) 
        : '')
    .(request()->has('segments') 
        ? '&'.http_build_query(['segments' => request()->segments]) 
        : '');
                    $ga_total_dep = $ga_paid_dep = $ga_balance_dep = 0;
                    $total_count[$ga->id] = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td nowrap>{{ $ga->name }}</td>
                    <td nowrap><i class="bi bi-geo text-secondary"></i> {{ $ga->code }}</td>
                    @foreach ($prepaid_schemes as $scheme_data)
                        @php
                            $consumer_count[$scheme_data->id] += ($sd_amount_by_ga[$ga->id][$scheme_data->id]['count'] ?? 0);
                            $total_count[$ga->id] += ($sd_amount_by_ga[$ga->id][$scheme_data->id]['count'] ?? 0);
                            // Totals
                            $total = $sd_amount_by_ga[$ga->id][$scheme_data->id]['total_deposit'] ?? 0;
                            $paid = $sd_amount_by_ga[$ga->id][$scheme_data->id]['paid_deposit'] ?? 0;
                            $balance = $sd_amount_by_ga[$ga->id][$scheme_data->id]['balance'] ?? 0;
                            // SUM of totals based on GA
                            $ga_total_dep += $total;
                            $ga_paid_dep += $paid;
                            $ga_balance_dep += $balance;
                        @endphp
                        <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}&scheme[]={{ $scheme_data->id }}&status={{ request()->status }}&connection_type_id[]={{ $scheme_data->connection_type_id }}">{{ $sd_amount_by_ga[$ga->id][$scheme_data->id]['count'] ?? 0 }}</a></td>
                    @endforeach
                    @foreach ($postpaid_schemes as $scheme_data)
                        @php
                            $consumer_count[$scheme_data->id] += ($sd_amount_by_ga[$ga->id][$scheme_data->id]['count'] ?? 0);
                            $total_count[$ga->id] += ($sd_amount_by_ga[$ga->id][$scheme_data->id]['count'] ?? 0);
                            // Totals
                            $total = $sd_amount_by_ga[$ga->id][$scheme_data->id]['total_deposit'] ?? 0;
                            $paid = $sd_amount_by_ga[$ga->id][$scheme_data->id]['paid_deposit'] ?? 0;
                            $balance = $sd_amount_by_ga[$ga->id][$scheme_data->id]['balance'] ?? 0;
                            // SUM of totals based on GA
                            $ga_total_dep += $total;
                            $ga_paid_dep += $paid;
                            $ga_balance_dep += $balance;
                        @endphp
                        <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}&scheme[]={{ $scheme_data->id }}&status={{ request()->status }}&connection_type_id[]={{ $scheme_data->connection_type_id }}">{{ $sd_amount_by_ga[$ga->id][$scheme_data->id]['count'] ?? 0 }}</a></td>
                    @endforeach
                    <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}&status={{ request()->status }}" target="_blank">{{ numberFormat($total_count[$ga->id] ?? 0) }}</a></td>
                    <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}&status={{ request()->status }}" target="_blank">{{ numberFormat($ga_total_dep ?? 0) }}</a></td>
                    <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}&status={{ request()->status }}" target="_blank">{{ numberFormat($ga_paid_dep ?? 0) }}</a></td>
                    <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}&status={{ request()->status }}" target="_blank">{{ numberFormat($ga_balance_dep ?? 0) }}</a></td>
                </tr>
                @php
                    $total_dep += $ga_total_dep;
                    $paid_dep += $ga_paid_dep;
                    $balance_dep += $ga_balance_dep;
                @endphp
            @endforeach
            <tr class="fw-semibold table-warning">
                <td colspan="3" class="text-end">Totals</td>
                @foreach ($prepaid_schemes as $total_data)
                    <td class="text-end">{{ $consumer_count[$total_data->id] ?? 0 }}</td>
                @endforeach
                @foreach ($postpaid_schemes as $total_data)
                    <td class="text-end">{{ $consumer_count[$total_data->id] ?? 0 }}</td>
                @endforeach
                <td class="text-end">{{ array_sum($consumer_count) }}</td>
                <td class="text-end">{{ numberFormat($total_dep) }}</td>
                <td class="text-end">{{ numberFormat($paid_dep) }}</td>
                <td class="text-end">{{ numberFormat($balance_dep) }}</td>
            </tr>
        </tbody>
    </table>
</div>
@include('scripts.bs-popover')
@include('scripts.export-table', [
    'table' => 'monthly-sale',
    'button' => 'exportSaleBtn',
    'tabBased' => false,
    'filename' => 'sd_report',
    'sheet'    => 'Report',
])