{{-- Display Gas bill --}}

@extends('layouts.layout')

@section('title', 'View Gas Bill')

@section('page-title', 'Gas Bill - ' . $invoice->invoice_number)

@section('page-content')
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
                                                    <span style="font-size: 12px;line-height: 2;text-transform: uppercase;">Megha City Gas Distribution Private Limited</span><br>
                                                    RS 86/2D2, CHOPPARAMETLA VILLAGE, AGIRIPALLI MANDAL, Krishna, Andhra Pradesh                                                    <br/>
                                                    HSN No : 27112100                                                </p>
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
                                                            <td style="font-size: 12px;">112500038</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;width: 40px;">Name</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="text-align: left;"><b>MEGHA ENGINEERING and INFRASTRUCTURE LTD (STAFF ACCOMMODATION AGP MAIN)</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;width: 40px;">Address</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="text-align: left;">
                                                                2-69/1/34,GROUND FLOOR, AGIRIPALLI,<br>
                                                                Agiripalli,<br/>
                                                                521211<br/>
                                                                Krishna - Andhra Pradesh.<br/>
                                                                Landmark : OPP: GOVT JUNIOR COLLEGE</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;width: 40px;">Mobile</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="text-align: left;">9491982450</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left;width: 40px;">Email</td>
                                                            <td style="width:1px;">:</td>
                                                            <td style="text-align: left;">testemail@gmail.com</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-borderless mb-0" style="table-layout: fixed; width: 100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="3" style="background-color: #fdd835;text-align: center;font-size: 10px;padding: 5px !important;"><b>Current Bill Details ప్రస్తుత బిల్లు వివరాలు</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3"><b>Bill Details - Tax Invoice<br/>బిల్లు వివరాలు - పన్ను ఇన్‌వాయిస్</b></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;width: 10%;">&nbsp;</th>
                                                            <th style="text-align: center;border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Part A (Sale Of Natural Gas )<br/>పార్ట్ - ఏ (సహజ వాయువు విక్రయం)</th>
                                                            <th style="text-align: right;border-bottom: 1px solid #000000;width: 27%;">(&nbsp;&#8377;&nbsp;)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.1</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Gas Consumption Charges గ్యాస్ వినియోగ ఛార్జీలు</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">
                                                                634.36                                                            </td>
                                                        </tr>
                                                        <tr>
                                                                                                                        <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.2</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">VAT Value @ 5.00%&nbsp;వ్యాట్ విలువ</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">
                                                                31.72                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.3</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Arrears: Unpaid dues up to previous bill<br/>బకాయిలు:  గతంలో  చెల్లించని బిల్లు మొత్తం</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">6,961.34</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.4</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Credit Balance:- Advance/Excess paid<br/>క్రెడిట్ బ్యాలెన్స్:- అడ్వాన్స్/అదనపు చెల్లింపు మొత్తం</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                    </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.5</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Adjustment for Estimated Bills అంచనా వేసిన బిల్లుల సర్దుబాటు మొత్తం</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.6</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Discount/Rebate డిస్కౌంట్/రిబేటు</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">1.7</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Late Payment Charges</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;"><b>Total Charges (Part A) మొత్తం ఛార్జీలు</b></td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">
                                                                <strong>7,627.42</strong>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;width: 10%;">&nbsp;</th>
                                                            <th style="text-align: center;border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Part - B (Charges) పార్ట్ - బి</th>
                                                            <th style="text-align: right;border-bottom: 1px solid #000000;width: 27%;">(&nbsp;&#8377;&nbsp;)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                                                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.1</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Fixed Daily Charges రోజువారీ ఛార్జీలు</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.2</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Other Charges ఇతర ఛార్జీలు</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.3</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Minimum Consumption Charges కనిష్ట ఛార్జీలు</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.4</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Total Taxable Charges పన్ను విధించదగిన మొత్తం</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">
                                                                0.00                                                                    </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.5</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">SGST @ 9% రాష్ట్ర జిఎస్టి</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.6</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">CGST @ 9% కేంద్ర జిఎస్టి</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">2.7</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Estimation Charges</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;"><b>Total Charges (Part B) పూర్తి మొత్తం</b></td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">
                                                                0.00                                                                    </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;">
                                                    <thead>
                                                        <tr>
                                                            <th style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;width: 10%;">&nbsp;</th>
                                                            <th style="text-align: center;border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Part - C (Security Deposit) పార్ట్ - సి</th>
                                                            <th style="text-align: right;border-bottom: 1px solid #000000;width: 27%;">(&nbsp;&#8377;&nbsp;)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">3.1</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Security Deposit Payable<br/>చెల్లించవలసిన సెక్యూరిటీ డిపాజిట్</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">3.2</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Consumptioin Security Deposit<br/>వినియోగ భద్రతా డిపాజిట్</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">0.00</td>
                                                        </tr>
                                                                                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;"><b>Total Charges (Part C) మొత్తం ఛార్జీలు</b></td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">
                                                                0.00                                                                            
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;"><b>Total Payable(A+B+C)<br/>చెల్లించవలసిన మొత్తం (ఎ+బి+సి)</b></td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;"><b>7,627.42</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">&nbsp;</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;">Total Security Deposit Paid<br/>చెల్లించిన మొత్తం సెక్యూరిటీ డిపాజిట్</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;">4,500.00</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <td style="width: 65%;padding-right: 0px !important;padding-top: 4px !important;padding-bottom: 0px !important;">
                                                <table class="table table-borderless" style="width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 5px;">
                                                <tbody>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Invoice No.&nbsp;:&nbsp;241211307036<br/>{{ __('bill.invoice_no') }}&nbsp;:</td>
                                                        <td style="border-bottom: 1px solid #000000;">Bill Date&nbsp;:&nbsp;<strong>03-12-2024</strong><br/>{{ __('bill.bill_date') }}&nbsp;:</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Invoice Type&nbsp;:&nbsp;Retail<br/>{{ __('bill.invoice_type') }}&nbsp;:&nbsp;{{ __('bill.retail') }}</td>
                                                        <td style="border-bottom: 1px solid #000000;">Due Date&nbsp;:&nbsp;<strong>18-12-2024</strong><br/>{{ __('bill.due_date') }}&nbsp;:</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Credit Amount (If any)<br/>{{ __('bill.credit_amount') }}&nbsp;:</td>
                                                        <td style="border-bottom: 1px solid #000000;font-size: 12px;"><strong>&#8377;&nbsp;0.00</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">Amount Payable<br/>{{ __('bill.payable_amount') }}</td>
                                                        <td style="border-bottom: 1px solid #000000;font-size: 12px;"><strong>&#8377;&nbsp;7,627.34</strong>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;">After Due Date&nbsp;<span style="font-size: 8px;">(LPC Applicable)</span><br/>{{ __('bill.after_due_date') }}</td>
                                                        <td style="border-bottom: 1px solid #000000;font-size: 12px;"><strong>&#8377;&nbsp;
                                                            7,647.34</strong>
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
                                                            <td colspan="3" style="background-color: #fdd835;text-align: center;padding: 5px !important;"><b>Bill Details Of Consumption Cycle వినియోగ చక్రానికి సంబంధించిన బిల్లు వివరాలు</b></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-borderless" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;margin-top: 1px;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 15%;text-align: center;">Meter No<br/>మీటర్ నం</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 13%;text-align: center;">Previous Billing Date<br/>మునుపటి తేదీ</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 13%;text-align: center;">Current Billing Date<br/>ప్రస్తుత తేది</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 10%;text-align: center;">Previous Meter Reading<br/>మునుపటి మీటర్ రీడింగ్</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 10%;text-align: center;">Current Meter Reading<br/>ప్రస్తుత మీటర్ రీడింగ్</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 7%;text-align: center;">No. of days<br/>రోజుల సంఖ్య</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 15%;text-align: center;">Units Consumed (SCM)<br/>వినియోగించిన యూనిట్లు (ఎస్సీఎమ్)</td>
                                                            <!-- <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 10%;text-align: center;">PRICE PER UNIT</td> -->
                                                            <td style="border-bottom: 1px solid #000000;font-size: 8px;width: 17%;text-align: center;">Gas Consumption Charges(Exec. VAT)<br/>గ్యాస్ వినియోగ ఛార్జీలు<br/>(ఎగ్జిక్యూ. వ్యాట్)</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">17703158</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">08-10-2024</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">03-12-2024</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">792.481</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">805.653</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">56</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">13.172</td>
                                                            <!-- <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;"></td> -->
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;font-size: 8px;text-align: center;">634.36</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;text-align: right;"><b>Total</b></td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">56</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">13.172</td>
                                                            <!-- <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;"></td> -->
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;font-size: 8px;text-align: center;">634.36</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table class="table table-borderless" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 2px;">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="3" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;text-align: left;">Average Cons. in Last 3 billing cycles చివరి 3 బిల్లింగ్ వ్యవధిలో సగటు వినియోగం</td>
                                                            <td colspan="2" style="text-align: center;border-bottom: 1px solid #000000;">15.145/0.234 scm/day ఎస్సీఎమ్/రోజు</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;text-align: left;">Price/SCM in INR (w.e.f 03.12.2024) ధర/ఎస్సీఎమ్</td>
                                                            <td colspan="2" style="text-align: center;border-bottom: 1px solid #000000;">
                                                                51.00 (incl. VAT) వ్యాట్ తో సహా                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="5" style="text-align: center;border-bottom: 1px solid #000000;font-size: 12px;">Breakup Of Price /per SCM in INR రూపాయల ధర లో /ప్రతి ఎస్సీఎమ్ కి బ్రేక్అప్ (₹)(&#8377;)</td>
                                                        </tr>
                                                        <tr>                                                        
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Basic Cost of gas</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Supply & Distribution cost</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Margin</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">VAT</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Total<br/>మొత్తం</td>
                                                        </tr>
                                                        <tr>                                                        
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">27.37</td>
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">19.96</td>
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">1.24</td>
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">2.43</td>
                                                            <td style="border-bottom: 2px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">51.00</td>
                                                        </tr>
                                                        <tr>                                                        
                                                            <td colspan="2" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Billing Period<br/>బిల్లింగ్ వ్యవధి</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Consumption(SCM)<br/>వినియోగం(ఎస్సీఎమ్)</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Price<br/>ధర(₹)(&#8377;)</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">Total<br/>మొత్తం</td>
                                                        </tr>
                                                        <tr>                                                        
                                                            <td colspan="2" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">
                                                                08 Oct - 01 Nov                                                                        </td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">
                                                                5.645                                                                        </td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">47.62</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;font-size: 8px;text-align: center;">268.81</td>
                                                        </tr>
                                                        <tr>                                                        
                                                            <td colspan="2" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">
                                                                01 Nov - 03 Dec                                                                        </td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">
                                                                7.526                                                                        </td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">48.57</td>
                                                            <td style="text-align: right;border-bottom: 1px solid #000000;font-size: 8px;text-align: center;">365.56</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: right;">Totals</td>
                                                            <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;text-align: center;">634.36</td>
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
                                                &gt;&nbsp;బిల్లింగ్ మరియు చెల్లింపులకు సంబంధించిన సమాచారం కొరకు దయచేసి మా వినియోగదారు పోర్టల్ www.meghagas.comని సందర్శించ గలరు మరియు PlayStore/  Appstoreలో అందుబాటులో ఉన్న మా MeghaGas యాప్‌ని డౌన్‌లోడ్ చేసుకో గలరు.&nbsp;/&nbsp;Please visit our consumer portal @www.meghagas.com for all billing and payments related information. Please download our MeghaGas app available on PlayStore and Appstore.<br />
                                                &gt;&nbsp;గడువు తేదీ తర్వాత, చెల్లింపులో ఏదైనా ఆలస్యమైతే నెలకు @2% ఆలస్య చెల్లింపు ఛార్జీలను (LPC) విధించబడతాయని దయచేసి గమనించగలరు.&nbsp;/&nbsp;Please note that any delay in payment post due date, shall attract Late Payment Charges @2% per month.<br>
                                                &gt;&nbsp;గమనిక : గడువు తేదీకి బిల్లు చెల్లించనట్లయితే, మరుసటి రోజు కనెక్షన్ డిస్‌కనెక్ట్ చేయబడుతుందని మనవి.&nbsp;/&nbsp;NOTICE: On event of non-payment of bill post due date, connection shall be disconnected on the following day.<br>
                                                &gt;&nbsp;ఇప్పుడు మీరు మీ బిల్లులను ఆన్‌లైన్‌లో @ www.meghagas.com మరియు UPI (BHIM యాప్, GPay, PhonePe, Paytm, Amazon Pay మొదలైనవి) ద్వారా చెల్లించవచ్చు.&nbsp;/&nbsp;Now you can pay Online @www.meghagas.com or Pay via UPI (BHIM App, GPay, PhonePe, Paytm, Amazon Pay, etc.)<br>
                                                &gt;&nbsp;మీరు "మేఘా సిటీ గ్యాస్ డిస్ట్రిబ్యూషన్ ప్రైవేట్ లిమిటెడ్"కి అనుకూలంగా మీరు NEFT/RTGS మాత్రమే చెల్లించగలరు.&nbsp;/&nbsp;You can  pay NEFT/RTGS only in favour of “Megha City Gas Distribution Private Limited”.<br>
                                                &gt;&nbsp;ఏవైనా ఫిర్యాదులు/సూచనల కోసం దయచేసి 040-46565555లో సంప్రదించగలరు  లేదా customercare@meghagas.comలో మాకు వ్రాయగలరు&nbsp;/&nbsp;For any complaints/suggestions please Contact on 040-46565555 or write to us on customercare@meghagas.com.<br>
                                                &gt;&nbsp;దయచేసి మీ బిల్లు చెల్లింపును మేఘా గ్యాస్ ప్రతినిధికి నగదు రూపంలో చెల్లించవద్దు.&nbsp;/&nbsp;Please do NOT PAY CASH against your Bill to any person/ Megha Gas representative.
                                                <br>
                                                &gt;&nbsp;మా సేవల టారిఫ్ కార్డ్ కోసం, దయచేసి www.meghagas.com/domestic-png సందర్శించండి.&nbsp;/&nbsp;For Tarrif card of our services, please visit www.meghagas.com/domestic-png
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
                                                        <tr>
                                                            <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;">
                                                                03-08-2024  To  08-10-2024                                                                    </td>
                                                            <td style="border-right: 1px solid #000000;border-bottom: 1px solid #000000;text-align: center;">
                                                                14.755                                                                    </td>
                                                            <td style="border-bottom: 1px solid #000000;text-align: center;">
                                                                0.224                                                                    </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-right: 1px solid #000000;text-align: center;">01-06-2024  To  03-08-2024</td>
                                                            <td style="border-right: 1px solid #000000;text-align: center;">17.122</td>
                                                            <td style="text-align: center;">0.272</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td style="vertical-align: bottom !important;">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="text-align: center;"><img src="{{ asset('img/bill/auth-signature.jpg') }}" alt="signature" width="80"><br/><strong style="font-size: 7px;">(&nbsp;Authorised Signatory&nbsp;/&nbsp;అధికారిక సంతకం&nbsp;)</strong></td>
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