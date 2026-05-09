{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'Unbilled Consumers List')

@section('page-title', 'Unbilled Consumers List')


@section('page-content')
    <div>
        <form id="unbilled-consumers-search-form" name="unbilled-consumers-search-form" action="{{ url('reports/unbilled/list') }}">
            <div id="unbilled-consumers-list">
                @include('reports.consumer.unbilled-report.consumers-list-body')
            </div>
        </form>
    </div>
@endsection
@include('scripts.ajax-form-search',['form' => 'unbilled-consumers'])