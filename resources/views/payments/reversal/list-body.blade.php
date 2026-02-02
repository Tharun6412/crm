<div>
    @if ($payments->count() > 0)
        <div class="table-responsive" style="min-height: 500px;">
            <table class="table table-bordered table-hover">
                <thead class="table-info">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>Invoice Number</th>
                        <th>Invoice Type</th>
                        <th>Transaction Number</th>
                        <th>Payment Date</th>
                        <th>Paid Amount</th>
                        <th>Payment Status</th>
                        <th>Added By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = (($payments->currentPage() - 1) * $payments->perPage())+1;
                    @endphp
                    @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $payment->invoice->invoice_number }}</td>
                            <td>{{ $payment->invoiceType->name }}</td>
                            <td>{{ $payment->transaction_id }}</td>
                            <td>{{ $payment->payment_date->format('d-m-Y') }}</td>
                            <td>{{ numberFormat($payment->amount, 2) }}</td>
                            <td>{{ $payment->status->name }}</td>
                            <td>{{ $payment->createdBy->name }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ url('bill/invoice/' . $payment->invoice->id) }}" target="_blank"><i class="bi bi-file-text"></i>&nbsp;View Invoice</a></li>
                                        @if (in_array($payment->status_id, [1,2]))
                                            <li><a class="dropdown-item link-modal" href="{{ url('payments/reversalPayment/' . $payment->id) }}"><i class="bi bi-cash"></i>&nbsp;Payment Reversal</a></li>
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
            {{ $payments->links('utils.paginator', ['modDiv' => 'payment-reversal-list']) }}
        </div>
    @else
        <div class="alert alert-info">
            No payments found
        </div>
    @endif
</div>
{{-- scripts --}}
@include('scripts.link-modal')