<div class="table-responsive">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th nowrap="nowrap">S No.</th>
                <th>GA Name</th>
                <th>Domestic Prepaid</th>
                <th>Domestic Postpaid</th>
                <th>Commercial Prepaid</th>
                <th>Commercial Postpaid</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @forelse($gaGasSales as $ga)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $ga->ga_name }}</td>
                    <td>
                        <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query([
                            'ga_id' => $ga->ga_id,
                            'segment_id' => \App\Enums\SegmentType::DOMESTIC->value,
                            'connection_type_id' => \App\Enums\ConnectionType::PREPAID->value,
                            'invoice_type' => \App\Enums\InvoiceType::GAS_BILL->value,
                            ]) }}" class="aging-link" target="_blank">{{ numberFormat($ga->dom_pre,2) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query([
                            'ga_id' => $ga->ga_id,
                            'segment_id' => \App\Enums\SegmentType::DOMESTIC->value,
                            'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value,
                            'invoice_type' => \App\Enums\InvoiceType::GAS_BILL->value,
                            ]) }}" class="aging-link" target="_blank">{{ numberFormat($ga->dom_post,2) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query([
                            'ga_id' => $ga->ga_id,
                            'segment_id' => \App\Enums\SegmentType::COMMERCIAL->value,
                            'connection_type_id' => \App\Enums\ConnectionType::PREPAID->value,
                            'invoice_type' => \App\Enums\InvoiceType::GAS_BILL->value,
                            ]) }}" class="aging-link" target="_blank">{{ numberFormat($ga->com_pre,2) }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ url('reports/invoiceReport') }}?{{ http_build_query([
                            'ga_id' => $ga->ga_id,
                            'segment_id' => \App\Enums\SegmentType::COMMERCIAL->value,
                            'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value,
                            'invoice_type' => \App\Enums\InvoiceType::GAS_BILL->value,
                            ]) }}" class="aging-link" target="_blank">{{ numberFormat($ga->com_post,2) }}
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
            <tr>
                <th colspan="2">Total</th>
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