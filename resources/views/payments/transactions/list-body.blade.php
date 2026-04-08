{{-- Transactions list body --}}
{{-- Search form --}}
<div class="row gx-1 mb-1">
    <div class="col-auto">
        <div class="input-group">
            <span class="input-group-text" id="search-key">Search</span>
            <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}">
        </div>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
    </div>
    <div class="col-auto">
        <a href="{{ url('payments/transactions') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
    </div>
    <div class="col-auto mt-1">
        <span class="fw-semibold">({{ $transactions->total() }})</span> Records found
    </div>
</div>
{{-- transactions list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover table-striped align-middle">
        <thead class="table-success align-middle">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>CRN</th>
                <th>Name</th>
                <th>
                    <div class="d-flex flex-row gap-2">
                        <div>GA</div> 
                        <div><x-master.gaFilter class="float-end"/></div>
                    </div>
                </th>
                <th>
                    <div class="d-flex flex-row gap-2">
                        <div>Module</div> 
                        <div><x-payments.paymentModuleFilter class="float-end"/></div>
                    </div>
                </th>
                <th>
                    <div class="d-flex flex-row gap-2">
                        <div>Gateway</div> 
                        <div><x-payments.paymentGatewayFilter class="float-end"/></div>
                    </div>
                </th>
                <th>
                    <div class="d-flex flex-row gap-2">
                        <div>Date</div> 
                        <div><x-master.date-filter class="float-end"/></div>
                    </div>
                </th>
                <th>TXN ID</th>
                <th class="text-end">Amount</th>
                <th>Status</th>
                <th width="2%" nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($transactions->count() > 0)
                @php
                    $sno = ($transactions->currentPage() - 1) * $transactions->perPage();
                @endphp
                @foreach ($transactions as $transaction)
                    <tr>
                        <td class="text-center"{ $sno + $loop->iteration }}</td>
                        <td><x-auth.link href="{{ url('consumers/' . $transaction->consumer->id) }}" target="_blank">{{ $transaction->consumer->crn }}</x-auth.link></td>
                        <td>{{ $transaction->consumer->name }}</td>
                        <td nowrap>{{ $transaction->consumer->ga->name }}</td>
                        <td nowrap>{{ $transaction->module->name ?? '' }}</td>
                        <td nowrap>{{ $transaction->gateway->gateway ?? '' }}</td>
                        <td nowrap>{{ $transaction->transaction_date?->format('d-m-Y') }}</td>
                        <td nowrap><a href="{{ url('payments/transactions/' . $transaction->id) }}" class="dropdown-item link-canvas text-primary">{{ $transaction->transaction_id ?? '' }}</a></td>
                        <td class="text-end" nowrap><i class="bi bi-currency-rupee"></i>{{ numberFormat($transaction->amount ?? 0, 2) }}</td>
                        <td>
                            <x-payments.transaction-status :status="$transaction->status"/>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                    <li>
                                        <a href="{{ url('payments/transactions/' . $transaction->id) }}" class="dropdown-item link-canvas">View</a>
                                        <x-auth.link href="{{ url('payments/transactions/' . $transaction->id.'/edit') }}" class="dropdown-item link-modal" action="edit">Edit</x-auth.link>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="11">
                        <x-layouts.callout-info>No records found!</x->
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div>
    {{ $transactions->links('utils.paginator', ['modDiv' => 'transactions-list']) }}
</div>
@include('scripts.link-modal')
@include('scripts.link-canvas')