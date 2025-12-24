{{-- Complaints List --}}
@extends('layouts.layout')

@section('title', 'Calls')

@section('page-title', 'Calls')

@section('page-content')
    <form action="{{ url('complaints') }}" id="complaints-search-form" method="GET">
        <div id="complaints-list" class="current-page-reload">
            @include('complaints.list-body')
        </div>
    </form>
@endsection
@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'complaints'])
    @endpush
@endonce