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
            <div class="row g-2 mb-2">
                <div class="col-auto">
                    <div class="input-group">
                        <label for="dpr_date" class="input-group-text">From Date</label>
                        <input type="text" class="form-control" placeholder="MM-YYYY" name="date_from" id="date_from">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="input-group">
                        <label for="dpr_date" class="input-group-text">To Date</label>
                        <input type="text" class="form-control" placeholder="MM-YYYY" name="date_to" id="date_to">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success"><i class="bi bi-arrow-right-circle"></i>&nbsp;Get Report</button>
                </div>
            </div>
        </form>
        <div id="sd-report-loader"></div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'sd-report'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush