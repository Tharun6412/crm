{{-- Consumer onboarding Report --}}

@extends('layouts.layout')

@section('title', 'SD Reports')

@section('page-title', 'SD Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <form action="{{ url('reports/consumer/sdReport') }}" id="sd-report-form">
            <div class="row g-2 align-items-center">
                <label for="date_from" class="col-auto col-form-label">Registration Date</label>
                <div class="col-auto">
                    <div class="input-group">
                        <span class="input-group-text">From Date</span>
                        <input type="text" aria-label="From Date" class="form-control" name="date_from" id="date_from">  
                        <span class="input-group-text">To Date</span>
                        <input type="text" aria-label="To Date" class="form-control" name="date_to" id="date_to">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="check_all" id="check_all" value="all" id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">All</label>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-arrow-right-circle"></i>&nbsp;Get Report
                    </button>
                </div>
            </div>
        </form>

        <div id="sd-report-loader" class="mt-3"></div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'sd-report'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush