{{-- Status Report --}}
@extends('layouts.layout')

@section('title', 'Consumers Complaints Reports')

@section('page-title', 'Consumers Complaints Reports')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('calls/dashboard') }}">CS Dashboard</a></li>
@endsection

@section('page-content')
    <div>
        <div id="complaints-reports-list">
            {{-- @include('complaints.reports.ga.list-body') --}}
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a href="#" class="nav-link border border-bottom-0 me-2 active fs-5" id="nav-ga-tab" data-bs-toggle="tab" data-bs-target="#nav-ga" type="button" role="tab" aria-controls="nav-ga" aria-selected="true" data-url="{{ url('calls/reports/gaReport') }}"><i class="bi bi-geo-alt"></i>&nbsp;Ga Report</a>
                    <a href="#" class="nav-link border border-bottom-0 me-2  fs-5" id="nav-category-tab" data-bs-toggle="tab" data-bs-target="#nav-category" type="button" role="tab" aria-controls="nav-category" aria-selected="false" data-url="{{ url('calls/reports/categoryReport') }}"><i class="bi bi-check2-circle"></i>&nbsp;Category Report</a>
                    <a href="#" class="nav-link border border-bottom-0  fs-5" id="nav-deviation-tab" data-bs-toggle="tab" data-bs-target="#nav-deviation" type="button" role="tab" aria-controls="nav-deviation" aria-selected="false" data-url="{{ url('calls/reports/deviationReport') }}"><i class="bi bi-signpost-split"></i>&nbsp;Deviation Report</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade" id="nav-ga" role="tabpanel" aria-labelledby="nav-ga-tab"></div>
                <div class="tab-pane fade" id="nav-category" role="tabpanel" aria-labelledby="nav-category-tab"></div>
                <div class="tab-pane fade" id="nav-deviation" role="tabpanel" aria-labelledby="nav-deviation-tab"></div>
            </div>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    <script>
        $(document).ready(function () {
            let firstTab = $('.nav-link[data-url]').first();
            if (firstTab.length) {
                firstTab.addClass('active');
                let target = firstTab.data('bs-target');
                $(target).addClass('show active');
                // trigger AJAX load
                loadTabData(firstTab.data('url'), target);
            }
        });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {                
            let target = $(e.target).data('bs-target');
            let url = $(e.target).data('url');
            if ($(target).data('loaded')) return;
            if(url) {
                loadTabData(url, target);
            }
        });

        // reusable loader
        function loadTabData(url, target, formData = {}) {
            preLoader();
            $.get(url, formData, function (data) {
                $(target).html(data).data('loaded', true);
                closePreLoader();
            });
        }

        // filter submit (works for all tabs)
        $(document).on('submit', '.report-filter-form', function (e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let target = form.data('target');
            loadTabData(url, target, form.serialize());
        });
    </script>
@endpush