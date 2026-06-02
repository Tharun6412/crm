@extends('layouts.layout')
@section('title','SubAreas')
@section('page-title','SubAreas')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection
@section('page-content')
    <form action="{{ url('master/location/subareas') }}" id="subareas-search-form" method="GET">
        <div id="subareas-list" class="current-page-reload">
            @include('master.locations.subareas.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search',['form' => 'subareas'])
@endpush