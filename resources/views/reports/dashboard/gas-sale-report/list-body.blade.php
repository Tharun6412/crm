<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped bg-white page-sort">
        <thead class="table-success">
            <tr>
                <th rowspan="2" width="1%" nowrap="nowrap">S No.</th>
                <th rowspan="2">GA Name</th>
                <th colspan="4" class="text-center">Sale in SCMs</th>
            </tr>
            <tr>
                <th>Domestic Prepaid</th>
                <th>Domestic Postpaid</th>
                <th>Commercial Prepaid</th>
                <th>Commercial Postpaid</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $commonParams = [
                    'invoice_type' => [\App\Enums\InvoiceType::GAS_BILL->value],
                    'date_from'    => $date_from->format('d-m-Y'),
                    'date_to'      => $date_to->format('d-m-Y'),
                ];
            @endphp
            @forelse($gaGasSales as $ga)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $ga->ga_name }}</td>
                    <td>
                        <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query(array_merge($commonParams, [
                            'geo_area' => [$ga->ga_id],
                            'segment_id' => [\App\Enums\SegmentType::DOMESTIC->value],
                            'connection_type_id' => [\App\Enums\ConnectionType::PREPAID->value],
                            ])) }}" class="aging-link" target="_blank">{{ numberFormat($ga->dom_pre,2) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query(array_merge($commonParams, [
                            'geo_area' => [$ga->ga_id],
                            'segment_id' => [\App\Enums\SegmentType::DOMESTIC->value],
                            'connection_type_id' => [\App\Enums\ConnectionType::POSTPAID->value],
                            ])) }}" class="aging-link" target="_blank">{{ numberFormat($ga->dom_post,2) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query(array_merge($commonParams, [
                            'geo_area' => [$ga->ga_id],
                            'segment_id' => [\App\Enums\SegmentType::COMMERCIAL->value],
                            'connection_type_id' => [\App\Enums\ConnectionType::PREPAID->value],
                            ])) }}" class="aging-link" target="_blank">{{ numberFormat($ga->com_pre,2) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query(array_merge($commonParams, [
                            'geo_area' => [$ga->ga_id],
                            'segment_id' => [\App\Enums\SegmentType::COMMERCIAL->value],
                            'connection_type_id' => [\App\Enums\ConnectionType::POSTPAID->value],
                            ])) }}" class="aging-link" target="_blank">{{ numberFormat($ga->com_post,2) }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No GA Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="table-info fw-bold">
                <th colspan="2" class="text-end">Total</th>
                <th>{{ numberFormat($gaGasSales->sum('dom_pre'),2) }}</th>
                <th>{{ numberFormat($gaGasSales->sum('dom_post'),2) }}</th>
                <th>{{ numberFormat($gaGasSales->sum('com_pre'),2) }}</th>
                <th>{{ numberFormat($gaGasSales->sum('com_post'),2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>
{{-- Scripts --}}
{{-- @include('scripts.link-modal') --}}