{{-- Reports.dashboard.list --}}

@extends('layouts.layout')

@section('title', 'Reports')

@section('page-title', 'Reports')

@section('page-content')
    <div class="row g-2 align-items-center mt-2 mb-3">
        <div class="col-3">
            <div class="border border-success bg-success bg-gradient rounded p-4 fs-5 shadow-sm">
                <a href="#" class="text-white"><i class="bi bi-ticket-perforated fs-4"></i>&nbsp;Consumer Onboarding</a>
            </div>
        </div>
        <div class="col-3">
            <div class="border border-primary bg-primary bg-gradient rounded p-4 fs-5 shadow-sm">
                <a href="#" class="text-white"><i class="bi bi-receipt-cutoff fs-4"></i>&nbsp;Consumer Invoices</a>
            </div>
        </div>
        <div class="col-3">
            <div class="border border-secondary bg-secondary bg-gradient rounded p-4 fs-5 shadow-sm">
                <a href="#" class="text-white"><i class="bi bi-graph-up fs-4"></i>&nbsp;Ageing Report</a>
            </div>
        </div>
        <div class="col-3">
            <div class="border border-warning bg-warning bg-gradient rounded p-4 fs-5 shadow-sm">
                <a href="#" class="text-white"><i class="bi bi-piggy-bank fs-4"></i>&nbsp;Security Deposit Report</a>
            </div>
        </div>
        <div class="col-3">
            <div class="border border-info bg-info bg-gradient rounded p-4 fs-5 shadow-sm">
                <a href="#" class="text-white"><i class="bi bi-file-earmark-spreadsheet fs-4"></i>&nbsp;Outstanding Report</a>
            </div>
        </div>
    </div>
@endsection
<style>
.rotate-90 {
  transform: rotate(90deg);
}
</style>