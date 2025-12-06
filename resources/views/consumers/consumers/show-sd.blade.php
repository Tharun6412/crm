{{-- Show SD details, tab content --}}

<div class="border rounded-top">
    <div class="bg-light p-2 fs-5 fw-semibold">
        <i class="bi bi-cash-stack"></i>&nbsp;Security Deposit Details
    </div>
    <div class="p-2">
        <div class="mt-2">
            <table class="table table-bordered"> 
                <thead>
                    <tr>
                        <th>Scheme Name</th>
                        <th>#</th>
                        <th>Registration Charges</th>
                        <th>Security Deposit</th>
                        <th>Consumption Deposit</th>
                        <th>Total Deposit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="2" class="text-center"><strong>{{ $consumer->scheme?->scheme?->name }}</strong></td>
                        <td>Charges</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->scheme?->registration) }}</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->security_deposit) }}</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->consumption_deposit) }}</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->total_deposit) }}</td>
                    </tr>
                    <tr>
                        <td>Payments</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->scheme?->registration) }}</td>
                        <td class="text-end" colspan="2">{{ numberFormat($consumer->scheme?->security_deposit + $consumer->scheme?->consumption_deposit) }}</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->total_deposit) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <h4 class="fw-semibold text-decoration-underline">Security Deposit Paid History</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Date</th>
                        <th>Payment Type</th>
                        <th>Transaction Number</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Balance</th>
                        <th>Payment Status</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($consumer->sdPayment->count() > 0)
                        @foreach ($consumer->sdPayment as $sd)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sd->created_at->format('d-m-Y') }}</td>
                                <td>{{ $sd->paymentType->name }}</td>
                                <td>{{ $sd->transaction_number }}</td>
                                <td class="text-end">{{ numberFormat($sd->amount) }}</td>
                                <td class="text-end">{{ numberFormat($sd->balance) }}</td>
                                <td>{{ $sd->status->name }}</td>
                                <td>{{ $sd->createdBy->first_name }}&nbsp;{{ $sd->createdBy->last_name }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">No records found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>