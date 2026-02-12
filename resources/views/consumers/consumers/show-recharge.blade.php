<div class="border rounded-top">
    <div class="bg-light p-2 fs-5 fw-semibold">
        <i class="bi bi-files"></i>&nbsp;Recharge History - ({{ $recharges->total() }})
    </div>
    <div class="p-2">
        @if ($recharges->count() > 0)
            <div class="responsive">
                <table class="table table-bordered table-hover table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Recharge Date</th>
                            <th>Amount</th>
                            <th>Balance</th>
                            <th>Payment Type</th>
                            <th>Transaction Number</th>
                            {{-- <th>Added By</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recharges as $recharge)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ dateFormat($recharge->recharge_date) }}</td>
                                <td>{{ numberFormat($recharge->amount) }}</td>
                                <td>{{ numberFormat($recharge->balance) }}</td>
                                <td>{{ $recharge->paymentType->name }}</td>
                                <td>{{ $recharge->transaction_id }}</td>
                                {{-- <td>{{ $recharge->createdBy->first_name }}&nbsp;{{ $recharge->createdBy->last_name }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div>
                {{ $recharges->links('utils.paginator', ['modDiv' => 'nav-recharge']) }}
            </div>
        @else
            <div class="alert alert-info">
                No records found!
            </div>
        @endif
    </div>
</div>