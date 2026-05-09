{{-- Consumers list --}}

@extends('layouts.layout')

@section('title', 'SD Details')

@section('page-title', 'SD Details')


@section('page-content')
    <div>
        <form action="{{ url('reports/consumer/sdDetails') }}" id="sd-details-search-form" method="GET">
            <div id="sd-details-list" class="current-page-reload">
                @include('reports.consumer.sd-details.list-body')
            </div>
            <div id="sd-details-counts"></div>
        </form>
    </div>
@endsection

@once
    @push('scripts')
        @include('scripts.ajax-form-search', ['form' => 'sd-details', 'callback' => 'loadCounts()'])
        <script>
            function loadCounts() {
                $('#sd-details-counts').html('Loading....');
                var params = $('#sd-details-search-form').serializeArray();
                $.get('{{ url('reports/consumer/sdDetails-counts') }}', params, function(data) {
                    $('#sd-details-counts').html(data);
                });
            }
            $(document).ready(function() {
                loadCounts();
            });
        </script>
    @endpush
@endonce