{{-- PNG Price setting --}}

@extends('layouts.layout')

@section('title', 'Gas Price')

@section('page-title', 'Gas Price')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <form action="{{ url('master/consumer/prices') }}" id="cns-price-search-form" method="GET">
        <div id="cns-price-list">
            @include('master.consumer.prices.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'cns-price'])
@endpush