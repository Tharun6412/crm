{{-- Prospects list --}}

@extends('layouts.layout')

@section('title', 'Prospects')

@section('page-title', 'Prospects')

@section('page-content')
    <div id="prospects-list">
        @include('spot.prospects.list-body')
    </div>
@endsection
