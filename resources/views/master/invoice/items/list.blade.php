{{-- Invoice items list --}}
@extends('layouts.layout')

@section('title', 'Invoice Items')

@section('page-title', 'Invoice Items')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <form action="{{ url('master/invoice/items') }}" id="inv-items-search-form" method="GET">
        <div id="inv-items-list">
            @include('master.invoice.items.list-body')
        </div>
    </form>
@endsection
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'inv-items'])
@endpush