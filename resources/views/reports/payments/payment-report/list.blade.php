{{-- Invoices Report --}}
@extends('layouts.layout')

@section('title', 'Payments Report')

@section('page-title', 'Payments Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <form id="payments-report-search-form" name="payments-report-search-form" action="{{ url('reports/paymentsReport') }}">
        <div id="payments-report-list" class="current-page-reload">
            @include('reports.payments.payment-report.list-body')
        </div>
    </form>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'payments-report'])
    @endpush
@endonce