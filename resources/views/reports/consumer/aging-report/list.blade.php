{{-- Status Report --}}
@extends('layouts.layout')

@section('title', 'Invoices Ageing Reports')

@section('page-title', 'Invoices Ageing Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <form name="aging-reports-search-form" id="aging-reports-search-form"  action="{{ url('reports/ageingReport') }}" method="get">
           <div class="d-flex justify-content-between">
                <div class="row gx-1 mb-1">
                    <div class="col-auto">
                        <label for="invoice_type">Invoice Type</label>
                        <select class="form-select" name="invoice_type" id="invoice_type">
                            <option value="">All</option>
                            @foreach ($invoice_types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
        <div id="aging-reports-list">
            @include('reports.consumer.aging-report.list-body')
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'aging-reports'])
@endpush