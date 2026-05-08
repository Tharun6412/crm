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
                <button class="nav-link active fs-5 border border-bottom-0 me-2" id="nav-consumers-tab" data-bs-toggle="tab" data-bs-target="#nav-consumers" type="button" role="tab" aria-controls="nav-consumers" aria-selected="true"><i class="bi bi-app-indicator"></i>&nbsp;Consumer Status&nbsp;<span class="badge text-bg-success">{{ array_sum($consumer_status_sum) }}</span></button>
                <button class="nav-link fs-5 border border-bottom-0 me-2" id="nav-current-status-tab" data-bs-toggle="tab" data-bs-target="#nav-current-status" type="button" role="tab" aria-controls="nav-current-status" aria-selected="false"><i class="bi bi-app-indicator"></i>&nbsp;Overview&nbsp;<span class="badge text-bg-success">{{ array_sum($consumer_status_sum) }}</span></button>
                <button class="nav-link fs-5 border border-bottom-0 me-2" id="nav-cns-activity-tab" data-bs-toggle="tab" data-bs-target="#nav-cns-activity" type="button" role="tab" aria-controls="nav-cns-activity" aria-selected="false"><i class="bi bi-house-gear"></i>&nbsp;Onboard Activity</button>
                <button class="nav-link fs-5 border border-bottom-0 me-2" id="nav-conversions-tab" data-bs-toggle="tab" data-bs-target="#nav-conversions" type="button" role="tab" aria-controls="nav-conversions" aria-selected="false"><i class="bi bi-arrow-right-square"></i>&nbsp;Conversions</button>
            </div>
        </nav>
        <div class="tab-content bg-white p-2 border border-top-0" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-consumers" role="tabpanel" aria-labelledby="nav-consumers-tab" tabindex="0">
                <form action="{{ url('reports/consumer/onboarding') }}" id="report-cns-onboard-search-form" method="GET">
                    <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 bg-secondary-subtle rounded">
                        <h4 class="mb-0">Consumer Onboard</h4>
                        <div class="row g-1">
                            <div class="col-auto">
                                <label for="status_date">Connection Type</label>
                                <select name="connect_type_id" id="connect_type_id" class="form-select">
                                    <option value="">All Connections</option>
                                    @foreach ($connection_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">Segments</label>
                                <select name="onboard_segment_id" id="onboard_segment_id" class="form-select">
                                    <option value="">All Segments</option>
                                    @foreach ($segments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>
                <div id="report-cns-onboard-list"> 
                    @include('reports.consumer.onboarding.consumer-status')
                </div>
            </div>
            <div class="tab-pane fade" id="nav-cns-activity" role="tabpanel" aria-labelledby="nav-cns-activity-tab" tabindex="0">
                <form action="{{ url('reports/consumer/onboarding/activity') }}" id="report-cns-activity-search-form" method="GET">
                    <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 bg-secondary-subtle rounded">
                        <h4 class="mb-0">Consumer Acitivity</h4>
                        <div class="row g-1">
                            <div class="col-auto">
                                <label for="status_date" class="text-danger text-bg-yellow px-2">Cut-Off Date</label>
                                <div class="input-group">
                                    <input type="text" name="status_date" id="status_date" class="form-control" placeholder="DD-MM-YYYY" value="{{ request()->status_date }}">
                                    <label for="status_date" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">Connection Type</label>
                                <select name="connection_type_id" id="connection_type_id" class="form-select">
                                    <option value="">All Connections</option>
                                    @foreach ($connection_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">Segments</label>
                                <select name="segment_id" id="segment_id" class="form-select">
                                    <option value="">All Segments</option>
                                    @foreach ($segments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">From Date</label>
                                <div class="input-group">
                                    <input type="text" name="date_from" id="date_from" class="form-control" placeholder="DD-MM-YYYY" value="{{ request()->from_dt }}">
                                    <label for="date_from" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">To Date</label>
                                <div class="input-group">
                                    <input type="text" name="date_to" id="date_to" class="form-control" placeholder="DD-MM-YYYY" value="{{ request()->to_dt }}">
                                    <label for="date_to" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                                </div>
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
            {{-- Consumer Conversions --}}
            <div class="tab-pane fade" id="nav-conversions" role="tabpanel" aria-labelledby="nav-conversions-tab" tabindex="0">
                <form action="{{ url('reports/consumer/conversions') }}" id="report-conversions-search-form" method="GET">
                    <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 bg-secondary-subtle rounded">
                        <h4 class="mb-0">Consumer Conversions</h4>
                        <div class="row g-1">
                            <div class="col-auto">
                                <label for="status_date">Segments</label>
                                <select name="conv_segment_id" id="conv_segment_id" class="form-select">
                                    <option value="">All Segments</option>
                                    @foreach ($segments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="conv_date_from">From Date</label>
                                <div class="input-group">
                                    <input type="text" name="conv_date_from" id="conv_date_from" class="form-control" placeholder="DD-MM-YYYY">
                                    <label for="date_from" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="conv_date_to">To Date</label>
                                <div class="input-group">
                                    <input type="text" name="conv_date_to" id="conv_date_to" class="form-control" placeholder="DD-MM-YYYY">
                                    <label for="date_to" class="input-group-text"><i class="bi bi-calendar3"></i></label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>
                <div id="report-conversions-list"> 
                    <div class="alert alert-info mb-0">
                        Please choose dates!
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-current-status" role="tabpanel" aria-labelledby="nav-current-status-tab" tabindex="0">
                <form action="{{ url('reports/consumer/onboarding/getCumulativeConsumerStatusCount') }}" id="report-current-status-search-form" method="GET">
                    <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 bg-secondary-subtle rounded">
                        <h4 class="mb-0">Consumer Onboarding Overview</h4>
                        <div class="row g-1">
                            <div class="col-auto">
                                <label for="status_date">Connection Type</label>
                                <select name="connect_type_id" id="connect_type_id" class="form-select">
                                    <option value="">All Connections</option>
                                    @foreach ($connection_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">Segments</label>
                                <select name="onboard_segment_id" id="onboard_segment_id" class="form-select">
                                    <option value="">All Segments</option>
                                    @foreach ($segments as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="status_date">&nbsp;</label>
                                <div>
                                    <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>    
                </form>
                <div id="report-current-status-list"> 
                    @include('reports.consumer.onboarding.consumer-status-count')
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'report-cns-onboard'])
    @include('scripts.ajax-form-search', ['form' => 'report-cns-activity'])
    @include('scripts.ajax-form-search', ['form' => 'report-conversions'])
    @include('scripts.ajax-form-search', ['form' => 'report-current-status'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to', 'status_date', 'conv_date_from', 'conv_date_to']])
@endpush