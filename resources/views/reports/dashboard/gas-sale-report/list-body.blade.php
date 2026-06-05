<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped bg-white page-sort" id="gas-sale-report">
        <thead class="table-success align-middle">
            <tr>
                <th rowspan="3" width="1%" nowrap="nowrap">S No.</th>
                <th rowspan="3">GA Name</th>
                <th colspan="4" class="text-center">Counts</th>
                <th colspan="4" class="text-center">Sale in SCMs</th>
                <th colspan="12" class="text-center">Sale in Rs</th>
            </tr>
            <tr>
                <th colspan="2" class="text-center bg-success bg-opacity-75" nowrap>Domestic</th>
                <th colspan="2" class="text-center bg-success bg-opacity-75" nowrap>Commercial</th>
                <th colspan="2" class="text-center bg-success bg-opacity-75" nowrap>Domestic</th>
                <th colspan="2" class="text-center bg-success bg-opacity-75" nowrap>Commercial</th>
                <th colspan="3" class="text-center bg-success bg-opacity-75">Domestic Prepaid</th>
                <th colspan="3" class="text-center bg-success bg-opacity-75">Domestic Postpaid</th>
                <th colspan="3" class="text-center bg-success bg-opacity-75">Commercial Prepaid</th>
                <th colspan="3" class="text-center bg-success bg-opacity-75">Commercial Postpaid</th>
            </tr>
            <tr>
                @for ($i = 1; $i < 3; $i++)
                    <th class="text-end" nowrap>Prepid</th>
                    <th class="text-end" nowrap>Postpaid</th>
                @endfor
                @for ($i = 1; $i < 3; $i++)
                    <th class="text-end" nowrap>Prepid</th>
                    <th class="text-end" nowrap>Postpaid</th>
                @endfor
                @for ($i = 1; $i < 5; $i++)
                    <th class="text-end" nowrap>Base Amt.</th>
                    <th class="text-end" nowrap>Tax Amt.</th>
                    <th class="text-end" nowrap>Total Amt.</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $total_dom_pre_count = $total_dom_pos_count = $total_dom_pre = $total_dom_post = $total_dom_pre_base = $total_dom_pre_tax = $total_dom_pre_total = $total_dom_post_base = $total_dom_post_tax = $total_dom_post_total = 0;
                $total_com_pre_count = $total_com_pos_count = $total_com_pre = $total_com_post = $total_com_pre_base = $total_com_pre_tax = $total_com_pre_total = $total_com_post_base = $total_com_post_tax = $total_com_post_total = 0;
            @endphp
            @forelse($geo_areas as $ga)
                @php
                    $gas_consumption = $gas_sale_array[$ga->id] ?? null;
                    // Domestic Prepaid
                    $dom_pre_count = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::PREPAID->value]['invoice_count'] ?? 0;
                    $dom_pre = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::PREPAID->value]['total_sale'] ?? 0;
                    $dom_pre_base_amt = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::PREPAID->value]['base_amount'] ?? 0;
                    $dom_pre_tax_amt = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::PREPAID->value]['tax_amount'] ?? 0;
                    $dom_pre_tot_amt = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::PREPAID->value]['total_amount'] ?? 0;
                    // Domestic Postpaid
                    $dom_pos_count = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::POSTPAID->value]['invoice_count'] ?? 0;
                    $dom_post = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::POSTPAID->value]['total_sale'] ?? 0;
                    $dom_post_base_amt = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::POSTPAID->value]['base_amount'] ?? 0;
                    $dom_post_tax_amt = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::POSTPAID->value]['tax_amount'] ?? 0;
                    $dom_post_tot_amt = $gas_consumption[\App\Enums\SegmentType::DOMESTIC->value][\App\Enums\ConnectionType::POSTPAID->value]['total_amount'] ?? 0;
                    // Commercial Prepaid
                    $com_pre_count = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::PREPAID->value]['invoice_count'] ?? 0;
                    $com_pre = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::PREPAID->value]['total_sale'] ?? 0;
                    $com_pre_base_amt = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::PREPAID->value]['base_amount'] ?? 0;
                    $com_pre_tax_amt = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::PREPAID->value]['tax_amount'] ?? 0;
                    $com_pre_tot_amt = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::PREPAID->value]['total_amount'] ?? 0;
                    // Commercial Postpaid
                    $com_pos_count = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::POSTPAID->value]['invoice_count'] ?? 0;
                    $com_post = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::POSTPAID->value]['total_sale'] ?? 0;
                    $com_post_base_amt = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::POSTPAID->value]['base_amount'] ?? 0;
                    $com_post_tax_amt = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::POSTPAID->value]['tax_amount'] ?? 0;
                    $com_post_tot_amt = $gas_consumption[\App\Enums\SegmentType::COMMERCIAL->value][\App\Enums\ConnectionType::POSTPAID->value]['total_amount'] ?? 0;
                    // accumulate totals
                    // Dom Prepaid
                    $total_dom_pre_count+= $dom_pre_count;
                    $total_dom_pre += $dom_pre;
                    $total_dom_pre_base += $dom_pre_base_amt;
                    $total_dom_pre_tax += $dom_pre_tax_amt;
                    $total_dom_pre_total += $dom_pre_tot_amt;
                    // Dom Postpaid
                    $total_dom_pos_count += $dom_pos_count;
                    $total_dom_post += $dom_post;
                    $total_dom_post_base += $dom_post_base_amt;
                    $total_dom_post_tax += $dom_post_tax_amt;
                    $total_dom_post_total += $dom_post_tot_amt;
                    // Com Prepaid
                    $total_com_pre_count+= $com_pre_count;
                    $total_com_pre += $com_pre;
                    $total_com_pre_base += $com_pre_base_amt;
                    $total_com_pre_tax += $com_pre_tax_amt;
                    $total_com_pre_total += $com_pre_tot_amt;
                    // Com Postpaid
                    $total_com_pos_count += $com_pos_count;
                    $total_com_post += $com_post;
                    $total_com_post_base += $com_post_base_amt;
                    $total_com_post_tax += $com_post_tax_amt;
                    $total_com_post_total += $com_post_tot_amt;
                @endphp
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $ga->name }}</td>
                    {{-- Invoice counts --}}
                    <td class="text-end">{{ numberFormat($dom_pre_count) }}</td>
                    <td class="text-end">{{ numberFormat($dom_pos_count) }}</td>
                    <td class="text-end">{{ numberFormat($com_pre_count) }}</td>
                    <td class="text-end">{{ numberFormat($com_pos_count) }}</td>
                    {{-- GAS sale In SCMs --}}
                    {{-- Domestic Prepaid --}}
                    <td class="text-end">{{ numberFormat($dom_pre,2) }}</td>
                    {{-- Domestic Postpaid --}}
                    <td class="text-end">{{ numberFormat($dom_post,2) }}</td>
                    {{-- Commercial Prepaid --}}
                    <td class="text-end">{{ numberFormat($com_pre,2) }}</td>
                    {{-- Commercial Postpaid --}}
                    <td class="text-end">{{ numberFormat($com_post,2) }}</td>
                    {{-- Revenue of GAS Sale --}}
                    {{-- Domestic Prepaid --}}
                    <td class="text-end">{{ numberFormat($dom_pre_base_amt,2) }}</td>
                    <td class="text-end">{{ numberFormat($dom_pre_tax_amt,2) }}</td>
                    <td class="text-end">{{ numberFormat($dom_pre_tot_amt,2) }}</td>
                    {{-- Domestic Postpaid --}}
                    <td class="text-end">{{ numberFormat($dom_post_base_amt,2) }}</td>
                    <td class="text-end">{{ numberFormat($dom_post_tax_amt,2) }}</td>
                    <td class="text-end">{{ numberFormat($dom_post_tot_amt,2) }}</td>
                    {{-- Commercial Prepaid --}}
                    <td class="text-end">{{ numberFormat($com_pre_base_amt,2) }}</td>
                    <td class="text-end">{{ numberFormat($com_pre_tax_amt,2) }}</td>
                    <td class="text-end">{{ numberFormat($com_pre_tot_amt,2) }}</td>
                    {{-- Commercial Postpaid --}}
                    <td class="text-end">{{ numberFormat($com_post_base_amt,2) }}</td>
                    <td class="text-end">{{ numberFormat($com_post_tax_amt,2) }}</td>
                    <td class="text-end">{{ numberFormat($com_post_tot_amt,2) }}</td>
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
                    {{-- Total GAS Sale.  --}}
                    {{-- Invoice Counts --}}
                    <td class="text-end">{{ numberFormat($total_dom_pre_count) }}</td>
                    <td class="text-end">{{ numberFormat($total_dom_pos_count) }}</td>
                    <td class="text-end">{{ numberFormat($total_com_pre_count) }}</td>
                    <td class="text-end">{{ numberFormat($total_com_pos_count) }}</td>
                    {{-- Total Domestic Prepaid --}}
                    <th>{{ numberFormat($total_dom_pre, 2) }}</th>
                    {{-- Total Domestic Postpaid --}}
                    <th>{{ numberFormat($total_dom_post, 2) }}</th>
                    {{-- Total Commercial Prepaid --}}
                    <th>{{ numberFormat($total_com_pre, 2) }}</th>
                    {{-- Total Commercial Postpaid --}}
                    <th>{{ numberFormat($total_com_post, 2) }}</th>
                    
                    {{-- Total Revenue of GAS Sale --}}
                    {{-- Total Domestic Prepaid --}}
                    <th>{{ numberFormat($total_dom_pre_base, 2) }}</th>
                    <th>{{ numberFormat($total_dom_pre_tax, 2) }}</th>
                    <th>{{ numberFormat($total_dom_pre_total, 2) }}</th>
                    {{-- Total Domestic Postpaid --}}
                    <th>{{ numberFormat($total_dom_post_base, 2) }}</th>
                    <th>{{ numberFormat($total_dom_post_tax, 2) }}</th>
                    <th>{{ numberFormat($total_dom_post_total, 2) }}</th>
                    {{-- Total Commercial Prepaid --}}
                    <th>{{ numberFormat($total_com_pre_base, 2) }}</th>
                    <th>{{ numberFormat($total_com_pre_tax, 2) }}</th>
                    <th>{{ numberFormat($total_com_pre_total, 2) }}</th>
                    {{-- Total Commercial Postpaid --}}
                    <th>{{ numberFormat($total_com_post_base, 2) }}</th>
                    <th>{{ numberFormat($total_com_post_tax, 2) }}</th>
                    <th>{{ numberFormat($total_com_post_total, 2) }}</th>
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