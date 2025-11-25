{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'Consumers')

@section('page-title', 'Consumers')


@section('page-content')
    <div>
        <form action="{{ url('consumers') }}" id="consumer-search-form" method="GET">
            <div class="row gx-1 mb-1">
                <div class="col-auto">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" id="search-key">Search</span>
                        <input type="text" name="key" id="search-key" class="form-control">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </form>
        <div id="consumer-list">
            @include('consumers.consumers.list-body')
        </div>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'consumer'])
    @endpush
@endonce