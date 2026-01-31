{{-- Reports.dashboard.list --}}

@extends('layouts.layout')

@section('title', 'Reports')

@section('page-title', 'Reports')

@section('page-content')
    <div class="row g-2 align-items-center">
        <div class="col-3">
            <div class="border rounded p-3 fs-5">
                <a href="#" class="text-dark"><i class="bi bi-easel"></i>&nbsp;Consumer Onboarding</a>
            </div>
        </div>
        <div class="col-3">
            <div class="border rounded p-3 fs-5">
                <a href="#" class="text-dark"><i class="bi bi-easel"></i>&nbsp;Consumer Invoices</a>
            </div>
        </div>
        <div class="col-3">
            <div class="border rounded p-3 fs-5">
                <a href="#" class="text-dark"><i class="bi bi-easel"></i>&nbsp;Ageing Report</a>
            </div>
        </div>
        <div class="col-3">
            <div class="border rounded p-3 fs-5">
                <a href="#" class="text-dark"><i class="bi bi-easel"></i>&nbsp;Security Deposit Report</a>
            </div>
        </div>
        <div class="col-3">
            <div class="border rounded p-3 fs-5">
                <a href="#" class="text-dark"><i class="bi bi-easel"></i>&nbsp;Outstanding Report</a>
            </div>
        </div>
    </div>
@endsection