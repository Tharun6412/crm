{{-- Business Types --}}

@extends('layouts.layout')

@section('title', 'Firm Types')

@section('page-title', 'Firm Types')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <form action="{{ url('master/consumer/firmTypes') }}" id="firm-type-search-form" method="GET">
        <div id="firm-type-list" class="current-page-reload">
            @include('master.consumer.firm-types.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'firm-type'])
@endpush