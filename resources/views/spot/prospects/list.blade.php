{{-- Prospects list --}}

@extends('layouts.layout')

@section('title', 'Prospects')

@section('page-title', 'Prospects')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('spot/dashboard') }}">SPot</a></li>
@endsection

@section('page-content')
    <div id="prospects-list" class="current-page-reload">
        @include('spot.prospects.list-body')
    </div>
@endsection
