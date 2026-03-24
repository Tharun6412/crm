{{-- Invoice details --}}
@props([
    'invoice' => [],
])
{{-- Consumer basic details --}}
<x-consumer.basic-details :consumer="$invoice->consumer" {{ $attributes->merge(['class']) }} />
{{-- Invoice details --}}
<div class="bg-secondary-subtle p-2 mb-2 rounded">
    <table class="table table-borderless table-info table-sm">
        <tr>
            <td><span class="fw-semibold">Invoice No :</span>&nbsp;<x-auth.link href="{{ url('bill/invoice/'.$invoice->id) }}" target="_blank">{{ $invoice->invoice_number }}</x-auth.link></td>
            <td><span class="fw-semibold">Invoice Type :</span>&nbsp;{{ $invoice->invoiceType->name }}</td>
        </tr>
        <tr>
            <td><span class="fw-semibold">Invoice Date :</span>&nbsp;{{ $invoice->invoice_date?->format('d-m-Y') }}</td>
            <td><span class="fw-semibold">Due Date :</span>&nbsp;{{ $invoice->due_date?->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td><span class="fw-semibold">Amount :</span>&nbsp;{{ numberFormat($invoice->total_amount, 2) }}</td>
            <td><span class="fw-semibold">Cr/Dr Amount :</span>&nbsp;{{ numberFormat($invoice->credit_amount, 2) }}</td>
        </tr>
        <tr>
            <td><span class="fw-semibold">Status :</span>&nbsp;<x-payments.status :status="$invoice->status" /></td>
            <td><span class="fw-semibold">Payable Amount :</span>&nbsp;{{ numberFormat($invoice->payable_amount, 2) }}</td>
        </tr>  
         <tr>
            <td><span class="fw-semibold">Paid :</span>&nbsp;{{ numberFormat($invoice->paid_amount, 2) }}</td>
            <td><span class="fw-semibold">Balance :</span>&nbsp;{{ numberFormat($invoice->balance_amount, 2) }}</td>
        </tr>   
    </table>
</div>
{{-- <div class="row g-1 pb-2 my-2 m-1 bg-secondary-subtle shadow-sm rounded p-2">
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
    <div class="col-sm-2 text-end fw-semibold text-nowrap">Payable Amount :</div>
    <div class="col-sm-4">&nbsp;{{ numberFormat($invoice->payable_amount, 2) }}</div>
    <div class="col-sm-2 text-end fw-semibold">Paid :</div>
    <div class="col-sm-4">{{ numberFormat($invoice->paid_amount, 2) }}</div>
    <div class="col-sm-2 text-end fw-semibold">Balance : </div>
    <div class="col-sm-4">{{ numberFormat($invoice->balance_amount, 2) }}</div>
</div> --}}