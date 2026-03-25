{{-- Status Report --}}
@extends('layouts.layout')

@section('title', 'Consumers Complaints Reports')

@section('page-title', 'Consumers Complaints Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('calls/dashboard') }}">CS Dashboard</a></li>
@endsection

@section('page-content')
    <div>
        <div id="complaints-ga-reports-list" class="bg-primary">
            @include('complaints.reports.ga.list-body')
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'complaints-ga-reports'])
@endpush