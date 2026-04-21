{{-- Invoices Report --}}
@extends('layouts.layout')

@section('title', 'Invoices Report')

@section('page-title', 'Invoices Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <form id="invoices-search-form" name="invoices-search-form" action="{{ url('reports/invoices/report') }}">
        <div id="invoices-list" class="current-page-reload">
            @include('reports.invoice.invoice-report.list-body')
        </div>
    </form>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'invoices'])
    @endpush
@endonce