{{-- Display Gas bill --}}

@extends('layouts.layout')

@section('title', 'View Gas Bill')

@section('page-title', 'Gas Bill - ' . $invoice->invoice_number)

@section('page-content')
    @php 
    @endphp
    <div class="previewPrint-area" id="print-area">
        <div class="bg-white overflow-hidden position-relative rounded-0 printView-table">

            <table class="table table-borderless mb-0" style="width: 100%;">
                <thead>
                    <tr>
                        <td style="padding: 0px !important;">
                            <div class="cgd_invoice_head">
                                <table class="table mb-0" style="z-index: 9;position: relative;width: 100%">
                                    <tbody>
                                        <tr>
                                            <td style="vertical-align: middle;border-top: none;border-bottom: none;width: 182px;text-align: left;">
                                                <img src="{{ asset('img/bill/bill-logo.png') }}" alt="Logo" width="120" style="padding-left: 15px;">
                                            </td>
                                            <td style="border-top: none;border-bottom: none;text-align: center;background-color: #FDD835;transform: skewX(-20deg);-webkit-transform: skewX(-20deg);vertical-align: middle;">
                                                <p style="font-size: 7px;font-weight: bold;margin-top:5px;margin-bottom: 5px;line-height: initial;color: #055688;transform: skewX(20deg);-webkit-transform: skewX(20deg);">
                                                    <span style="font-size: 12px;line-height: 2;text-transform: uppercase;">Megha City Gas Distribution Private Limited</span>
                                                    <br>RS 86/2D2, CHOPPARAMETLA VILLAGE, AGIRIPALLI MANDAL, Krishna, Andhra Pradesh<br/>HSN No : 27112100 </p>
                                            </td>
                                            <td style="vertical-align: middle;border-top: none;border-bottom: none;width: auto;display: flex;align-items: center;text-align: right;">
                                                <img src="{{ asset('img/bill/customer-service.png') }}" alt="24X7" width="45" style="padding-left: 10px;">
                                                <span style="font-size: 9px;color: #ffffff;line-height: 1.75;padding-left: 10px;padding-top: 6px;font-weight: bold;">040-46565 555<br/>040-69085 555<br/><span style="font-size: 8px;">Emergency&nbsp;:&nbsp;</span>1800 123 1803</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="cgd_invoice_head_bg"></div>
                                <div class="cgd_invoice_head_bg1"></div>
                            </div>                                    
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding-left: 0px !important;padding-right: 0px !important;padding-top: 0px !important;">
                            <div class="cgd-invoice-body">
                                <table class="table table-borderless mb-0" style="table-layout: fixed; width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td style="width: 35%;padding-left: 0px !important;padding-bottom: 0px !important;">
                                                <table class="table table-borderless mb-0" style="table-layout: fixed; width: 100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: left;width: 55px;font-size: 12px;">CRN</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="font-size: 12px;">{{ $invoice->consumer->crn }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;width: 40px;">Name</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="text-align: left;"><b>{{ $invoice->consumer->name }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;width: 40px;">Address</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="text-align: left;">
                                                                {{ $invoice->consumer->hno }}<br>
                                                                {{ $invoice->consumer->street }},{{ $invoice->consumer->city }}<br/>
                                                                {{ $invoice->consumer->pincode }}<br/>
                                                                {{ $invoice->consumer->district->name }} - {{ $invoice->consumer->state->name }}.</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;width: 40px;">Mobile</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="text-align: left;">{{ $invoice->consumer->phone }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;width: 40px;">Email</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="text-align: left;">{{ $invoice->consumer->email }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-borderless mb-0" style="table-layout: fixed; width: 100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="3" style="background-color: #fdd835;text-align: center;font-size: 10px;padding: 5px !important;"><b>Current Bill Details {{ __('bill.current_bill_details') }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><b>Bill Details - Tax Invoice<br/>{{ __('bill.bill_details'). ' - ' . __('bill.tax_invoice') }}</b></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;width: 10%;">&nbsp;</th>
                                                            <th style="text-align: center;border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Part A (Sale Of Natural Gas )<br/>{{ __('bill.part_a') }} ({{ __('bill.sale_of_natural_gas') }})</th>
                                                            <th style="text-align: right;border-bottom: 1px solid #000000;width: 27%;">(&nbsp;&#8377;&nbsp;)</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $parta = $invoice->payable_amount+$lpc;
                                                    @endphp
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.1</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Gas Consumption Charges {{ __('bill.gas_consumption_charges') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ $invoice->base_amount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.2</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">VAT Value @ {{ numberFormat($invoice->tax_value,2) }}%&nbsp;{{ __('bill.vat_value') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ $invoice->tax_amount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.3</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Arrears: Unpaid dues up to previous bill<br/>{{ __('bill.arrears') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.4</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Credit Balance:- Advance/Excess paid<br/>{{ __('bill.credit_balance') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ numberFormat($invoice->credit_amount ?? 0,2) }}</td>
                                                    </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.5</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Adjustment for Estimated Bills {{ __('bill.adjustment_for_estimated_bills') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ $invoice->total_amount }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.6</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Discount/Rebate {{ __('bill.discount') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.7</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Late Payment Charges {{ __('bill.late_payment_charges') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ numberFormat($lpc,2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;"><b>Total Charges (Part A) {{ __('bill.total_charges') }}</b></td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">
                                                                <strong>{{ numberFormat($parta,2) }}</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;width: 10%;">&nbsp;</th>
                                                            <th style="text-align: center;border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Part - B (Charges) {{ __('bill.part_b') }}</th>
                                                            <th style="text-align: right;border-bottom: 1px solid #000000;width: 27%;">(&nbsp;&#8377;&nbsp;)</th>
                                                        </tr>
                                                    </thead>
                                                    @php
                                                        $partb = $rental->balance_amount ?? 0;
                                                    @endphp
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.1</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Fixed Daily Charges {{ __('bill.fixed_daily_charges') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ numberFormat($rental->base_amount ?? 0, 2); }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.2</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Other Charges {{ __('bill.other_charges') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.3</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Minimum Consumption Charges {{ __('bill.minimum_consumption_charges') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.4</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Total Taxable Charges {{ __('bill.total_taxable_charges') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ numberFormat($rental?->tax_amount,2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.5</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">SGST @ 9% {{ __('bill.sgst') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ numberFormat($rental?->tax_amount/2,2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.6</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">CGST @ 9% {{ __('bill.cgst') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ numberFormat($rental?->tax_amount/2,2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.7</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Estimation Charges {{ __('bill.estimation_charges') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;"><b>Total Charges (Part B) {{ __('bill.total_charges') }}</b></td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ $partb; }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;width: 10%;">&nbsp;</th>
                                                            <th style="text-align: center;border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Part - C (Security Deposit) {{ __('bill.part_c') }} ({{ __('bill.security_deposit') }})</th>
                                                            <th style="text-align: right;border-bottom: 1px solid #000000;width: 27%;">(&nbsp;&#8377;&nbsp;)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $sd = ($invoice->consumer->scheme->scheme->emi_amount > 0) ? $invoice->consumer->scheme->scheme->emi_amount : 0;
                                                            $consumption_deposit = 0;
                                                            $total_emis = ($sd > 0) ? round($invoice->consumer->scheme->security_deposit / $sd) : 0;
                                                            $partc = $sd;
                                                        @endphp
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">3.1</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Security Deposit Payable<br/>{{ __('bill.security_deposit_payable') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ $sd }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">3.2</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Consumptioin Security Deposit<br/>{{ __('bill.consumption_security_deposit') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ numberFormat($consumption_deposit,2) }}</td>
                                                        </tr>
                                                        @if ($sd > 0)
                                                            <tr>
                                                                <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">3.3</td>
                                                                <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Current EMI No<br/>{{ __('bill.consumption_security_deposit') }}</td>
                                                                <td style="text-align: right;border-bottom: 1px solid #000000;">{{ $invoice->consumer->sdPayment->last()?->emi_no ." / ". $total_emis}}</td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;"><b>Total Charges (Part C) {{ __('bill.total_charges') }}</b></td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ $partc }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;"><b>Total Payable(A+B+C)<br/>{{ __('bill.total_payable') }} ({{ __('bill.a_b_c') }})</b></td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;"><b>{{ numberFormat($parta+$partb+$partc,2) }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Total Security Deposit Paid<br/>{{ __('bill.total_security_deposit_paid') }}</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">{{ numberFormat($invoice->consumer->scheme->paid_deposit,2) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <td style="width: 65%;padding-right: 0px !important;padding-top: 4px !important;padding-bottom: 0px !important;">
                                                <table class="table table-borderless" style="width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 5px;">
                                                <tbody>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Invoice No.&nbsp;:&nbsp;{{ $invoice->invoice_number }}<br/>{{ __('bill.invoice_no') }}&nbsp;:</td>
                                                        <td style="border-bottom: 1px solid #000000;">Bill Date&nbsp;:&nbsp;<strong>{{ dateFormat($invoice->invoice_date) }}</strong><br/>{{ __('bill.bill_date') }}&nbsp;:</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Invoice Type&nbsp;:&nbsp;{{ $invoice->invoiceType->name }}<br/>{{ __('bill.invoice_type') }}&nbsp;:&nbsp;{{ __('bill.retail') }}</td>
                                                        <td style="border-bottom: 1px solid #000000;">Due Date&nbsp;:&nbsp;<strong>{{ dateFormat($invoice->due_date) }}</strong><br/>{{ __('bill.due_date') }}&nbsp;:</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Credit Amount (If any)<br/>{{ __('bill.credit_amount') }}&nbsp;:</td>
                                                        <td style="border-bottom: 1px solid #000000;font-size: 12px;"><strong>&#8377;&nbsp;{{ numberFormat($invoice->credit_amount,2) }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Amount Payable<br/>{{ __('bill.payable_amount') }}</td>
                                                        <td style="border-bottom: 1px solid #000000;font-size: 12px;"><strong>&#8377;&nbsp;{{ numberFormat($invoice->payable_amount,2) }}</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">After Due Date&nbsp;<span style="font-size: 8px;">(LPC Applicable)</span><br/>{{ __('bill.after_due_date') }}</td>
                                                        <td style="border-bottom: 1px solid #000000;font-size: 12px;"><strong>&#8377;&nbsp;{{ numberFormat($invoice->payable_amount+20,2) }}</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;">Disconnection Date&nbsp;:&nbsp;<span style="font-size: 8px;">(if bill not paid within Due Date)</span><br/>{{ __('bill.disconnection') }}</td>
                                                        <td style="text-align: center;vertical-align: middle;font-size: 12px;"><span style="color: red;">IMMEDIATE&nbsp;<span style="font-size:8px;">({{ __('bill.immediate') }})</span></span></td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                                <table class="table table-borderless" style="table-layout: fixed; width: 100%;margin-bottom: 5px;">
                                                    <tbody>
                                                        <tr>
                                                            @if (rand(1, 9) % 2 == 0)
                                                                <td style="padding: 0px !important;">
                                                                    <div style="text-align: center;font-size: 10px;padding: 5px !important;"><b>To Pay Scan QR Code</b></div>
                                                                    <img src="{{ asset('img/bill/pay-link-qr.png') }}" alt="Payment QR" style="height: 225px; width: 100%;">
                                                                </td>
                                                                <td style="padding: 0px !important;">
                                                                    <img src="{{ asset('img/bill/promotion2.jpg') }}" alt="promotion img" style="height: 250px;width: 100%;">
                                                                </td>
                                                            @else
                                                                <td style="padding: 0px !important;">
                                                                    <img src="{{ asset('img/bill/promotion1.jpg') }}" alt="promotion img" style="height: 250px;width: 100%;">
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-borderless" style="table-layout: fixed; width: 100%;margin-bottom: 5px;">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="3" style="background-color: #fdd835;text-align: center;padding: 5px !important;"><b>Bill Details Of Consumption Cycle {{ __('bill.bill_details_of_consumption_cycle') }}</b></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-borderless" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;margin-top: 1px;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 15%;text-align: center;">Meter No<br/>{{ __('bill.meter_no') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 13%;text-align: center;">Previous Billing Date<br/>{{ __('bill.previous_billing_date') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 13%;text-align: center;">Current Billing Date<br/>{{ __('bill.current_billing_date') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 10%;text-align: center;">Previous Meter Reading<br/>{{ __('bill.previous_meter_reading') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 10%;text-align: center;">Current Meter Reading<br/>{{ __('bill.current_meter_reading') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 7%;text-align: center;">No. of days<br/>{{ __('bill.no_of_days') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 15%;text-align: center;">Units Consumed (SCM)<br/>{{ __('bill.units_consumed') }}</td>
                                                            <!-- <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 10%;text-align: center;">PRICE PER UNIT</td> -->
                                                            <td style="border-bottom: 1px solid #000000;font-size: 8px;width: 17%;text-align: center;">Gas Consumption Charges(Exec. VAT)<br/>{{ __('bill.gas_consumption_charges(exec. vat)') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ $invoice->consumer->activeMeter->meter_no }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ dateFormat($invoice->consumption->date_from) }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ dateFormat($invoice->consumption->date_to) }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($invoice->consumption->prev_reading,2) }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($invoice->consumption->curr_reading,2) }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ $invoice->consumption->days }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($invoice->consumption->net_consumption,3) }}</td>
                                                            <!-- <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;"></td> -->
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($invoice->base_amount,2) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;text-align: right;"><b>Total</b></td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ $invoice->consumption->days }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($invoice->consumption->net_consumption,3) }}</td>
                                                            <!-- <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;"></td> -->
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($invoice->base_amount,2) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-borderless" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 2px;">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="3" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;text-align: left;">Average Cons. in Last 3 billing cycles {{ __('bill.average_cons_in_last_3_billing_cycles') }}</td>
                                                            <td colspan="2" style="text-align: center;border-bottom: 1px solid #000000;">{{ $avg_scm ?? 0 }}/{{ $avg_scm_per_day ?? 0 }} scm/day {{ __('bill.scm/day') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;text-align: left;">Price/SCM in INR (w.e.f 03.12.2024) {{ __('bill.price/scm_in_inr') }}</td>
                                                            <td colspan="2" style="text-align: center;border-bottom: 1px solid #000000;">
                                                                {{ numberFormat($price->rsp,2) }} (incl. VAT) {{ __('bill.incl_vat') }}                                                         </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: center;border-bottom: 1px solid #000000;font-size: 12px;">Breakup Of Price /per SCM in INR (&#8377;) {{ __('bill.breakup_of_price/per_scm_in_inr') }}</td>
                                                        </tr>
                                                        <tr>                                                        
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Basic Cost of gas</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Supply & Distribution cost</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Margin</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">VAT</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Total<br/>{{ __('bill.total') }}</td>
                                                        </tr>
                                                        <tr>                                                        
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($price->basic,2) }}</td>
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($price->supply,2) }}</td>
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($price->margin,2) }}</td>
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($price->tax_price,2) }}</td>
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($price->rsp,2) }}</td>
                                                        </tr>
                                                        <tr>                                                        
                                                            <td colspan="2" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Billing Period<br/>{{ __('bill.billing_period') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Consumption(SCM)<br/>{{ __('bill.consumption(scm)') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Price (&#8377;)<br/>{{ __('bill.price') }}</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Total<br/>{{ __('bill.total') }}</td>
                                                        </tr>
                                                        @foreach ($invoice->consumption->breakupPeriods as $row)
                                                            <tr>                                                        
                                                                <td colspan="2" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ dateFormat($row['start_date']) }} - {{ dateFormat($row['end_date']) }}</td>
                                                                <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($row['consumption'], 3) }}</td>
                                                                <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($row['unit_price'], 2) }}</td>
                                                                <td style="text-align: right;border-bottom: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($row['total_price'], 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td colspan="4" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: right;">Totals</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">{{ numberFormat($invoice->consumption->consumptionDetails->sum('total_price'),2) }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table mb-0" style="table-layout: fixed; width: auto;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;font-size: 8px !important;">
                                    <tbody>
                                        <tr>
                                            <td colspan="3">
                                                &gt;&nbsp;{{ __('bill.please visit our consumer portal @www.meghagas.com for all billing and payments related information. please download our meghagas app available on playstore and appstore.') }}&nbsp;/&nbsp;Please visit our consumer portal @www.meghagas.com for all billing and payments related information. Please download our MeghaGas app available on PlayStore and Appstore.<br />
                                                &gt;&nbsp;{{ __('bill.please note that any delay in payment post due date, shall attract late payment charges @2% per month.') }}&nbsp;/&nbsp;Please note that any delay in payment post due date, shall attract Late Payment Charges @2% per month.<br>
                                                &gt;&nbsp;{{ __('bill.notice: on event of non-payment of bill post due date, connection shall be disconnected on the following day.') }}&nbsp;/&nbsp;NOTICE: On event of non-payment of bill post due date, connection shall be disconnected on the following day.<br>
                                                &gt;&nbsp;{{ __('bill.now you can pay online @www.meghagas.com or pay via upi (bhim app, gpay, phonepe, paytm, amazon pay, etc.)') }}&nbsp;/&nbsp;Now you can pay Online @www.meghagas.com or Pay via UPI (BHIM App, GPay, PhonePe, Paytm, Amazon Pay, etc.)<br>
                                                &gt;&nbsp;{{ __('bill.you can pay neft/rtgs only in favour of “megha city gas distribution private limited”.') }}&nbsp;/&nbsp;You can  pay NEFT/RTGS only in favour of “Megha City Gas Distribution Private Limited”.<br>
                                                &gt;&nbsp;{{ __('bill.for any complaints/suggestions please Contact on 040-46565555 or write to us on customercare@meghagas.com.') }}&nbsp;/&nbsp;For any complaints/suggestions please Contact on 040-46565555 or write to us on customercare@meghagas.com.<br>
                                                &gt;&nbsp;{{ __('bill.please do not pay cash against your bill to any person/ megha gas representative.') }}&nbsp;/&nbsp;Please do NOT PAY CASH against your Bill to any person/ Megha Gas representative.
                                                <br>
                                                &gt;&nbsp;{{ __('bill.for tariff card of our services, please visit www.meghagas.com/domestic-png.') }}&nbsp;/&nbsp;For Tarrif card of our services, please visit www.meghagas.com/domestic-png
                                            </td>
                                        </tr>
                                        <tr style="border-top: 1px solid #000000;">
                                            <td style="border-right: 1px solid #000000;padding: 0px !important;">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: right;">TIN&nbsp;:</td>
                                                            <td>37623453075</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: right;">CIN&nbsp;:</td>
                                                            <td>U40106TG2021PTC154806</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: right;">GSTN&nbsp;:</td>
                                                            <td>37AAOCM9485E1ZS</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="border-right: 1px solid #000000;padding: 0px !important;">
                                                <table class="table mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="3" style="color:red;border-bottom: 1px solid #000000;text-align: center;"><b>Bill History</b></td>
                                                        </tr>                                                    
                                                        <tr>
                                                            <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;">Billing Period</td>
                                                            <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;">Units (scm)</td>
                                                            <td style="border-bottom: 1px solid #000000;text-align: center;">Cons/day (scm)</td>
                                                        </tr>
                                                        @foreach ($billHistory as $bill)
                                                            <tr>
                                                                <td style="border-right: 1px solid #000000;text-align: center;">{{ dateFormat($bill->consumption->bill_from) }} To {{ dateFormat($bill->consumption->bill_to) }}</td>
                                                                <td style="border-right: 1px solid #000000;text-align: center;">{{ numberFormat($bill->net_consumption, 3) }}</td>
                                                                <td style="text-align: center;">{{ numberFormat($bill->net_consumption / max($bill->consumption->days, 1),3) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="vertical-align: bottom !important;">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;"><img src="{{ asset('img/bill/auth-signature.jpg') }}" alt="signature" width="80"><br/><strong style="font-size: 7px;">(&nbsp;Authorised Signatory&nbsp;/&nbsp;{{ __('bill.authorised_signatory') }}&nbsp;)</strong></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-borderless mb-0" style="table-layout: fixed; width: 100%;overflow-wrap: break-word;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                DISCLAIMER : This bill is not a document for claiming any valid address proof of the PNG customer, for submitting before any Authority Body. Anybody claimimg or accepting the said bill to be valid address proof shall be doing it at their own risk and cost.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="cgd_invoice_in">
                            </div>
                            <br>
                            <div style="text-align: center; font-size: 12px;">
                                <br><br><strong>TERMS AND CONDITIONS<br>ATTENTION</strong><br><br>
                            </div>
                            <div>
                                <table class="table mb-0" style="table-layout: fixed; width: auto;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;font-size: 8px !important; width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <ol style="margin: auto; padding: 20px;">
                                                    <li style="padding-left: 10px;">Consumer shall make the full payment for the invoice raised on or before the due date of payment. Charges will be applied for late payments.</li>
                                                    <li style="padding-left: 10px; padding-top: 5px;">Piped Natural Gas (PNG) is liable to be disconnected in case of non-payment of a bill. The supply will be resumed only after outstanding dues along with the applicable interest and penalty are remitted. In addition, reconnection cgarges have ti be paid.</li>
                                                    <li style="padding-left: 10px; padding-top: 5px;">View updated prices and new payment options, visit our website www.meghagas.com or call our 24 hours customer care numbers 040-46565 555 / 040-69085 555</li>
                                                    <li style="padding-left: 10px; padding-top: 5px;">
                                                        <strong style="font-size: 10px;">WAYS TO PAY MEGHAGAS BILL THROUGH:<br></strong>
                                                        <table>
                                                            <tr>
                                                                <td>Cash Payment</td>
                                                                <td>-</td>
                                                                <td>At out branch offices</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Online Payment</td>
                                                                <td>-</td>
                                                                <td>Net banking, Debit & Credit Cards, etc.</td>
                                                            </tr>
                                                            <tr>
                                                                <td>E-Wallet Payment</td>
                                                                <td>-</td>
                                                                <td>PhonePe, GooglePay, Paytm, AmazonPay, etc.</td>
                                                            </tr>
                                                        </table>
                                                        <br>
                                                    </li>
                                                    <li style="padding-left: 10px; padding-top: 5px;">
                                                        <strong style="font-size: 10px;">Grievance redressal steps:<br></strong>
                                                        First step is to lodge your grievances with the 24 hours customer care number 040-46565 555 / 040-69085 555<br><br>
                                                        <strong style="text-decoration: underline;">Incharge Customer Complaint Cell (Name, Contact No & Email Id):<br></strong>
                                                        Customer can approach Incharge Customer Receiving Cell in case complaint is not resolved with 15 days of registering with customer care. Miss. P.Asha, Ph - 040-46565 555 Ext-001, inchargecustomercare@meghagas.com, (Mon-Sat, 10:00 AM to 5:00 PM)<br><br>
                                                        <strong style="text-decoration: underline;">Nodal Officer (Name, Contact No & Email Id):<br></strong>
                                                        Customer can approach Nodal Officer in case complaint is not resolved with 15 days of registering with incharge complaint receiving cell. Mr. M.David Raj, Ph - 040-46565 555 EXT-002, nodalofficer@meghagas.com, (Mon-Sat, 10:00 AM to 5:00 PM)<br><br>
                                                        <strong style="text-decoration: underline;">Appellate Authority (Name, Contact No & Email Id):<br></strong>
                                                        Customer can approach Appellate Authority in case complaint is not resolved with 15 days of registering with Nodal Officer. Mr XXXXXX, Ph - 040-46565 555 Ext-003, inchargecustomercare@meghagas.com, (Mon-Sat, 10:00 AM to 5:00 PM)<br><br>
                                                        Emergency Customer Care Number <strong>1800 123 1803</strong> [Please note that these numbers are to be used in case of an emergency (fire/leakage) only]<br>
                                                    </li>
                                                </ol>
                                                </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div>
                                    <br><strong style="font-size: 10px;">Bill Collection Center</strong>
                                </div>
                                <table class="table table-borderless" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;margin-top: 1px;">
                                    <thead>
                                        <tr>
                                            <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 15%;text-align: center;">GA Name</th>
                                            <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 15%;text-align: center;">Name of bill collection center</th>
                                            <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 30%;text-align: center;">Address of bill collection center</th>
                                            <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 30%;text-align: center;">Name and Contact Number</th>
                                            <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 10%;text-align: center;">Timings</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Agiripalli</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rs No: 86/2D2, Chopparametla, Agiripalli, Eluru (DT), AP - 521211</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rajamahendravarapu Eswara Rao,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Nunna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rs. No: 734/3B3, Nunna bypass road, Nunna, Vijayawada, Ap-521212</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Venkateswara rao Rasuri,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Agiripalli</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rs No: 86/2D2, Chopparametla, Agiripalli, Eluru (DT), AP - 521211</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rajamahendravarapu Eswara Rao,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Nuzvid </td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">D.No: 25/203/1&2, Gangadhar rao Hospital Road, NSP Colony, Nuzvid, Eluru (DT), Ap-521201</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">K Satyannarayana,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Gannavaram</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">D.No: 12-216, Beside Sub registar office, Near Konaye Cheruvu, Gannavarm, Krishna, Ap-521101</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Chitturi Ashok,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Tadigadapa & Yanamalakuduru</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">RS.No: 105/6,105/7, YSR Tadigadapa, Yanamalakuduru, Penamaluru, Ap-520007</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Pamarthi Siva Nagaraju,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Avanigadda</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">D.No: 6-196, Ward No 9, Avanigadda, Krishna (DT), Ap-521121</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Lakshminadha Rao,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Nagayalanka</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">RS.No: 481/1, 491/2&3, 492/1, Vakkabatlavari Palem, Nagayalanka, Krishna-521120</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Ashok Kondeti,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Gudlavalleru</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">D.No: 10-122, Near by Avasavlli rest home , Main road, Gudlavalleru, Krishna (DT), Ap-521356</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Marrivada Gangabhavni,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Pedana</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rs No: 17/18-8-16, Ward No:8, NTR Colony, Pedana, Krishna (DT), AP-531366</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Gunja Nagalakshmi,  040-46565 555</td>
                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div style="font-size: 10px;">
                                    <br><br>
                                    <u>Note:</u> Be very cautious about unsolicited calls or messages claiming to be from MeghaGas. MeghaGas never sends any message or makes any call for bill payments or immediate disconnection. Also, never sends any suspecious links asking to download apps. Beware of such fraudulent messages or calls. If you receive any such messages, call us immediatly on 040-46565 555 / 040-69085 555.
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div class="print-btn">
        <button type="button" class="btn btn-primary no-print" onclick="previewPrint()"><i class="bi bi-printer">&nbsp;</i>Print</button>
    </div>
@endsection
{{-- Styles --}}
@push('styles')
<style>
    body {
        color: #484747;
        overflow-x: hidden;
    }
    .previewPrint-area {
        position: relative;
        width: 20.5cm;
        height: auto;
        margin: 0px auto;
        box-shadow: 1px 2px 3px 0px #ccc;
        padding: 5px; 
        margin-top: 15px;
        margin-bottom: 15px;
    }
    .table > tbody > tr > td, .table > thead > tr > th {
        padding: 4px;
        font-size: 12px;
    }
    .print-btn {
        margin-bottom: 20px;
        margin-top: 20px;
        text-align: center;
    }
    /*Table header with yellow background color*/
    .cgd_invoice_head {
        height: auto;
        position: relative;
    }
    .cgd_invoice_head_bg {
        position: absolute;
        height: 100%;
        width: 32%;
        -webkit-transform: skewX(-20deg);
        transform: skewX(-20deg);
        top: 0px;
        right: -32px;
        overflow: hidden;
        background-color: #105493;
        z-index: 1;
    }
    .cgd_invoice_head_bg1 {
        position: absolute;
        height: 100%;
        width: 29%;
        -webkit-transform: skewX(-20deg);
        transform: skewX(-20deg);
        top: 0px;
        left: -27px;
        overflow: hidden;
        background-color: #FDD835;
        z-index: 1;
    }
    .table.table-font-small tbody tr td {
        font-size: 10px;
    }
    .printView-table .table tbody tr td, .printView-table .table thead tr th {
        padding: 1px 4px !important;
        font-size: 9px !important;
        color: #000000;
        line-height: 1.41;
    }
    @media print {
        @page {
        margin: 0cm;
        }
        body {
            margin: 10px !important;
        }
        .cgd-invoice-container {
            box-shadow: none !important;
            padding: 0px !important;
        }
        .previewPrint-area {
            margin: 5px !important;
        }

    }
    @media print {
        thead {
            display: table-row-group;
        }
    }
</style>
@endpush
{{-- Scripts --}}
@push('scripts')
    <script type="text/javascript">
        function previewPrint() {
            var restore = $('body').html();
            var print = $('#print-area').html();
            $('body').html(print);
            window.print();
            $('body').html(restore);
        }
    </script>
@endpush