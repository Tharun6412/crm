<div>
    @if ($invoices->count() > 0)
        <div class="table-responsive" style="min-height: 500px;">
            <table class="table table-bordered table-hover">
                <thead class="table-info">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>CRN</th>
                        <th>Invoice Number</th>
                        <th>Status</th>
                        <th>Added By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = (($invoices->currentPage() - 1) * $invoices->perPage())+1;
                    @endphp
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td><x-auth.link class="link-modal" href="{{ url('consumers/'.$invoice->consumer_id) }}">{{ $invoice->consumer->crn }}</x-auth.link></td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->status->name }}</td>
                            <td>{{ $invoice->createdBy->name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ url('bill/invoice/' . $invoice->id) }}" target="_blank"><i class="bi bi-file-text"></i>&nbsp;View</a></li>
                                        @if ($invoice->status_id == 2)
                                            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('bill/creditNote/create/' . $invoice->id) }}" target="_blank"><i class="bi bi-chevron-right"></i>&nbsp;Add Credit/Debit Note</x-auth.link></li>
                                            <li><x-auth.link class="dropdown-item link-modal" href="{{ url('bill/invoice/cancelInvoice/' . $invoice->id) }}" target="_blank"><i class="bi bi-chevron-right"></i>&nbsp;Cancel Invoice</x-auth.link></li>
                                            <li><a class="dropdown-item link-modal" href="{{ url('payments/invoicePayments/create/'.$invoice->id) }}"><i class="bi bi-cash"></i>&nbsp;Pay Invoice</a></li>
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
        <div>
            {{ $invoices->links('utils.paginator', ['modDiv' => 'invoice-list']) }}
        </div>
    @else
        <div class="alert alert-info">
            No invoices found
        </div>
    @endif
</div>
@include('scripts.link-modal')