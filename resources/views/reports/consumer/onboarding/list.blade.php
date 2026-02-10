{{-- Consumer onboarding Report --}}

@extends('layouts.layout')

@section('title', 'Reports')

@section('page-title', 'Consumer Onboarding')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active fs-5" id="nav-consumers-tab" data-bs-toggle="tab" data-bs-target="#nav-consumers" type="button" role="tab" aria-controls="nav-consumers" aria-selected="true"><i class="bi bi-app-indicator"></i>&nbsp;All Consumers <span class="badge text-bg-success">{{ array_sum($consumer_status_sum) }}</span></button>
                <button class="nav-link fs-5" id="nav-cns-activity-tab" data-bs-toggle="tab" data-bs-target="#nav-cns-activity" type="button" role="tab" aria-controls="nav-cns-activity" aria-selected="false"><i class="bi bi-app-indicator"></i>&nbsp;Consumer Onboard Activity</button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-consumers" role="tabpanel" aria-labelledby="nav-consumers-tab" tabindex="0">
                @include('reports.consumer.onboarding.consumer-status')
            </div>
            <div class="tab-pane fade" id="nav-cns-activity" role="tabpanel" aria-labelledby="nav-cns-activity-tab" tabindex="0">
                <form action="{{ url('reports/consumer/onboarding/activity') }}" id="report-cns-activity-search-form" method="GET">
                    <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2">
                        <h4 class="mb-0">Consumer Acitivity</h4>
                        <div class="row g-1">
                            <div class="col-auto">
                                <select name="connection_type_id" id="connection_type_id" class="form-select">
                                    <option value="">All Connections</option>
                                    @foreach ($connection_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <select name="segment_id" id="segment_id" class="form-select">
                                    <option value="">All Segments</option>
                                    @foreach ($segments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <input type="text" name="date_from" id="date_from" class="form-control" placeholder="DD-MM-YYYY" value="{{ request()->from_dt }}">
                                    <label for="date_from" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="input-group">
                                    <input type="text" name="date_to" id="date_to" class="form-control" placeholder="DD-MM-YYYY" value="{{ request()->to_dt }}">
                                    <label for="date_to" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="report-cns-activity-list">
                    <div class="alert alert-info mb-0">
                        Please choose dates!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'report-cns-activity'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
@endpush