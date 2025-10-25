{{-- Prospects Dashboard --}}
@extends('layouts.layout')

@section('title', 'Dashbaord')

@section('page-title', 'Dashboard')

@section('page-content')
<div id="dashboard-body">
    @include('spot.prospects.dashboard.list-body')
</div>
@endsection