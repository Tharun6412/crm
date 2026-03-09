<div class="table-responsive">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th nowrap="nowrap" width="1%">S No.</th>
                <th>GA Name</th>
                <th>Recharged Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $commonParams = [
                    'date_from'    => $date_from->format('d-m-Y'),
                    'date_to'      => $date_to->format('d-m-Y'),
                ];
            @endphp
            @forelse($gaRecharges as $ga)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $ga->ga_name }}</td>
                    <td>
                        <a href="{{ url('reports/consumer/recharge/List') }}?{{ http_build_query(array_merge($commonParams, [
                            'geo_area' => [$ga->ga_id],
                            'segments' => [\App\Enums\SegmentType::DOMESTIC->value],
                            ])) }}" class="aging-link" target="_blank">{{ numberFormat($ga->recharged_amount,2) }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No GA Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th>{{ numberFormat($gaRecharges->sum('recharged_amount'),2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>