<div>
    @if ($invoices->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white table-striped">
                <thead class="table-success">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>CRN</th>
                        <th>Name</th>
                        <th>Invoice Number</th>
                        <th>Invoice Date</th>
                        <th>Amount</th>
                        <th>Balance</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td><x-auth.link href="{{ url('consumers/'.$invoice->consumer_id) }}" target="_blank">{{ $invoice->consumer->crn }}</x-auth.link></td>
                            <td>{{ $invoice->consumer->name }}</td>
                            <td><a href="{{ url('bill/invoice/' . $invoice->id) }}" target="_blank">{{ $invoice->invoice_number }}</a></td>
                            <td>{{ $invoice->invoice_date?->format('d-m-Y') }}</td>
                            <td class="text-end">{{ numberFormat($invoice->total_amount, 2) }}</td>
                            <td class="text-end">{{ numberFormat($invoice->balance_amount, 2) }}</td>
                            <td>{{ $invoice->status->name }}</td>
                            <td>{{ $invoice->invoiceType->name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><x-auth.link class="dropdown-item" href="{{ url('bill/invoice/' . $invoice->id) }}" target="_blank"><i class="bi bi-file-text"></i>&nbsp;View</x-auth.link></li>
                                        @if ($invoice->status_id == 2)
                                            <li><x-auth.link class="dropdown-item" href="{{ url('bill/creditNote/create/' . $invoice->id) }}" target="_blank" action="gcrdr"><i class="bi bi-chevron-right"></i>&nbsp;Add Credit/Debit Note</x-auth.link></li>
                                            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('bill/invoice/cancelInvoice/' . $invoice->id) }}" target="_blank" action="caninv"><i class="bi bi-chevron-right"></i>&nbsp;Cancel Invoice</x-auth.link></li>
                                            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('payments/invoicePayments/create/'.$invoice->id) }}" action="payinv"><i class="bi bi-cash"></i>&nbsp;Pay Invoice</x-auth.link></li>
                                        @else
                                            <li><a class="dropdown-item link-modal" href="{{ url('payments/invoicePayments/create/'.$invoice->id) }}"><i class="bi bi-cash"></i>&nbsp;Payment Info</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <div><span class="fw-bold">({{ $invoices->count() }})</span> Invoices found</div>
        </div>
    @else
        <div class="alert alert-info text-center mb-1">
            No invoices found
        </div>
    @endif
</div>
@include('scripts.link-modal')