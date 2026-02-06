{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'Prepaid Consumers')

@section('page-title', 'Prepaid Consumers')


@section('page-content')
    <div>
        <form action="{{ url('consumers/prepaid') }}" id="prepaid-consumers-search-form" method="GET">
            <div id="prepaid-consumers-list" class="current-page-reload">
                @include('consumers.prepaid.list-body')
            </div>
        </form>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'prepaid-consumers'])
    @endpush
@endonce