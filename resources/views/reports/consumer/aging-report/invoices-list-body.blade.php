<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover page-sort">
        <thead class="table-success">
            <tr>
                <th>S No</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Invoice Type</th>
                <th>CRN</th>
                <th>Consumer Name</th>
                <th>Segment</th>
                <th>Due Date</th>
                <th>Amount</th>
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
                    <td>{{ dateFormat($inv->due_date) }}</td>
                    <td>{{ numberFormat($inv->total_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No Records Found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div>
    {{ $invoices->links('utils.paginator', ['modDiv' => 'aging-invoices-list']) }}
</div>
