{{-- Business Types --}}

@extends('layouts.layout')

@section('title', 'Business Types')

@section('page-title', 'Business Types')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <form action="{{ url('master/business-types') }}" id="business-type-search-form" method="GET">
        <div id="business-type-list" class="current-page-reload">
            @include('master.locations.business-types.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'business-type'])
@endpush