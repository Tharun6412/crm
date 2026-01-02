{{-- Invoice details --}}
@props([
    'invoice' => [],
])
{{-- Consumer basic details --}}
<x-consumer.basic-details :consumer="$invoice->consumer" {{ $attributes->merge(['class']) }} />
{{-- Invoice details --}}
<div class="row g-2 pb-2 my-2 bg-warning-subtle rounded">
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Invoice No :</div>
    <div class="col-sm-4">{{ $invoice->invoice_number }}</div>
    <div class="col-sm-2 text-end fw-semibold">Status : </div>
    <div class="col-sm-4">{{ $invoice->status->name }}</div>
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Invoice Date :</div>
    <div class="col-sm-4">{{ $invoice->invoice_date?->format('d-m-Y') }}</div>
    <div class="col-sm-2 text-end fw-semibold">Amount : </div>
    <div class="col-sm-4">{{ numberFormat($invoice->total_amount, 2) }}</div>
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Paid :</div>
    <div class="col-sm-4">{{ numberFormat($invoice->paid_amount, 2) }}</div>
    <div class="col-sm-2 text-end fw-semibold">Balance : </div>
    <div class="col-sm-4">{{ numberFormat($invoice->balance_amount, 2) }}</div>
</div>