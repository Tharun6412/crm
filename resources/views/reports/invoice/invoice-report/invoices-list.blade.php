{{-- Invoices Report --}}
@extends('layouts.layout')

@section('title', 'Invoices List')

@section('page-title', 'Invoices List')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <form id="invoices-report-search-form" name="invoices-report-search-form" action="{{ url('reports/invoices/list') }}">
        <div id="invoices-report-list" class="current-page-reload">
            @include('reports.invoice.invoice-report.invoices-list-body')
        </div>
    </form>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'invoices-report'])
    @endpush
@endonce