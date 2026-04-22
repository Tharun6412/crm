{{-- Invoices Report --}}
@extends('layouts.layout')

@section('title', 'Invoices Report')

@section('page-title', 'Invoices Report')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <form id="invoices-search-form" name="invoices-search-form" action="{{ url('reports/invoices/all') }}">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <!-- LEFT SIDE FILTERS -->
            <div class="d-flex flex-wrap align-items-center gap-2">
                <!-- Search -->
                <div class="input-group w-auto">
                    <span class="input-group-text">Search</span>
                    <input type="text" name="key" id="key" class="form-control" value="{{ request()->key }}" placeholder="Search...">
                </div>
                <!-- From Date -->
                <div class="input-group w-auto">
                    <span class="input-group-text">Invoice Date</span>
                    <input type="text" class="form-control" name="date_from" id="date_from" value="{{ request()->date_from }}" placeholder="DD-MM-YYYY">
                    <label for="date_from" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                    <input type="text" class="form-control" name="date_to" id="date_to" value="{{ request()->date_to }}" placeholder="DD-MM-YYYY">
                    <label for="date_to" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                </div>
                <!-- Submit -->
                <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                <!-- Reset -->
                <a href="{{ url('reports/invoices/report') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
            {{-- <div>
                <x-auth.link :href="url('reports/invoiceReport/invoicesReportExport') . '?' . request()->getQueryString()" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</x-auth.link>
            </div> --}}
        </div>
        <div id="invoices-list" class="current-page-reload">
            {{-- @include('reports.invoice.invoice-report.list-body') --}}
            <div class="bg-white p-4 border rounded">Please select date range!</div>
        </div>
        <div id="inv-counts"></div>
    </form>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'invoices', 'callback' => 'loadCounts()'])
        @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
        <script>
            function loadCounts() {
                $('#inv-counts').html('Loading....');
                var params = $('#invoices-search-form').serializeArray();
                $.get('{{ url('reports/invoices/all-counts') }}', params, function(data) {
                    $('#inv-counts').html(data);
                });
            }
        </script>
    @endpush
@endonce