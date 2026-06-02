{{-- PNGRB Applications --}}

@extends('layouts.layout')

@section('title', 'PNGRB Applications')

@section('page-title', 'PNGRB Applications')

{{-- @section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Applications</a></li>
@endsection --}}

@section('page-content')
    <form action="{{ url('pngrb/applications') }}" id="pngrb-search-form" method="GET">
        <div id="pngrb-list" class="current-page-reload">
            @include('pngrb.applications.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'pngrb'])
@endpush