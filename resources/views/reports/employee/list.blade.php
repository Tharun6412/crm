{{-- Employee Report Landing for Tabs --}}

@extends('layouts.layout')

@section('title', 'Employee Report')

@section('page-title', 'Employee Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab1" role="tablist">
                <button class="nav-link active fs-5 border border-bottom-0 me-2 text-nowrap" id="billing-tab" data-bs-toggle="tab" data-bs-target="#billing" type="button" role="tab" aria-controls="billing" aria-selected="true"><i class="bi bi-receipt-cutoff me-1"></i>&nbsp;Billing</button>
                <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="collection-tab" data-bs-toggle="tab" data-bs-target="#collection" type="button" role="tab" aria-controls="collection" aria-selected="false"><i class="bi bi-cash me-1"></i>&nbsp;Collection</button>
            </div>
        </nav>
        <div class="tab-content bg-white p-1 border border-top-0" id="nav-tabContentMain">
        <div class="tab-pane fade show active" id="billing" role="tabpanel" aria-labelledby="billing-tab" tabindex="0">
            @include('reports.employee.bill-report.list')
        </div>
        <div class="tab-pane fade" id="collection" role="tabpanel" aria-labelledby="collection-tab" tabindex="0">
            @include('reports.employee.collection-report.list')
        </div>
    </div>
@endsection