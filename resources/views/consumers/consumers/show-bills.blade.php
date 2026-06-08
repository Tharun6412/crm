{{-- Show Bill details, tab content --}}

<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-files"></i>&nbsp;Bills - ({{ $invoices->total() }})
    </div>
    <div class="p-2">
        @if ($invoices->count() > 0)
            <div class="table-responsive" style="min-height: 300px;">
                <table class="table table-bordered table-hover table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th nowrap>Invoice number</th>
                            <th class="text-center" nowrap>Invoice Date</th>
                            <th class="text-end" nowrap>Consumption<br/>(SCM)</th>
                            <th nowrap>Due Date</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">Balance</th>
                            <th>Status</th>
                            <th nowrap>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td class="text-center" nowrap>{{ $invoice->invoice_date?->format('d-m-y') }}</td>
                                <td class="text-end">{{ numberFormat($invoice->consumption->net_consumption, 3) }}</td>
                                <td class="text-center" nowrap>{{ $invoice->due_date?->format('d-m-y') }}</td>
                                <td class="text-end">{{ numberFormat($invoice->total_amount, 2) }}</td>
                                <td class="text-end">{{ numberFormat($invoice->balance_amount, 2) }}</td>
                                <td><x-invoice.status :status="$invoice->status"/></td>
                                <td>{{ $invoice->createdBy->name ?? '' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ url('bill/invoice/' . $invoice->id) }}" target="_blank"><i class="bi bi-file-text"></i>&nbsp;View</a></li>
                                            <li><a class="dropdown-item" href="{{ url('bill/gasInvoice/' . $invoice->id) }}" target="_blank"><i class="bi bi-file-text"></i>&nbsp;View / Print Gas Bill</a></li>
                                            @if (in_array($invoice->status_id, [2,3]))
                                                <li><x-auth.link class="dropdown-item link-modal" href="{{ url('payments/gasPayments/create/'.$invoice->id) }}" action="payinv"><i class="bi bi-cash"></i>&nbsp;Pay Invoice</x-auth.link></li>
                                            @endif
                                            @if (isSuperAdmin() OR isFullAccess())
                                                <li><a class="dropdown-item link-modal" href="{{ url('payments/oldPayment/'.$invoice->id.'/edit') }}"><i class="bi bi-cash"></i>&nbsp;Add Old Payment</a></li>
                                                <li><a class="dropdown-item link-modal" href="{{ url('payments/oldPayment/addLpc/'.$invoice->id) }}"><i class="bi bi-file-text"></i>&nbsp;Add LPC</a></li>
                                            @endif
                                            @empty(!$invoice->consumption->file_id)
                                                <li><a class="dropdown-item" href="{{ url('master/dc/documents/' . $invoice->consumption?->file_id) }}" title="{{ $invoice->consumption->file?->file_name }}" target="_blank"><i class="bi bi-image"></i>&nbsp;Meter Image</a></li>
                                            @endempty
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i>&nbsp;Print</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {{ $invoices->links('utils.paginator', ['modDiv' => 'nav-bills']) }}
            </div>
        @else
            <div class="alert alert-info">
                No invoices found!
            </div>
        @endif
    </div>
</div>
{{-- Scripts --}}
@include('scripts.link-modal')