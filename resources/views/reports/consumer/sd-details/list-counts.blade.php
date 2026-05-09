{{-- SD details report counts --}}
<div class="card-group">
    <div class="card text-bg-primary mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h3 class="card-title text-end">{{ numberFormat($totals['total_deposit'],2) }}</h3>
            <p class="card-text text-end">Total Deposit</p>
        </div>
    </div>
    <div class="card text-bg-success mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h3 class="card-title text-end">{{ numberFormat($totals['paid_deposit'],2) }}</h3>
            <p class="card-text text-end">Total Paid Deposit</p>
        </div>
    </div>
    <div class="card text-bg-info mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h3 class="card-title text-end">{{ numberFormat($totals['balance'],2) }}</h3>
            <p class="card-text text-end">Total Balance</p>
        </div>
    </div>
</div>