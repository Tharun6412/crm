@extends('layouts.layout')

@section('title', 'Delivery Units')

@section('page-title' , 'Delivery Units')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('lms/') }}">Lms</a></li>
@endsection

@section('page-content')
    <form action="{{ url('lms/deliveryUnits') }}" id="lms-du-search-form" method="GET">
        <div id="lms-du-list" class="current-page-reload">
            @include('lms.delivery-units.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'lms-du'])
@endpush