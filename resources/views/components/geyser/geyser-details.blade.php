@props([
    'geyser' => [],
])
<div class="row g-2 pb-2 my-2 p-2 bg-warning-subtle">
    <div class="col-sm-2 text-end fw-semibold">Geyser Code : </div>
    <div class="col-sm-4">{{ $geyser->code ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Geyser Status : </div>
    <div class="col-sm-4"><x-geyser.status-change :status="$geyser->status"/></div>
    <div class="col-sm-2 text-end fw-semibold">Invoice No : </div>
    <div class="col-sm-4"><a href="{{ url('bill/invoice/'.$geyser->invoice_id) }}" target="_blank">{{ $geyser->invoice->invoice_number ?? '' }}</a></div>
    <div class="col-sm-2 text-end fw-semibold">Total Amount : </div>
    <div class="col-sm-4">{{ $geyser->invoice->payable_amount ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Invoice Type : </div>
    <div class="col-sm-4">{{ $geyser->invoice->invoiceType->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Paid Amount: </div>
    <div class="col-sm-4">{{ $geyser->invoice->paid_amount ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Invoice Status : </div>
    <div class="col-sm-4">{{ $geyser->invoice->status->name ?? '' }}</div>
    <div class="col-sm-2 text-end fw-semibold">Balance Amount : </div>
    <div class="col-sm-4">{{ $geyser->invoice->balance_amount ?? '' }}</div>
</div>