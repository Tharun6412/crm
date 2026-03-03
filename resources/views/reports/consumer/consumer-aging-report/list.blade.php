{{-- Status Report --}}
@extends('layouts.layout')

@section('title', 'Consumers Ageing Reports')

@section('page-title', 'Consumers Ageing Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <div id="consumer-aging-reports-list">
            @include('reports.consumer.consumer-aging-report.list-body')
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'consumer-aging-reports'])
@endpush