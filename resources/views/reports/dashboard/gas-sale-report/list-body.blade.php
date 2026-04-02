<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped bg-white page-sort" id="gas-sale-report">
        <thead class="table-success align-middle">
            <tr>
                <th rowspan="2" width="1%" nowrap="nowrap">S No.</th>
                <th rowspan="2">GA Name</th>
                <th colspan="4" class="text-center bg-warning-subtle">Sale in SCMs</th>
            </tr>
            <tr class="text-end">
                <th>Domestic Prepaid</th>
                <th>Domestic Postpaid</th>
                <th>Commercial Prepaid</th>
                <th>Commercial Postpaid</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $total_dom_pre = $total_dom_post = $total_com_post = $total_com_pre = 0;
            @endphp
            @forelse($geo_areas as $ga)
                @php
                    $gas_consumption = $gas_sale_array[$ga->id] ?? null;
                    // Domestic Segment
                    $dom_pre = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::PREPAID->value] ?? 0;
                    $dom_post = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::POSTPAID->value] ?? 0;
                    $com_pre = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::PREPAID->value] ?? 0;
                    $com_post = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::POSTPAID->value] ?? 0;
                    // accumulate totals
                    $total_dom_pre += $dom_pre;
                    $total_dom_post += $dom_post;
                    $total_com_pre += $com_pre;
                    $total_com_post += $com_post;
                @endphp
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $ga->name }}</td>
                    <td class="text-end">{{ numberFormat($dom_pre,2) }}</td>
                    <td class="text-end">{{ numberFormat($dom_post,2) }}</td>
                    <td class="text-end">{{ numberFormat($com_pre,2) }}</td>
                    <td class="text-end">{{ numberFormat($com_post,2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No GA Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="table-info fw-bold text-end">
                <th colspan="2" class="text-end">Total</th>
                <th>{{ numberFormat($total_dom_pre, 2) }}</th>
                <th>{{ numberFormat($total_dom_post, 2) }}</th>
                <th>{{ numberFormat($total_com_pre, 2) }}</th>
                <th>{{ numberFormat($total_com_post, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
@include('scripts.export-table', [
    'table' => 'gas-sale-report',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'gas_sale_report',
    'sheet'    => 'Report',
])