@extends('layouts.layout')
@section('title','Verification')
@section('page-title','Verification')
@section('page-content')
    <form action="{{ url('consumers/verify/all') }}" id="verification-search-form" method="GET">
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