{{-- Show SD details, tab content --}}

<div class="border rounded-top">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-cash-stack"></i>&nbsp;Security Deposit Details
    </div>
    <div class="p-2">
        <div class="table-responsive">
            <table class="table table-bordered table-warning"> 
                <thead class="table-warning">
                    <tr>
                        <th>Scheme Name</th>
                        <th>#</th>
                        <th class="text-end">Security Deposit</th>
                        <th class="text-end">Consumption Deposit</th>
                        <th class="text-end">Total Deposit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="3" class="text-center align-middle"><strong>{{ $consumer->scheme?->scheme?->name }}</strong></td>
                        <td>Charges</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->security_deposit) }}</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->consumption_deposit) }}</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->total_deposit) }}</td>
                    </tr>
                    <tr>
                        <td>Payments</td>
                        <td class="text-end" colspan="2">{{ numberFormat($consumer->scheme?->paid_deposit) }}</td>
                        <td class="text-end">{{ numberFormat($consumer->scheme?->paid_deposit) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end text-success fw-bold">Balance Deposit</td>
                        <td class="text-end text-success fw-bold">{{ numberFormat($consumer?->scheme?->balance) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @if ($consumer->priceGroup)
            <div class="mt-2">
                <h4 class="fw-semibold text-decoration-underline">Price Group Details</h4>
                <table class="table table-bordered table-info"> 
                    <thead class="table-info">
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $consumer->priceGroup?->code }}</td>
                            <td class="text-start">{{ numberFormat($consumer->priceGroup?->price, 2) }}</td>
                            <td class="text-start">{{ $consumer->priceGroup?->description }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
        <div class="table-responsive">
            <h4 class="fw-semibold text-primary">Security Deposit Paid History</h4>
            <table class="table table-bordered table-primary">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>Date</th>
                        <th>Payment Type</th>
                        <th>Transaction Number</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
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
                                <td class="text-end">{{ numberFormat($sd->amount, 2) }}</td>
                                <td class="text-end">{{ numberFormat($sd->balance, 2) }}</td>
                                <td>{{ $sd->status->name }}</td>
                                <td>{{ $sd->createdBy->name }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item link-modal" href="{{ url('consumers/payDeposit/'.$sd->id) }}"><i class="bi bi-file-text"></i>&nbsp;View</a></li>
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i>&nbsp;Print</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center">No records found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        {{-- Prepaid Data --}}
        @if ($consumer->prepaidData)
            <div>
                <h4 class="fw-semibold text-primary">Prepaid Data</h4>
                <table class="table table-bordered">
                    <tr>
                        <td class="bg-light">Prepaid date</td>
                        <td>{{ $consumer->prepaidData->created_at?->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">HES Status</td>
                        <td>{{ ($consumer->prepaidData->status == 1) ? 'Sent' : 'Not Sent' }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">HES sent date</td>
                        <td>{{ $consumer->prepaidData->hes_date?->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="bg-light">Initial Recharge</td>
                        <td>{{ numberFormat($consumer->prepaidData->bonus) }}</td>
                    </tr>
                    @if ($consumer->prepaidData->conversion_date)
                        <tr>
                            <td class="bg-light">Conversion date</td>
                            <td>{{ $consumer->prepaidData->conversion_date?->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Postpaid Scheme</td>
                            <td>{{ $consumer->prepaidData->postpaidScheme->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Prepaid Scheme</td>
                            <td>{{ $consumer->prepaidData->prepaidScheme->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Bill date</td>
                            <td>{{ $consumer->prepaidData->bill_date ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Bill Qty</td>
                            <td>{{ $consumer->prepaidData->bill_qty ?? '' }} SCM</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Bill amount</td>
                            <td>{{ numberFormat($consumer->prepaidData->bill_amount) }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Bill Status</td>
                            <td>{{ ($consumer->prepaidData->bill_status == 1) ? 'Paid' : 'Pending' }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        @endif
        {{-- Scheme change history --}}
        @if ($consumer->schemeHistory->count() > 0)
            <h4 class="fw-semibold text-primary">Scheme History</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-warning">
                    <thead class="table-warning">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Scheme Code</th>
                            <th>Scheme Name</th>
                            <th class="text-end">Security Deposit</th>
                            <th class="text-end">Consumption Deposit</th>
                            <th class="text-end">Total Deposit</th>
                            <th class="text-end">Paid Deposit</th>
                            <th class="text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($consumer->schemeHistory as $scheme)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $scheme->scheme->code ?? '' }}</td>
                                <td>{{ $scheme->scheme->name ?? '' }}</td>
                                <td class="text-end">{{ numberFormat($scheme->security_deposit) }}</td>
                                <td class="text-end">{{ numberFormat($scheme->consumption_deposit) }}</td>
                                <td class="text-end">{{ numberFormat($scheme->total_deposit) }}</td>
                                <td class="text-end">{{ numberFormat($scheme->paid_deposit) }}</td>
                                <td class="text-end">{{ numberFormat($scheme->balance) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@include('scripts.link-modal')