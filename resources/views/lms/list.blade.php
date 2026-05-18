@extends('layouts.layout')

@section('title', 'Lms')

@section('page-title' , 'Lms')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('lms/') }}">Lms</a></li>
@endsection

@section('page-content')
    <form action="{{ url('lms/leads') }}" id="lms-search-form" method="GET">
        <div id="lms-list" class="current-page-reload">
            @include('lms.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'lms'])
@endpush