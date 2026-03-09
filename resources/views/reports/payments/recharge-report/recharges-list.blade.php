{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'Consumer Recharges List')

@section('page-title', 'Consumers Recharges List')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <form action="{{ url('reports/consumer/recharge/List') }}" id="consumer-recharges-search-form" method="GET">
            <div id="consumer-recharges-list" class="current-page-reload">
                @include('reports.payments.recharge-report.recharges-list-body')
            </div>
        </form>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'consumer-recharges'])
    @endpush
@endonce