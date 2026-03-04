{{-- Employee Collection Report --}}

@extends('layouts.layout')

@section('title', 'Employee Collection Report')

@section('page-title', 'Employee Collection Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <form action="{{ url('reports/employeeCollectionReport') }}" id="employee-report-form">
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="radio" class="btn-check" name="filter_name" id="success-outlined" value="show" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary" for="success-outlined">
                        <i class="bi bi-calendar-range"></i>&nbsp;Between Days
                    </label>
                    <input type="radio" class="btn-check" name="filter_name" id="danger-outlined" value="hide" autocomplete="off">
                    <label class="btn btn-outline-secondary" for="danger-outlined">
                        <i class="bi bi-calendar-check"></i>&nbsp;All Data
                    </label>
                </div>
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
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-arrow-right-circle"></i>&nbsp;Get Report
                    </button>
                </div>
                <div class="col-auto">
                    <a href="{{ url('reports/employeeCollectionReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </form>

        <div id="employee-report-loader" class="mt-3"></div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'employee-report'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
    <script>
        $(function(){
            $('input[name="filter_name"]').change(function() {
                $('#tar-div').toggle($(this).val() === "show");
            });
        });
    </script>
@endpush