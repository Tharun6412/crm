{{-- Price groups --}}

@extends('layouts.layout')

@section('title', 'Price Groups')

@section('page-title', 'Price Groups')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    {{-- Seacrh --}}
    <form action="{{ url('master/consumer/price-groups') }}" id="price-group-search-form" method="GET">
        <div class="d-flex justify-content-between">
            <div class="row g-2 mb-1">
                <div class="col-auto">
                    <input type="text" name="key" id="key" placeholder="Enter search keyword" class="form-control" value="{{ request()->key ?? '' }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                </div>
            </div>
            <div>
                <a href="{{ url('master/consumer/price-groups/create') }}" class="btn btn-success link-modal"><i class="bi bi-plus-lg"></i>&nbsp;Create</a>
            </div>
        </div>
        <div id="price-group-list" class="current-page-reload">
            @include('master.consumer.price-groups.list-body')
        </div>
    </form>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'price-group'])
    @include('scripts.link-modal')
@endpush