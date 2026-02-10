{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'SD Details')

@section('page-title', 'SD Details')


@section('page-content')
    <div>
        <form action="{{ url('reports/consumer/sdDetails') }}" id="sd-details-search-form" method="GET">
            <div id="sd-details-list" class="current-page-reload">
                @include('reports.consumer.sd-details.list-body')
            </div>
        </form>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'sd-details'])
    @endpush
@endonce