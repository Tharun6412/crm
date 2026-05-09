{{-- Status Report --}}
@extends('layouts.layout')

@section('title', 'Unbilled Consumers Reports')

@section('page-title', 'Unbilled Consumers Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <div id="unbilled-reports-list">
            @include('reports.consumer.unbilled-report.list-body')
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'unbilled-reports'])
@endpush