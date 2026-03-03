{{-- SD Report list body Between Dates Based on GA --}}
@php
    // Total counts by scheme
    $consumer_count = [];
    $total_count = [];
@endphp
<div class="table-responsiveaa table-basic">
    <table class="table table-bordered table-hover" id="monthly-sale">
        <thead>
            <tr class="bg-light">
                <th width="1%" nowrap rowspan="2">S No</th>
                <th rowspan="2">GA</th>
                <th colspan="{{ $schemes->count() }}" class="text-center">Scheme Code</th>
                <th class="text-end" rowspan="2">Total Consumers</th>
                <th class="text-end" rowspan="2">Total Deposit</th>
                <th class="text-end" rowspan="2">Paid Deposit</th>
                <th class="text-end" rowspan="2">Balance Deposit</th>
            </tr>
            <tr>
                @foreach ($schemes as $scheme)
                    @php
                        $consumer_count[$scheme->id] = 0;
                    @endphp
                    <th class="text-end" title="{{ $scheme->name }}">{{ $scheme->code }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $total_dep = $paid_dep = $balance_dep = 0;
            @endphp
            @foreach ($geo_areas as $ga)
                @php
                    // Append to URL
                    $append_data = 'date_from='.request()->date_from.'&date_to='.request()->date_to.'&geo_area[]='.$ga->id;
                    $ga_total_dep = $ga_paid_dep = $ga_balance_dep = 0;
                    $total_count[$ga->id] = 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td nowrap>{{ $ga->name }}-{{ $ga->code }}</td>
                    @foreach ($schemes as $scheme_data)
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
                        <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}&scheme[]={{ $scheme_data->id }}&status={{ request()->status }}">{{ $sd_amount_by_ga[$ga->id][$scheme_data->id]['count'] ?? 0 }}</a></td>
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
            <tr class="fw-semibold">
                <td colspan="2" class="text-end">Totals</td>
                @foreach ($schemes as $total_data)
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