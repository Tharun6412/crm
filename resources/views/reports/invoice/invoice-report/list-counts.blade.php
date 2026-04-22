{{-- Invoice report counts --}}
<div class="card-group">
    {{-- <div class="card text-bg-primary mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h4 class="card-title">Total Consumption</h4>
            <p class="card-text">0.00</p>
        </div>
    </div> --}}
    <div class="card text-bg-primary mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h3 class="card-title text-end">{{ numberFormat($totals['total_taxable'],2) }}</h3>
            <p class="card-text text-end">Total Base Amount</p>
        </div>
    </div>
    <div class="card text-bg-success mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h3 class="card-title text-end">{{ numberFormat($totals['total_tax'],2) }}</h3>
            <p class="card-text text-end">Total Tax Amount</p>
        </div>
    </div>
    <div class="card text-bg-danger mb-3" style="max-width: 15rem;">
        <div class="card-body">
            <h3 class="card-title text-end">{{ numberFormat($totals['total_amount'],2) }}</h3>
            <p class="card-text text-end">Total Invoice Amount</p>
        </div>
    </div>
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