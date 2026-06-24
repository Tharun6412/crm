{{-- Employee Billing Report --}}

@extends('layouts.layout')

@section('title', 'Employee Billing Report')

@section('page-title', 'Employee Billing Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <form action="{{ url('reports/employee/bills') }}" id="employee-report-form">
            <div class="row g-2">
                <div class="col-auto">
                    <div id="tar-div">
                        <div class="input-group">
                            <label for="date_from" class="input-group-text"><i class="bi bi-calendar3"></i>&nbsp;From</label>
                            <input type="text" aria-label="From Date" class="form-control" name="date_from" id="date_from" placeholder="DD-MM-YYYY">  
                            <label for="date_to" class="input-group-text"><i class="bi bi-calendar3"></i>&nbsp;To</label>
                            <input type="text" aria-label="To Date" class="form-control" name="date_to" id="date_to" placeholder="DD-MM-YYYY">
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <label class="input-group-text">Geo Area</label>
                        <select class="form-select" name="ga_id" id="ga_id">
                            <option value="">select</option>
                            @foreach ($geo_areas as $ga)
                                <option value="{{ $ga->id }}">{{ $ga->name }} ({{ $ga->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-arrow-right-circle"></i>&nbsp;Get Report
                    </button>
                </div>
                <div class="col-auto">
                    <a href="{{ url('reports/employee/collection') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </form>
        <div id="employee-report-loader" class="mt-3">
            <div class="alert alert-info text-center">Select Dates to get Report</div>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'employee-report'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush