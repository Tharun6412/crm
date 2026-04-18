{{-- States --}}

@extends('layouts.layout')

@section('title', 'Districts')

@section('page-title', 'Districts')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <form action="{{ url('master/location/districts') }}" id="dist-search-form" method="GET">
        <div id="dist-list" class="current-page-reload">
            @include('master.locations.districts.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'dist'])
@endpush