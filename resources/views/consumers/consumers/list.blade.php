{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'Consumers')

@section('page-title', 'Consumers')


@section('page-content')
    <div>
        <form action="{{ url('consumers') }}" id="consumers-search-form" method="GET">
            <div id="consumers-list">
                @include('consumers.consumers.list-body')
            </div>
        </form>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'consumers'])
    @endpush
@endonce