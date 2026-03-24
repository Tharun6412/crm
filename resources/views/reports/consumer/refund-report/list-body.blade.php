<div class="table-responsive">
    <table class="table table-bordered table-striped bg-white">
        <thead class="table-success align-middle">
            <tr>
                <th rowspan="2" width="1%">S.No</th>
                <th rowspan="2">GA</th>
                <th colspan="{{ $refund_status->count() }}" class="text-center">Refund Status</th>
                <th rowspan="2" class="text-end">Total</th>
                <th rowspan="2" class="text-end">Refund Amount&nbsp;(&#8377;)</th>
                {{-- <th rowspan="2" class="text-end">Refunded&nbsp;(&#8377;)</th> --}}
            </tr>
            <tr>
                @foreach ($refund_status as $status)
                    <th class="text-end">{{ $status->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php 
                $total_status_count = [];
                $tot_amt = $ref_amt = 0;
            @endphp   
            @foreach ($geo_areas as $ga)
            <tr> 
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $ga->name }}</td>
                @foreach ($refund_status as $status)
                    @php
                        if(!isset($total_status_count[$status->id])) {
                            $total_status_count[$status->id] = 0;
                        }
                        $tot_amt += ($refund_data[$ga->id][$status->id]['amount'] ?? 0);
                        $total_status_count[$status->id] += ($refund_data[$ga->id][$status->id]['count'] ?? 0);
                    @endphp
                    <td class="text-end"><a href="{{ url('consumers/refunds') }}?{{ http_build_query(['refund_status' => [$status->id],'geo_area'=> [$ga->id]]) }}" target="_blank">{{ $refund_data[$ga->id][$status->id]['count'] ?? 0 }}</a></td>
                @endforeach
                <td class="text-end"><a href="{{ url('consumers/refunds') }}?{{ http_build_query(['geo_area' => [$ga->id]]) }}" target="_blank">{{ $refund_data[$ga->id]['total_count'] ?? 0 }}</a></td>
                <td class="text-end"><a href="{{ url('consumers/refunds') }}?{{ http_build_query(['geo_area' => [$ga->id]]) }}" target="_blank">{{ $refund_data[$ga->id][$status->id]['amount'] ?? 0 }}</a></td>
            </tr>
            @endforeach
            <tr class="bg-info-subtle fw-semibold">
                <td colspan="2" class="text-end">Totals</td>
                @foreach ($refund_status as $status_ref)
                    <td class="text-end"><a href="{{ url('consumers/refunds') }}?{{ http_build_query(['refund_status' => [$status_ref->id]]) }}" target="_blank">{{ $total_status_count[$status_ref->id] }}</a></td>
                @endforeach
                <td class="text-end">{{ array_sum($total_status_count) }}</td>
                <td class="text-end"><a href="{{ url('consumers/refunds') }}" target="_blank">{{ numberFormat($tot_amt, 2) }}</a></td>
            </tr>
        </tbody>
    </table>
</div>