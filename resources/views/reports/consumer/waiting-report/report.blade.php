{{-- Consumer onboarding Report --}}

@extends('layouts.layout')

@section('title', 'Reports')

@section('page-title', 'Connection Progress Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        @php
            $total = array_sum($consumer_wait_sum ?? []);
            $total_tr = $consumer_wait_sum[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0;
            $total_act = $consumer_wait_sum[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0;
            $total_td = $consumer_wait_sum[\App\Enums\ConsumerStatus::TD->value] ?? 0;
            $total_pd = $consumer_wait_sum[\App\Enums\ConsumerStatus::PD->value] ?? 0;
            $total_reject = $consumer_wait_sum[\App\Enums\ConsumerStatus::REJECT->value] ?? 0;
            $wait_list = $total - ($total_tr + $total_reject + $total_act + $total_td + $total_pd);
        @endphp
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active fs-5 border border-bottom-0 me-2" id="nav-consumers-wait-tab" data-bs-toggle="tab" data-bs-target="#nav-consumers-wait" type="button" role="tab" aria-controls="nav-consumers" aria-selected="true"><i class="bi bi-app-indicator"></i>&nbsp;Connection Progress&nbsp;<span class="badge text-bg-success">{{ $wait_list }}</span></button>
                <button class="nav-link fs-5 border border-bottom-0 me-2" id="nav-ga-teams-tab" data-bs-toggle="tab" data-bs-target="#nav-ga-teams" type="button" role="tab" aria-controls="nav-ga-teams" aria-selected="true"><i class="bi bi-people-fill me-1"></i>&nbsp;Teams&nbsp;<span class="badge bg-primary">{{ $total_teams }}</span></button>              
                {{-- <button class="nav-link fs-5 border border-bottom-0 me-2" id="nav-current-status-tab" data-bs-toggle="tab" data-bs-target="#nav-current-status" type="button" role="tab" aria-controls="nav-current-status" aria-selected="false"><i class="bi bi-app-indicator"></i>&nbsp;Overview&nbsp;<span class="badge text-bg-success">{{ array_sum($consumer_status_sum) }}</span></button>
                <button class="nav-link fs-5 border border-bottom-0 me-2" id="nav-cns-activity-tab" data-bs-toggle="tab" data-bs-target="#nav-cns-activity" type="button" role="tab" aria-controls="nav-cns-activity" aria-selected="false"><i class="bi bi-house-gear"></i>&nbsp;Onboard Activity</button>
                <button class="nav-link fs-5 border border-bottom-0 me-2" id="nav-conversions-tab" data-bs-toggle="tab" data-bs-target="#nav-conversions" type="button" role="tab" aria-controls="nav-conversions" aria-selected="false"><i class="bi bi-arrow-right-square"></i>&nbsp;Conversions</button> --}}
            </div>
        </nav>
        <div class="tab-content bg-white p-2 border border-top-0" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-consumers-wait" role="tabpanel" aria-labelledby="nav-consumers-wait-tab" tabindex="0">
                <form action="{{ url('reports/consumer/waiting') }}" id="report-cns-waiting-search-form" method="GET">
                    <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 bg-secondary-subtle rounded">
                        <h4 class="mb-0">Connection Progress</h4>
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
                <div id="report-cns-waiting-list"> 
                    @include('reports.consumer.waiting-report.report-body')
                </div>
            </div>
            <div class="tab-pane fade" id="nav-ga-teams" role="tabpanel" aria-labelledby="nav-ga-teams-tab" tabindex="0">
                <form action="{{ url('reports/consumer/teams') }}" id="report-ga-teams-search-form" method="GET">
                    <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 bg-secondary-subtle rounded">
                        <h4 class="mb-0">Teams Report</h4>
                    </div>    
                </form>
                <div id="report-ga-teams-list"> 
                    @include('reports.consumer.waiting-report.teams')
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'report-cns-waiting'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to', 'status_date', 'conv_date_from', 'conv_date_to']])
@endpush