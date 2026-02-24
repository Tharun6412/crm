{{-- Industrial Areas --}}

@extends('layouts.layout')

@section('title', 'Industrial Areas')

@section('page-title', 'Industrial Areas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <form action="{{ url('master/industrial-areas') }}" id="ca-search-form" method="GET">
        <div id="ia-list" class="current-page-reload">
            @include('master.locations.industrial-areas.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'ia'])
@endpush