{{-- Transactions list body --}}
{{-- Search form --}}
<div class="row gx-1 mb-1">
    <div class="col-auto">
        <div class="input-group input-group-sm">
            <span class="input-group-text" id="search-key">Search</span>
            <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}">
        </div>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
    </div>
    <div class="col-auto">
        <a href="{{ url('payments/transactions') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a>
    </div>
    <div class="col-auto">
        ({{ $transactions->total() }}) Records found
    </div>
</div>
{{-- transactions list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>CRN</th>
                <th>Name</th>
                <th>GA<x-master.gaFilter class="float-end" /></th>
                <th>Module</th>
                <th>Gateway</th>
                <th>Date</th>
                <th>TXN ID</th>
                <th>Amount</th>
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
                        <td>{{ $sno + $loop->iteration }}</td>
                        <td><x-auth.link href="{{ url('consumers/' . $transaction->consumer->id) }}" target="_blank">{{ $transaction->consumer->crn }}</x-auth.link></td>
                        <td>{{ $transaction->consumer->name }}</td>
                        <td>{{ $transaction->consumer->ga->name }}</td>
                        <td>{{ $transaction->module->name ?? '' }}</td>
                        <td>{{ $transaction->gateway->gateway ?? '' }}</td>
                        <td>{{ $transaction->transaction_date?->format('d-m-Y') }}</td>
                        <td>{{ $transaction->transaction_id ?? '' }}</td>
                        <td>{{ numberFormat($transaction->amount ?? 0, 2) }}</td>
                        <td>{{ $transaction->status->name ?? '' }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                                    <li>
                                        <a href="{{ url('payments/transactions/' . $transaction->id) }}" class="dropdown-item link-canvas">View</a>
                                        @if (in_array($transaction->transaction_status_id ,[\App\Enums\TransactionStatus::SUCCESS->value, \App\Enums\TransactionStatus::INITIATED->value]))
                                            <a href="{{ url('payments/transactions/' . $transaction->id.'/edit?status_id=3') }}" class="dropdown-item link-modal">Fail</a>      
                                        @else
                                            <a href="{{ url('payments/transactions/' . $transaction->id.'/edit?status_id=2') }}" class="dropdown-item link-modal">Success</a>      
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="9">
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