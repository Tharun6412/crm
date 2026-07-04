{{-- @extends('layouts.layout')
@section('title', 'Verification')
@section('page-title','Verification')
@section('page-content')
    <div>
        @include('reports.verifications.list-body')
    </div>
@endsection --}}
@extends('layouts.layout')
@section('title','Verification')
@section('page-title','Verification')
@section('page-content')
    <form action="{{ url('reports/consumer/verify') }}" id="verification-search-form" method="GET"> 
        <h4>Verified Status</h4> 
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card-group">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Verified Success</h4>
                            <p class="card-text">{{ $verificationCount[1] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Verified Issues</h4>
                            <p class="card-text">{{ $verificationCount[0] ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Verified </h4>
                            <p class="card-text">{{ $verificationCount->sum() }}</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Unverified</h4>
                            <p class="card-text">{{ $consumers - $verificationCount->sum() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="mt-2">Verified Details</h4>
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