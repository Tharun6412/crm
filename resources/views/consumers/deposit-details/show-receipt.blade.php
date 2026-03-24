{{-- Invoice show --}}

@extends('layouts.layout')

@section('title', 'Security Deposit Receipt')

@section('page-title', 'SD Report#'.$sd_payment->code)

@section('page-content')
    <div class="d-flex align-content-md-start">
        <div class="a4-page pb-2 border bg-white" id="printableArea">
            <div class="row p-4 pb-2">
                <div class="col-sm-4 border-bottom border-success-subtle">
                    <img src="{{ asset('img/logo.png') }}" alt="MeghaGas" class="img-fluid">
                </div>
                <div class="col-sm-8 text-end border-bottom border-success-subtle">
                    <span class="fs-4 fw-semibold">Security Deposit Receipt</span>
                </div>
            </div> 
            <div class="px-4">           
                 <img src="{{ asset('img/watermark_logo.png') }}" alt="MeghaGas" width="320" class="watermark-overlay">
                <div class="row">
                    <div class="col-sm-7">
                        <address>
                            <span class="fw-semibold">Megha Gas Distribution Privated Limited. </span><br>
                             {{-- Address component --}}
                            <x-master.gaAddress :ga-id="$sd_payment->consumer->ga_id"/>
                        </address>
                        <address>
                            <strong>{{ $sd_payment->consumer->name}}</strong><br>
                            {{ $sd_payment->consumer->cofDisplay?->name }} {{ $sd_payment->consumer->cof_name }}<br>
                            {{ $sd_payment->consumer->hno }}, {{ $sd_payment->consumer->street }},<br>
                            {{ $sd_payment->consumer->colony }}, {{ $sd_payment->consumer->city }},<br>
                            {{ $sd_payment->consumer->district->name ?? '' }}, {{ $sd_payment->consumer->ga->state->name ?? '' }} - {{ $sd_payment->consumer->pincode }}.
                        </address>
                    </div>
                    <div class="col-sm-5">
                        <div class="row gy-1 gx-2">
                            <div class="col-sm-6 text-end">Receipt Number:</div>
                            <div class="col-sm-6">{{ $sd_payment->code }}</div>
                            <div class="col-sm-6 text-end">Receipt Date:</div>
                            <div class="col-sm-6">{{ dateFormat($sd_payment->created_at) }}</div>
                            <div class="col-sm-6 text-end">CRN:</div>
                            <div class="col-sm-6">{{ $sd_payment->consumer->crn }}</div>
                            <div class="col-sm-6 text-end">Consumer Type:</div>
                            <div class="col-sm-6">{{ $sd_payment->consumer->segment->name }}</div>
                            <div class="col-sm-6 text-end">Products:</div>
                            <div class="col-sm-6">PNG</div>
                        </div>
                    </div>
                </div>           
                <table class="table table-borderless table-sm m-0">
                    <tr>
                        <td>
                            <h5 class="text-decoration-underline m-0">Security Deposit Details</h5>
                            <table class="table table-borderless table-sm m-0">
                                <tr>
                                    <td>Security deposit(Rs)</td>
                                    <td>:</td>
                                    <td>{{ numberFormat($sd_payment->consumer->scheme->security_deposit,2) }}</td>
                                </tr>
                                <tr>
                                    <td>Consumption deposit(Rs)</td>
                                    <td>:</td>
                                    <td>{{ numberFormat($sd_payment->consumer->scheme->consumption_deposit,2) }}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <h5 class="text-decoration-underline m-0">Payment Details</h5>
                            <table class="table table-borderless table-sm m-0 fs-6">
                                <tr>
                                    <td>Total Paid Amount(Rs)</td>
                                    <td>:</td>
                                    <td>{{ numberFormat($sd_payment->consumer->scheme->paid_deposit,2) }}</td>
                                </tr>
                                <tr>
                                    <td>Balance(Rs)</td>
                                    <td>:</td>
                                    <td>{{ numberFormat($sd_payment->consumer->scheme->balance,2) }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                @if ($sd_payment->status_id == App\Enums\SDPaymentStatus::PAID->value)
                    <div class="text-center p-2 border border-success text-success fw-semibold mt-1">Security deposit paid successfully.</div>
                @endif
                <div class="fw-semibold fs-5 mt-1">Security Deposit Details</div>
                <table class="table table-bordered table-success fs-6 table-sm align-middle">                    
                    <tbody>
                        <tr>
                            <td class="bg-light" width="40%">Payment Date</td>
                            <td>{{ dateFormat($sd_payment->created_at) }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Payment Type</td>
                            <td>{{ $sd_payment->paymentType?->name }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Cheque No/Transaction no</td>
                            <td>{{ $sd_payment->transaction_number }}</td>
                        </tr>
                        {{-- <tr>
                            <td class="bg-light" width="40%">Remarks</td>
                            <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus fuga temporibus molestias facere voluptatum? Doloribus iusto quos nihil.</td>
                        </tr> --}}
                        <tr>
                            <td class="bg-light" width="40%">Payment Status</td>
                            <td>{{ $sd_payment->status->name }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Payment received by</td>
                            <td>{{ $sd_payment->createdBy->name }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Paid amount (Rs)</td>
                            <td class="fw-semibold">{{ numberFormat($sd_payment->amount,2) }}</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Balance amount (Rs)</td>
                            <td class="fw-semibold">{{ numberFormat($sd_payment->balance,2) }}</td>
                        </tr>
                        <tr class="border-0">
                            <td class="border-0 text-start text-dark">Thank You.!</td>
                            <td class="border-0 text-end text-muted" style="font-size:12px;">This is computer generated receipt.</td>
                        </tr>    
                    </tbody>
                </table>
            </div>
        </div>
        <div class="p-3">
            <button class="btn btn-primary" onclick="printDiv('printableArea')"><i class="bi bi-printer"></i>&nbsp;Print Receipt</button>
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
    body {
        background-color: #F7F7F7;
    }
</style>
@endpush