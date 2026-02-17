<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th nowrap>S No</th>
                <th nowrap>Invoice No</th>
                <th nowrap>Invoice Date</th>
                <th nowrap>Invoice Type</th>
                <th nowrap>CRN</th>
                <th nowrap>Consumer Name</th>
                <th nowrap>Segment</th>
                <th nowrap>Consumption</th>
                <th nowrap>Due Date</th>
                <th class="text-end" nowrap>Invoice Amount</th>
                <th class="text-end" nowrap>Balance Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = (($invoices->currentPage() - 1) * $invoices->perPage())+1;
            @endphp
            @forelse($invoices as $inv)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $inv->invoice_number }}</td>
                    <td>{{ dateFormat($inv->invoice_date) }}</td>
                    <td>{{ $inv->invoiceType->name }}</td>
                    <td>{{ $inv->consumer->crn }}</td>
                    <td>{{ $inv->consumer->name }}</td>
                    <td>{{ $inv->consumer->segment->name }}</td>
                    <td>{{ $inv->consumption->net_consumption ?? 0 }}</td>
                    <td>{{ dateFormat($inv->due_date) }}</td>
                    <td class="text-end">{{ numberFormat($inv->payable_amount, 2) }}</td>
                    <td class="text-end">{{ numberFormat($inv->balance_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No Records Found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end" colspan="8">Total</th>
                <th class="text-end">{{ numberFormat($invoices->sum('payable_amount'),2) }}</th>
                <th class="text-end">{{ numberFormat($invoices->sum('balance_amount'),2) }}</th>
            </tr>
        </tfoot>
    </table>
</div>

<div>
    {{ $invoices->links('utils.paginator', ['modDiv' => 'aging-invoices-list']) }}
</div>
