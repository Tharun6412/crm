@extends('layouts.layout')

@section('title', 'Consumer Schemes')

@section('page-title', 'Consumer Schemes')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div id="schemes-list" class="current-page-reload">
        @include('master.consumer.schemes.list-body')
    </div>
@endsection