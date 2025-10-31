{{-- Prospects Dashboard --}}
@extends('layouts.layout')

@section('title', 'Dashbaord')

@section('page-title', 'Dashboard')

@section('page-content')
<div id="dashboard-list" class="current-page-reload">
    <form id="fy-year-form" method="GET" action="{{ url('spot/dashboard') }}">
        <div class="row g-2 mb-2">
            <div class="col-auto">
                <div class="input-group">
                    <label for="target_year" class="input-group-text">Year</label>
                    @php
                        $fy_val = request()->has('target_year') ? request()->get('target_year') : date('Y');
                    @endphp
                    <input type="text" class="form-control" placeholder="YYYY" name="target_year" id="target_year" value="{{ $fy_val }}">
                </div>
            </div>
            <div class="col-auto">
                <div class="border rounded px-2 py-1">
                    <div class="d-flex justify-content-between align-items-center pt-1">
                        <label class="fs-6 mx-1">GA</label>
                        <x-admin.ga-filter class="pt-1" />
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success"><i class="bi bi-arrow-right-circle"></i>&nbsp;Get Report</button>
                <a href="{{ url('spot/dashboard') }}" class="btn btn-warning ajax-link" title="Reset">
                <i class="bi bi-arrow-clockwise"></i>
            </a>
            </div>
        </div>
    </form>
    <div id="fy-year-loader">
        @include('spot.dashboard.list-body')
    </div>
</div>
@endsection
@include('scripts.ajax-get-form-submit', ['form' => 'fy-year'])
<script type="module">
    $(function(){
        $('#target_year').datepicker({format: 'yyyy', autoHide: true});
    });
</script>