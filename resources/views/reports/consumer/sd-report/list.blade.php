{{-- Consumer onboarding Report --}}

@extends('layouts.layout')

@section('title', 'Security Deposit Report')

@section('page-title', 'Security Deposit Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <form action="{{ url('reports/consumer/sdReport') }}" id="sd-report-form">
            <div class="row g-1">
                <div class="col-auto">
                    <input type="radio" class="btn-check" name="filter_name" id="danger-outlined" value="hide" autocomplete="off">
                    <label class="btn btn-outline-secondary" for="danger-outlined">
                        <i class="bi bi-calendar-check"></i>&nbsp;All Data
                    </label>
                </div>
                <div class="col-auto">
                    <input type="radio" class="btn-check" name="filter_name" id="success-outlined" value="show" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary" for="success-outlined">
                        <i class="bi bi-calendar-range"></i>&nbsp;Between Days
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
                    <select name="status" id="status" class="form-select">
                        <option value="">Select</option>
                        <option value={{ \App\Enums\ConsumerStatus::PRE_REGISTER->value }}>TR</option>
                        <option value={{ \App\Enums\ConsumerStatus::REGISTER->value }}>Register</option>
                    </select>
                </div>
                <div class="col-auto">
                    <span class="form-control">
                        Connection Type<x-master.connection-type-filter class="float-end" />
                    </span>
                    {{-- <select name="con_type" id="con_type" class="form-select">
                        <option value="">Connection Type</option>
                    </select> --}}
                </div>
                <div class="col-auto">
                    <span class="form-control">
                        Segment<x-master.segment-filter class="float-end" />
                    </span>
                </div>
                <div class="col-auto">
                    <input type="checkbox" name="conversion_scheme" id="conversion_scheme" />Incl. Conversion Scheme
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success" id="getDetailsBtn">
                        <i class="bi bi-arrow-right-circle"></i>&nbsp;Get Report
                    </button>
                </div>
                <div class="col-auto">
                    <a href="{{ url('reports/consumer/sdReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
                <div class="col-auto">
                    <!-- Export -->
                    <button type="button" id="exportSaleBtn" class="btn btn-outline-info text-end"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
                </div>
            </div>
        </form>

        <div id="sd-report-loader" class="mt-3">
            <div class="alert alert-info text-center">Pick Dates to Generate Report</div>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'sd-report'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
    <script>
        $(function(){
            $('input[name="filter_name"]').change(function() {
                $('#tar-div').toggle($(this).val() === "show");
            });
        });
    </script>
@endpush