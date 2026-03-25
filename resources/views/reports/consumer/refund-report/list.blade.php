{{-- Consumer Refund Report --}}

@extends('layouts.layout')

@section('title', 'Refund Report')

@section('page-title', 'Refund Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <div id="refund-report-loader" class="mt-2">
            @include('reports.consumer.refund-report.list-body')
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'refund-report'])
@endpush