{{-- Refund list --}}

@extends('layouts.layout')

@section('title', 'Refunds Request')

@section('page-title', 'Refunds Request')

@section('page-content')
    <div>
        <form action="{{ url('consumers/refunds') }}" id="refunds-search-form" method="GET">
            <div id="refunds-list" class="current-page-reload">
                @include('consumers.refund.list-body')
            </div>
        </form>
    </div>
@endsection
