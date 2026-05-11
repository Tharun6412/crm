@extends('layouts.layout')

@section('title', 'Geysers')

@section('page-title' , 'Geysers')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('consumers/geysers') }}">Geysers</a></li>
@endsection

@section('page-content')
    <form action="{{ url('consumers/geysers') }}" id="geysers-search-form" method="GET">
        <div id="geysers-list" class="current-page-reload">
            @include('consumers.geysers.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'geysers'])
@endpush