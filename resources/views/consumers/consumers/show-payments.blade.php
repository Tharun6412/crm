{{-- Show Payments details, tab content --}}
<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-file-text"></i>&nbsp;Payments&nbsp;-&nbsp;({{ $payments->total() }})
    </div>
    <div class="p-2">
        @if ($payments->count() > 0)
            <div class="responsive">
                <table class="table table-bordered table-hover table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Invoice number</th>
                            <th>Date</th>
                            <th>Payment Type</th>
                            <th>Transaction Number</th>
                            <th class="text-end">Amount</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = (($payments->currentPage() - 1) * $payments->perPage())+1
                        @endphp
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $payment->invoice->invoice_number }}</td>
                                <td>{{ $payment->payment_date?->format('d-m-Y') }}</td>
                                <td>{{ $payment->paymentType->name }}</td>
                                <td>{{ $payment->transaction_id }}</td>
                                <td class="text-end">{{ numberFormat($payment->amount, 2) }}</td>
                                <td>{{ $payment->status->name }}</td>
                                <td>{{ $payment->createdBy->name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ url('bill/invoice/' . $payment->invoice->id) }}" target="_blank"><i class="bi bi-file-text"></i>&nbsp;View</a></li>
                                            @if (in_array($payment->status_id, [\App\Enums\PaymentStatus::PROGRESS->value]))
                                                <li><a class="dropdown-item link-modal" href="{{ url('payments/invoicePayments/create/'.$payment->invoice->id) }}"><i class="bi bi-cash"></i>&nbsp;Pay Invoice</a></li>
                                            @endif
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
                {{ $payments->links('utils.paginator', ['modDiv' => 'nav-pay']) }}
            </div>
        @else
            <div class="alert alert-info">
                No payments found!
            </div>
        @endif
    </div>
</div>
{{-- Scripts --}}
@include('scripts.link-modal')