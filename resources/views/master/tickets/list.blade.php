@extends('layouts.layout')
@section('title','Ticket Categories')
@section('page-title','Tickets Categories')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master/tickets') }}">Categories</a></li>
@endsection
@section('page-content')
    <div>
        @include('master.tickets.list-body')
    </div>
@endsection