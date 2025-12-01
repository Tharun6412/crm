{{-- Pre-Consumers List --}}
@extends('layouts.layout')

@section('title', 'TR Consumers')

@section('page-title', 'TR Consumers')

@section('page-content')
    <div id="tr-list" class="current-page-reload">
        @include('consumers.tr.list-body')
    </div>
@endsection