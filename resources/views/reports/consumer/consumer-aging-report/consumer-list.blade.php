{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'Consumers List')

@section('page-title', 'Consumers List')


@section('page-content')
    <div>
        <form action="{{ url('reports/consumer/consumerAgeingReport/consumersList') }}" id="consumers-search-form" method="GET">
            <div id="consumers-list" class="current-page-reload">
                @include('reports.consumer.consumer-aging-report.consumer-list-body')
            </div>
        </form>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'consumers'])
    @endpush
@endonce