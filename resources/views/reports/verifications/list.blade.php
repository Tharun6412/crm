{{-- @extends('layouts.layout')
@section('title', 'Verification')
@section('page-title','Verification')
@section('page-content')
    <div>
        @include('reports.verifications.list-body')
    </div>
@endsection --}}
@extends('layouts.layout')
@section('title','Consumers Verifications')
@section('page-title','Consumer Verifications')
@section('page-content')
    <form action="{{ url('reports/consumer/verify') }}" id="verification-search-form" method="GET"> 
        <h4>Consumers Status Board</h4>
        <div class="card-group">
            <div class="card bg-success-subtle">
                <div class="card-body">
                    <h3 class="card-text">{{ numberFormat($verificationCount[1] ?? 0) }}</h3>
                    <h5 class="card-title">Verified Success</h5>
                </div>
            </div>
            <div class="card bg-danger-subtle">
                <div class="card-body">
                    <h3 class="card-text">{{ numberFormat($verificationCount[0] ?? 0) }}</h3>
                    <h5 class="card-title">Verified Issues</h5>
                </div>
            </div>
            <div class="card bg-primary-subtle">
                <div class="card-body">
                    <h3 class="card-text">{{ numberFormat($verificationCount->sum()) }}</h3>
                    <h5 class="card-title">Total Verified </h5>
                </div>
            </div>
            <div class="card bg-secondary-subtle">
                <div class="card-body">
                    <h3 class="card-text">{{ numberFormat($consumers - $verificationCount->sum()) }}</h3>
                    <h5 class="card-title">Unverified</h5>
                </div>
            </div>
        </div>
        <h4 class="mt-2">Consumer Verified Details</h4>
        <div id="verification-list" class="current-page-reload">
            @include('consumers.verify.list-body')
        </div>
    </form>
@endsection
@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'verification'])
    @endpush
@endonce