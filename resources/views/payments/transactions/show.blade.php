{{-- Transaction details --}}

<div class="offcanvas-header border-bottom">
    <h4 class="offcanvas-title border-start border-5 border-success ps-2" id="offcanvasRightLabel">{{ $transaction->module->name ?? '' }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="bg-light">CRN</td>
                <td>{{ $transaction->consumer->crn }}</td>
            </tr>
            <tr>
                <td class="bg-light">Name</td>
                <td>{{ $transaction->consumer->name }}</td>
            </tr>
            <tr>
                <td class="bg-light">GA</td>
                <td>{{ $transaction->consumer->ga->name }}</td>
            </tr>
            <tr>
                <td class="bg-light">Module</td>
                <td>{{ $transaction->module->name ?? '' }}</td>
            </tr>
            <tr>
                <td class="bg-light">Gateway</td>
                <td>{{ $transaction->gateway->gateway ?? '' }}</td>
            </tr>
            <tr>
                <td class="bg-light">Source</td>
                <td>{{ $transaction->source->name ?? '' }}</td>
            </tr>
        </tbody>
    </table>
    <h5>Transaction details</h5>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td class="bg-light">Status</td>
                <td><x-payments.transaction-status :status="$transaction->status"/></td>
            </tr>
            <tr>
                <td class="bg-light">Txn. Date</td>
                <td>{{ $transaction->transaction_date?->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td class="bg-light">Txn. ID</td>
                <td>{{ $transaction->transaction_id }}</td>
            </tr>
            <tr>
                <td class="bg-light">Amount</td>
                <td>{{ $transaction->amount }}</td>
            </tr>
            <tr>
                <td class="bg-light">Bank Reference</td>
                <td>{{ $transaction->bank_ref }}</td>
            </tr>
            <tr>
                <td class="bg-light">Gateway Ref</td>
                <td>{{ $transaction->pg_ref_id }}</td>
            </tr>
            <tr>
                <td class="bg-light">Txn. Ref</td>
                <td>{{ $transaction->transaction_ref }}</td>
            </tr>
            <tr>
                <td class="bg-light">Payment Mode</td>
                <td>{{ $transaction->payment_mode }}</td>
            </tr>
            <tr>
                <td class="bg-light">Created At</td>
                <td>{{ $transaction->created_at?->format('d-m-Y H:i:s') }}</td>
            </tr>
            @if (!empty($transaction->recharge) and ($transaction->transaction_status_id == \App\Enums\TransactionStatus::SUCCESS->value))
                <tr>
                    <td class="bg-light">HES Status</td>
                    <td><x-payments.recharge-status :status="$transaction->recharge?->hes_status"/>
                        @if($transaction->recharge?->hes_status != 2)
                        <form method="POST" id="update-recharge-form" action={{ url('payments/transactions/initiateRecharge/'.$transaction->id) }}>
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Initiate Recharge</button>
                        </form>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="bg-light">HES Date</td>
                    <td>{{ $transaction->recharge?->hes_date }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Note</td>
                    <td>{{ $transaction->recharge?->note }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div id="update-recharge-success"></div>
    <div id="update-recharge-error"></div>
</div>
@include('scripts.ajax-form-submit', ['form' => 'update-recharge'])
