{{-- Prospects Dashboard --}}
@extends('layouts.layout', ['dashboard' => true])

@section('title', 'Dashbaord')

@section('page-title', 'Dashboard')

@section('page-content')
    <div id="dashboard-list" class="current-page-reload">
        <div class="row g-2 mb-3">
            <div class="col-auto"><a href="{{ url('spot/prospects') }}" class="btn btn-primary btn-sm"><i class="bi bi-people"></i>&nbsp;All Prospects</a></div>
            <div class="col-auto"><a href="{{ url('spot/comments') }}" class="btn btn-primary btn-sm"><i class="bi bi-chat-dots"></i>&nbsp;Latest Comments</a></div>
            <div class="col-auto"><a href="{{ url('spot/dateChangeRequest') }}" class="btn btn-primary btn-sm"><i class="bi bi-calendar2-event"></i>&nbsp;Latest Date Requests</a></div>
            <div class="col-auto"><a href="{{ url('spot/targets') }}" class="btn btn-primary btn-sm"><i class="bi bi-bullseye"></i>&nbsp;Targets</a></div>
        </div>
        <div class="d-flex justify-content-between align-items-center p-2 bg-light">
            <h3 class="mb-0">Analysis</h3>
            <form id="fy-year-form" method="GET" action="{{ url('spot/dashboard') }}" class="mb-0">
                <div class="row gx-2">
                    <div class="col-auto">
                        <div class="input-group">
                            <label for="target_year" class="input-group-text">FY</label>
                            @php
                                $fy_val = request()->has('target_year') ? request()->get('target_year') : date('Y');
                            @endphp
                            <input type="text" class="form-control" placeholder="YYYY" name="target_year" id="target_year" value="{{ $fy_val }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-control">
                            <label class="fs-6 mx-1">Clusters</label>
                            <x-master.cluster-filter />
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-control">
                            <label class="fs-6 mx-1">GA</label>
                            <x-master.ga-filter class="float-end" />
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success"><i class="bi bi-arrow-right-circle"></i>&nbsp;Go</button>
                        <a href="{{ url('spot/dashboard') }}" class="btn btn-warning ajax-link" title="Reset">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div id="fy-year-loader" class="">
            @include('spot.dashboard.list-body')
        </div>
        <div>
            @include('spot.dashboard.targets')
        </div>
    </div>
@endsection
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'fy-year'])
    <script type="module">
        $(function(){
            $('#target_year').datepicker({format: 'yyyy', autoHide: true});
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/highcharts/funnel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/highcharts/accessibility.js') }}"></script>
@endpush