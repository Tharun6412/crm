{{-- Invoice details --}}
@props([
    'invoice' => [],
])
{{-- Consumer basic details --}}
<x-consumer.basic-details :consumer="$invoice->consumer" {{ $attributes->merge(['class']) }} />
{{-- Invoice details --}}
<div class="row g-1 pb-2 my-2 m-1 bg-secondary-subtle shadow-sm rounded">
    <div class="col-sm-2 text-end fw-semibold">Invoice No :</div>
    <div class="col-sm-4"><x-auth.link href="{{ url('bill/invoice/'.$invoice->id) }}" target="_blank">{{ $invoice->invoice_number }}</x-auth.link></div>
    <div class="col-sm-2 text-end fw-semibold">Invoice Type :</div>
    <div class="col-sm-4">{{ $invoice->invoiceType->name }}</div>
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Invoice Date :</div>
    <div class="col-sm-4">{{ $invoice->invoice_date?->format('d-m-Y') }}</div>
    <div class="col-sm-2 text-end fw-semibold">Due Date :</div>
    <div class="col-sm-4">{{ $invoice->due_date?->format('d-m-Y') }}</div>
    <div class="col-sm-2 text-end fw-semibold">Amount : </div>
    <div class="col-sm-4">{{ numberFormat($invoice->total_amount, 2) }}</div>
    <div class="col-sm-2 text-end fw-semibold">Cr/Dr Amount :</div>
    <div class="col-sm-4">{{ numberFormat($invoice->credit_amount, 2) }}</div>
    <div class="col-sm-2 text-end fw-semibold">Status : </div>
    <div class="col-sm-4">{{ $invoice->status->name }}</div>
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Payable Amount : </div>
    <div class="col-sm-4">{{ numberFormat($invoice->payable_amount, 2) }}</div>
    <div class="col-sm-2 text-end fw-semibold">Paid :</div>
    <div class="col-sm-4">{{ numberFormat($invoice->paid_amount, 2) }}</div>
    <div class="col-sm-2 text-end fw-semibold">Balance : </div>
    <div class="col-sm-4">{{ numberFormat($invoice->balance_amount, 2) }}</div>
</div>