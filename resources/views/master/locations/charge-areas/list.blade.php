{{-- States --}}

@extends('layouts.layout')

@section('title', 'Charge Areas')

@section('page-title', 'Charge Areas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <form action="{{ url('master/charge-areas') }}" id="ca-search-form" method="GET">
        <div id="ca-list">
            @include('master.locations.charge-areas.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'ca'])
@endpush