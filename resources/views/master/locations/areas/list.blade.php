{{-- States --}}

@extends('layouts.layout')

@section('title', 'States')

@section('page-title', 'Areas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <form action="{{ url('master/areas') }}" id="area-search-form" method="GET">
        <div id="area-list">
            @include('master.locations.areas.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'area'])
@endpush