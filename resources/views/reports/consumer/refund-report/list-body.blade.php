<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th rowspan="2">S.No</th>
                <th rowspan="2">GA</th>
                <th colspan="{{ $refund_status->count() }}" class="text-center">Refund Status</th>
                <th rowspan="2" class="text-center">Total</th>
            </tr>
            <tr>
                @foreach ($refund_status as $status)
                    <th class="text-center">{{ $status->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php
                $total_status_count = [];
            @endphp   
            @foreach ($geo_areas as $ga)
            <tr> 
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ga->name }}</td>
                @foreach ($refund_status as $status)
                    @php
                        if(!isset($total_status_count[$status->id])) {
                            $total_status_count[$status->id] = 0;
                        }
                        $total_status_count[$status->id] += ($refund_data[$ga->id][$status->id] ?? 0);
                    @endphp
                    <td class="text-center"><a href="{{ url('consumers/refunds') }}?{{ http_build_query(['refund_status' => [$status->id],'geo_area'=> [$ga->id]]) }}" target="_blank">{{ $refund_data[$ga->id][$status->id] ?? 0 }}</a></td>
                @endforeach
                <td class="text-center"><a href="{{ url('consumers/refunds') }}?{{ http_build_query(['geo_area' => [$ga->id]]) }}" target="_blank">{{ $refund_data[$ga->id]['total_count'] ?? 0 }}</a></td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-end">Totals</td>
                @foreach ($refund_status as $status_ref)
                    <td class="text-center"><a href="{{ url('consumers/refunds') }}?{{ http_build_query(['refund_status' => [$status_ref->id]]) }}" target="_blank">{{ $total_status_count[$status_ref->id] }}</a></td>
                @endforeach
                <td class="text-center">{{ array_sum($total_status_count) }}</td>
            </tr>
        </tbody>
    </table>
</div>