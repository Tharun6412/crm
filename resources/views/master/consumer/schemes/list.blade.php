@extends('layouts.layout')

@section('title', 'SD Schemes')

@section('page-title', 'SD Schemes')

@section('page-content')
    <div id="sd-schemes-list" class="current-page-reload">
        @include('master.consumer.schemes.list-body')
    </div>
@endsection