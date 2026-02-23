{{-- Status Report --}}
@extends('layouts.layout')

@section('title', 'Invoices Ageing Reports')

@section('page-title', 'Invoices Ageing Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <div id="aging-reports-list">
            @include('reports.consumer.aging-report.list-body')
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'aging-reports'])
@endpush