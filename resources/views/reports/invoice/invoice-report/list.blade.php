{{-- Invoices Report --}}
@extends('layouts.layout')

@section('title', 'Invoices Report')

@section('page-title', 'Invoices Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    
    <div id="invoices-list">
        @include('reports.invoice.invoice-report.list-body')
    </div>
@endsection