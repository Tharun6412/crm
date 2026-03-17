{{-- Invoice show --}}

@extends('layouts.layout')

@section('title', 'Security Deposit Receipt')

@section('page-title', 'SD Report#')

@section('page-content')
    <div class="d-flex align-content-md-start">
        <div class="a4-page pb-2 border bg-white">
            <div class="row p-4 pb-2">
                <div class="col-sm-4 border-bottom border-success-subtle">
                    <img src="{{ asset('img/logo.png') }}" alt="MeghaGas" class="img-fluid">
                </div>
                <div class="col-sm-8 text-end border-bottom border-success-subtle">
                    <span class="fs-4 fw-semibold">Security Deposit Receipt</span>
                </div>
            </div> 
             <div class="px-4">           
                <div class="row">
                    <div class="col-sm-7">
                        <address>
                            <span class="fw-semibold">Megha Gas Distribution Privated Limited. </span><br>
                            S-2, Technocrat Industrial Estate, <br>
                            Balanagar,Hyderabad, <br>
                            Telangana - 500 037
                        </address>
                        <address>
                            <strong>Sri vishnu M</strong><br>
                            Ramulu<br>
                            H.No 21/1, Road No 31, Bhagya nagar colony,<br>
                            Agripalli village, Vijayawada,<br>
                            Krishna, Andhra Pradesh - 500049.
                        </address>
                    </div>
                    <div class="col-sm-5">
                        <div class="row gy-1 gx-2">
                            <div class="col-sm-6 text-end">Receipt Number:</div>
                            <div class="col-sm-6">MG00012</div>
                            <div class="col-sm-6 text-end">Receipt Date:</div>
                            <div class="col-sm-6">17-03-2026</div>
                            <div class="col-sm-6 text-end">Consumer Type:</div>
                            <div class="col-sm-6">Domastic</div>
                            <div class="col-sm-6 text-end">CRN:</div>
                            <div class="col-sm-6">1101000001</div>
                            <div class="col-sm-6 text-end">Products:</div>
                            <div class="col-sm-6">PNG/ CNG</div>
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
                                    <td>9,000.00</td>
                                </tr>
                                <tr>
                                    <td>Consumption deposit(Rs)</td>
                                    <td>:</td>
                                    <td>9,000.00</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <h5 class="text-decoration-underline m-0">Payment Details</h5>
                            <table class="table table-borderless table-sm m-0 fs-6">
                                <tr>
                                    <td>Total Paid Amount(Rs)</td>
                                    <td>:</td>
                                    <td>9,000.00</td>
                                </tr>
                                <tr>
                                    <td>Balance(Rs)</td>
                                    <td>:</td>
                                    <td>9,000.00</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="text-center p-2 border border-success text-success fw-semibold mt-1">Security deposit paid successfully.</div>
                <div class="fw-semibold fs-5 mt-1">Security Deposit Details</div>
                <table class="table table-bordered table-success fs-6 table-sm align-middle">                    
                    <tbody>                        
                        <tr>
                            <td class="bg-light" width="40%">Consumer No</td>
                            <td>310440159</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Payment Date</td>
                            <td>17-03-2026</td>
                        </tr>
                        <tr>
                            <td class="bg-light">Payment Type</td>
                            <td>Online/ UPI/ CC</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Cheque No/Transaction no</td>
                            <td>E2512100QHWE8H</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Remarks</td>
                            <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus fuga temporibus molestias facere voluptatum? Doloribus iusto quos nihil.</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Payment received by</td>
                            <td>Rajashekar G</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Paid amount (Rs)</td>
                            <td class="fw-semibold">9,000.00</td>
                        </tr>
                        <tr>
                            <td class="bg-light" width="40%">Balance amount (Rs)</td>
                            <td class="fw-semibold">3,000.00</td>
                        </tr>
                        <tr class="border-0">
                            <td class="border-0 text-start text-dark">Thank You.!</td>
                            <td class="border-0 text-end text-muted" style="font-size:12px;">This is computer generated receipt.</td>
                        </tr>    
                    </tbody>
                </table>
            </div>
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