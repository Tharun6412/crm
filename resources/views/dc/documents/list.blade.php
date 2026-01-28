{{-- Document centre -> Documents list --}}

@extends('layouts.layout')

@section('title', 'Document Centre')

@section('page-title', 'Document Centre')

@section('page-content')
    <div id="dc-list">
        @include('dc.documents.list-body')
    </div>
@endsection