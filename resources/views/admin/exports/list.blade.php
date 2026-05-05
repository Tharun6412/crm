{{-- Export Users list --}}

@extends('layouts.layout')

@section('title', 'Exports')

@section('page-title', 'Exports')

@section('page-content')
    <div id="exports-list" class="current-page-reload">
        @include('admin.exports.list-body')
    </div>
@endsection