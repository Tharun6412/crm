{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'Referrals')

@section('page-title', 'Referrals')


@section('page-content')
    <div>
        <form action="{{ url()->current() }}" id="referrals-search-form" method="GET">
            <div id="referrals-list" class="current-page-reload">
                @include('reports.referrals.list-body')
            </div>
        </form>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'referrals'])
    @endpush
@endonce
