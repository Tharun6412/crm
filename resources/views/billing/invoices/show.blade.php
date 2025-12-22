{{-- Invoice show --}}

@extends('layouts.layout')

@section('title', 'Invoice')

@section('page-title', 'Invoice#' . $invoice->invoice_number)

@section('page-content')
    <div class="d-flex align-content-md-start">
        <div class="a4-page pb-2 border bg-white">
            <div class="row p-4">
                <div class="col-sm-4">
                    <img src="{{ asset('img/logo.png') }}" alt="MeghaGas" class="img-fluid">
                </div>
                <div class="col-sm-8 text-end">
                    <span class="fs-3 fw-semibold">INVOICE</span>
                </div>
            </div>
            <div class="row px-4">
                <div class="col-sm-6">
                    <address>
                        <span class="fw-semibold">Megha Gas Distribution Privated Limited. </span><br>
                        S-2, Technocrat Industrial Estate, <br>
                        Balanagar,Hyderabad, <br>
                        Telangana - 500 037
                    </address>
                </div>
                <div class="col-sm-6">
                    <div class="row gy-1 gx-2">
                        <div class="col-sm-6 text-end">Invoice Number:</div>
                        <div class="col-sm-6">{{ $invoice->invoice_number }}</div>
                        <div class="col-sm-6 text-end">Date:</div>
                        <div class="col-sm-6">{{ $invoice->invoice_date?->format('d-m-Y') }}</div>
                        <div class="col-sm-6 text-end">CRN:</div>
                        <div class="col-sm-6">{{ $invoice->consumer?->crn }}</div>
                    </div>
                </div>
            </div>
            <div class="row px-4">
                <div class="col-sm-6">
                    <address>
                        <strong>{{ $invoice->consumer->name}}</strong><br>
                        {{ $invoice->consumer->cofDisplay?->name }} {{ $invoice->consumer->cof_name }}<br>
                        {{ $invoice->consumer->hno }}, {{ $invoice->consumer->street }},<br>
                        {{ $invoice->consumer->colony }}, {{ $invoice->consumer->city }},<br>
                        {{ $invoice->consumer->district->name ?? '' }}, {{ $invoice->consumer->ga->state->name ?? '' }} - {{ $invoice->consumer->pincode }}.
                    </address>
                </div>
                <div class="col-sm-6 text-center">
                    {{ $invoice->invoiceType->name ?? '' }}
                </div>
            </div>
            <div class="px-4">
                <div class="fw-semibold">Invoice items</div>
                <table class="table table-bordered table-success">
                    <thead class="table-success">
                        <tr>
                            <th width="1%" nowrap>No</th>
                            <th>Item</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">QTY</th>
                            <th class="text-end">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($invoice->items->count() > 0)
                            @foreach ($invoice->items as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->item->name ?? '' }}</td>
                                    <td class="text-end">{{ numberFormat($item->unit_price, 2) }}</td>
                                    <td class="text-end">{{ numberFormat($item->quantity, 2) }}</td>
                                    <td class="text-end">{{ numberFormat(($item->unit_price * $item->quantity), 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end">Sub Total</td>
                            <td class="text-end">{{ numberFormat($invoice->base_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Tax ({{ $invoice->tax->name ??'' }} - {{ $invoice->tax_value }}%)</td>
                            <td class="text-end">{{ numberFormat($invoice->tax_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Invoice Total</td>
                            <td class="text-end">{{ numberFormat($invoice->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="p-2">
            <button class="btn btn-primary"><i class="bi bi-printer"></i>&nbsp;Print</button>
            <button class="btn btn-primary"><i class="bi bi-file-text"></i>&nbsp;Options</button>
        </div>
    </div>
@endsection
{{-- Styles --}}
@push('styles')
<style>
    .a4-page {
        width: 210mm;
        min-height: 180mm;
    }
    .content-page {
        background-color: #F7F7F7;
    }
</style>
@endpush