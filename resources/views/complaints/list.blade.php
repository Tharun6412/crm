{{-- Complaints List --}}
@extends('layouts.layout')

@section('title', 'Complaints')

@section('page-title', 'Complaints')

@section('page-content')
    <div id="complaint-list" class="current-page-reload">
        @include('complaints.list-body')
    </div>
@endsection