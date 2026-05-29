@extends('layouts.layout')
@section('title','Tickets')
@section('page-title','Tickets')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{url('tickets/dashboard')}}">Ticktes</a></li>
@endsection
@section('page-content')
    <form action="{{ url('tickets/') }}" method="GET" id="tickets-search-form">
        <div id="tickets-list" class="current-page-reload">
            @include('tickets.tickets.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search',['form' => 'tickets'])
@endpush