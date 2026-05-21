{{-- Invoice List counts --}}
<div class="card-group">
    <div class="card text-bg-warning mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h3 class="card-title text-end">{{ numberFormat($totals['total_payable'],2) }}</h3>
            <p class="card-text text-end">Total Payable Amount</p>
        </div>
    </div>
    <div class="card text-bg-info mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h3 class="card-title text-end">{{ numberFormat($totals['total_balance'],2) }}</h3>
            <p class="card-text text-end">Total Balance</p>
        </div>
    </div>
</div>