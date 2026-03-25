{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'Consumers')

@section('page-title', 'Consumers')


@section('page-content')
    <div>
        <form action="{{ url()->current() }}" id="consumers-search-form" method="GET">
            <div id="consumers-list" class="current-page-reload">
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