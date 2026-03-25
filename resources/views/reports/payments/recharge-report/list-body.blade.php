<div>
    <!-- Export -->
    <button type="button" id="exportBtn" class="btn btn-outline-info btn-sm text-end"><i class="bi bi-download"></i>&nbsp;Export</button>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover page-sort bg-white table-striped" id="recharge_report">
        <thead class="table-success">
            <tr>
                <th nowrap="nowrap" width="1%">S No.</th>
                <th>GA Name</th>
                <th class="text-end">Recharged Amount</th>
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
                    <td class="text-end">
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
        <tfoot class="table-info">
            <tr>
                <th colspan="2" class="text-end">Total</th>
                <th class="text-end">{{ numberFormat($gaRecharges->sum('recharged_amount'),2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@push('scripts')
    @include('scripts.export-table', [
        'table' => 'recharge_report',
        'button' => 'exportBtn',
        'tabBased' => false,
        'filename' => 'recharge_report',
        'sheet'    => 'Report',
    ])
@endpush