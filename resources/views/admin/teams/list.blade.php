@extends('layouts.layout')
@section('title','Teams')
@section('page-title','Teams')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('teams') }}">Teams</a></li>
@endsection
@section('page-content')
    <form action="{{ url('admin/teams') }}" id="teams-search-form" method="GET">
        <div id="teams-list" class="current-page-reload" >
            @include('admin.teams.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search',['form' => 'teams'])
@endpush