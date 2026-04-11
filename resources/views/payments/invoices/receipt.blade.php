{{-- Payments landing page --}}
@extends('layouts.layout')
@section('title', 'Payment Receipt')
@section('page-title', 'Payment Receipt#')

@section('page-content')
    <div class="d-flex align-content-md-start">
        <div class="a4-page pb-2 border bg-white" id="printableArea">
            <div class="row p-4 pb-2">
                <div class="col-sm-4 border-bottom border-success-subtle">
                    <img src="{{ asset('img/logo.png') }}" alt="MeghaGas" class="img-fluid">
                </div>
                <div class="col-sm-8 text-end border-bottom border-success-subtle">
                    <span class="fs-4 fw-semibold">PAYMENT RECEIPT</span>
                </div>
            </div> 
            <div class="px-4">           
                 <img src="{{ asset('img/watermark_logo.png') }}" alt="MeghaGas" width="320" class="watermark-overlay">
                <div class="row">
                    <div class="col-sm-7">
                        <address>
                            <span class="fw-semibold">Megha Gas Distribution Privated Limited. </span><br>
                                S-2, Technocrat Industrial Estate,<br>
                                Balanagar,Hyderabad,<br>
                                Telangana - 500 037<br>
                             {{-- Address component --}}
                            {{-- <x-master.gaAddress :ga-id="$sd_payment->consumer->ga_id"/> --}}
                        </address>
                        <address>
                            <strong>MEGHA ENGINEERING and INFRASTRUCTURE LTD (STAFF ACCOMMODATION AGP MAIN)</strong><br>
                            2-69/1/34, GROUND FLOOR, AGIRIPALLI,<br>
                            Landmark : OPP: GOVT JUNIOR COLLEGE,<br>
                            Agiripalli,<br>
                            Krishna,<br>
                            Andhra Pradesh - 521211<br>
                        </address>
                    </div>
                    <div class="col-sm-5">
                        <div class="row gy-1 gx-2">
                            <div class="col-sm-6 text-end">Invoice Number:</div>
                            <div class="col-sm-6">260211031376</div>
                            <div class="col-sm-6 text-end">Invoice Date:</div>
                            <div class="col-sm-6">11-04-2026</div>
                            <div class="col-sm-6 text-end">Invoice due date:</div>
                            <div class="col-sm-6">26-04-2026</div>
                            <div class="col-sm-6 text-end">Amount Payable:</div>
                            <div class="col-sm-6">10,420.35</div>
                            <div class="col-sm-6 text-end">After Due Date:</div>
                            <div class="col-sm-6">10,440.35</div>
                        </div>
                    </div>
                </div> 
                <div class="text-center p-2 border border-success text-success fw-semibold mt-1">Receipt Amount Paid successfully.</div>                
                <div class="fw-semibold fs-5 mt-1">Payment Details</div>
                <table class="table table-bordered table-success fs-6 table-sm align-middle">                    
                    <tbody>
                        <tr>
                            <td class="bg-light" width="40%">Consumber Number</td>
                            <td>112500038</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Invoice No</td>
                            <td>260211031376</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Invoice Date</td>
                            <td>11-04-2026</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Payment Date</td>
                            <td>12-04-2026</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Payment type</td>
                            <td>Cash Payment</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Cheque No / Transaction no</td>
                            <td class="fw-semibold">ref12345678</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Paid amount (Rs)</td>
                            <td class="fw-semibold">101.00</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Service Charge (Rs)</td>
                            <td class="fw-semibold">0.00</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Due amount (Rs)</td>
                            <td class="fw-semibold">10,319.35</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <h5 class="m-0 p-0">Important Note :</h5>
                                <p class="fs-sm p-0 m-0">
                                    <ul class="fs-sm">
                                        <li>Please visit our consumer portal @www.meghagas.com for all billing and payments related information. Please download our MeghaGas app available on
PlayStore and Appstore.</li>
                                        <li>Please note that any delay in payment post due date, shall attract Late Payment Charges @2% per month.</li>
                                        <li>NOTICE: On event of non-payment of bill post due date, connection shall be disconnected on the following day.</li>
                                        <li>Now you can pay Online @www.meghagas.com or Pay via UPI (BHIM App, GPay, PhonePe, Paytm, Amazon Pay, etc.)</li>
                                        <li>You can pay NEFT/RTGS only in favour of “Megha City Gas Distribution Private Limited”.</li>
                                        <li>For any complaints/suggestions please Contact on 040-46565555 or write to us on customercare@meghagas.com.</li>
                                        <li>PLEASE DO NOT PAY CASH against your Bill to any person/representative of Megha Gas.</li>
                                    </ul>
                                </p>

                            </td>
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