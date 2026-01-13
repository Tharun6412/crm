{{-- Meter Change list --}}

@extends('layouts.layout')

@section('title', 'Meter Change')

@section('page-title', 'Meter Change')

@section('page-content')
    <div>
        <form action="{{ url('consumers/meterChange') }}" id="meter-change-search-form" method="GET">
            <div id="meter-change-list" class="current-page-reload">
                @include('consumers.meter-change.list-body')
            </div>
        </form>
    </div>
@endsection
@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'meter-change'])
    @endpush
@endonce
