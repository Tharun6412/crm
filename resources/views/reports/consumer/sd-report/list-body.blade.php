{{-- SD Report list body Between Dates Based on GA --}}
<div class="table-responsiveaa table-basic">
    <table class="table table-bordered table-hover" id="monthly-sale">
        <thead>
            <tr class="bg-light">
                <th with="1%" nowrap>S No</th>
                <th>GA</th>
                <th class="text-end">Total Deposit</th>
                <th class="text-end">Paid Deposit</th>
                <th class="text-end">Balance Deposit</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_dep = $paid_dep = $balance_dep = 0;
            @endphp
            @foreach ($geo_areas as $ga)
                @php
                    $total_dep += ($sd_amount_by_ga[$ga->id]['total_deposit'] ?? 0);
                    $paid_dep += ($sd_amount_by_ga[$ga->id]['paid_deposit'] ?? 0);
                    $balance_dep += ($sd_amount_by_ga[$ga->id]['balance'] ?? 0);
                    // Append to URL
                    $append_data = 'date_from='.request()->date_from.'&date_to='.request()->date_to.'&geo_area[]='.$ga->id;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td nowrap>{{ $ga->name }}-{{ $ga->code }}</td>
                    <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}">{{ numberFormat($sd_amount_by_ga[$ga->id]['total_deposit'] ?? 0) }}</a></td>
                    <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}">{{ numberFormat($sd_amount_by_ga[$ga->id]['paid_deposit'] ?? 0) }}</a></td>
                    <td class="text-end"><a href="{{ url('reports/consumer/sdDetails') }}?{{ $append_data }}">{{ numberFormat($sd_amount_by_ga[$ga->id]['balance'] ?? 0) }}</a></td>
                </tr>
            @endforeach
            <tr class="fw-semibold">
                <td colspan="2" class="text-end">Totals</td>
                <td class="text-end">{{ numberFormat($total_dep) }}</td>
                <td class="text-end">{{ numberFormat($paid_dep) }}</td>
                <td class="text-end">{{ numberFormat($balance_dep) }}</td>
            </tr>
        </tbody>
    </table>
</div>