{{-- Gas Sale Report --}}
@extends('layouts.layout')

@section('title', 'Gas Sale Report')

@section('page-title', 'Gas Sale Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <form name="gas-sale-report-search-form" id="gas-sale-report-search-form"  action="{{ url('reports/gasSaleReport') }}" method="get">
           <div class="d-flex justify-content-between">
                <div class="row gx-1 mb-1">
                    <div class="col-auto">
                        <div class="input-group mb-3">
                            <span class="input-group-text">From Date</span>
                            <input type="text" class="form-control" aria-label="From Date" name="date_from" id="date_from" value="{{ $date_from->format('d-m-Y') }}">
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="input-group mb-3">
                            <span class="input-group-text">To Date</span>
                            <input type="text" class="form-control" aria-label="To Date" name="date_to" id="date_to" value="{{ $date_to->format('d-m-Y') }}">
                            <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                    </div>
                    <!-- Reset -->
                    <div class="col-auto">
                        <a href="{{ url('reports/gasSaleReport') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </div>
            </div>
        </form>
        <div id="gas-sale-report-list">
            @include('reports.dashboard.gas-sale-report.list-body')
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'gas-sale-report'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush