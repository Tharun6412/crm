{{-- Consumer onboarding Report --}}

@extends('layouts.layout')

@section('title', 'Reports')

@section('page-title', 'Connection Progress Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')

{{-- resources/views/some-view.blade.php --}}

<div class="bg-white p-1">
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
        <div class="nav nav-tabs d-flex flex-nowrap gap-2 mt-2" id="nav-tab1" role="tablist">
            <button class="nav-link active fs-5 border border-bottom-0 me-2 text-nowrap" id="connection-progress-tab" data-bs-toggle="tab" data-bs-target="#connection-progress" type="button" role="tab" aria-controls="connection-progress" aria-selected="true"><i class="bi bi-hourglass-split"></i>&nbsp;Connection Progress</button>
            <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="emp-progress-tab" data-bs-toggle="tab" data-bs-target="#emp-progress" type="button" role="tab" aria-controls="emp-progress" aria-selected="false"><i class="bi bi-person-gear me-1"></i>&nbsp;Team Progress</button>
            <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-ga-teams-tab" data-bs-toggle="tab" data-bs-target="#nav-ga-teams" type="button" role="tab" aria-controls="nav-ga-teams" aria-selected="true"><i class="bi bi-people-fill me-1"></i>&nbsp;Teams&nbsp;<span class="badge bg-primary">{{ numberFormat($total_teams) }}</span></button>
        </div>
    </nav>
    <div class="tab-content bg-white p-2 border border-top-0" id="nav-tabContentMain">
        <div class="tab-pane fade show active pt-2 bg-white" id="connection-progress" role="tabpanel" aria-labelledby="connection-progress-tab" tabindex="0">
            <nav>
                <div class="nav nav-tabs bg-white" id="nav-tab2" role="tablist">
                    <button class="nav-link active fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-consumers-wait-tab" data-bs-toggle="tab" data-bs-target="#nav-consumers-wait" type="button" role="tab" aria-controls="nav-consumers" aria-selected="true"><i class="bi bi-pin-map"></i>&nbsp;GA Wise&nbsp;<span class="badge text-bg-success">{{ numberFormat($wait_list) }}</span></button>
                    <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-emp-activity-tab" data-bs-toggle="tab" data-bs-target="#nav-emp-activity" type="button" role="tab" aria-controls="nav-emp-activity" aria-selected="true"><i class="bi bi-person-workspace me-1"></i>&nbsp;Employee Wise</button>  
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContentCns">
                <div class="tab-pane fade show active" id="nav-consumers-wait" role="tabpanel" aria-labelledby="nav-consumers-wait-tab" tabindex="0">
                    <form action="{{ url('reports/consumer/connectionProgress') }}" id="report-cns-waiting-search-form" method="GET">
                        <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 rounded">
                            <h4 class="mb-0">Connection Progress</h4>
                            <div class="row g-1">
                                <div class="col-auto">
                                    <select name="connect_type_id" id="connect_type_id" class="form-select">
                                        <option value="">All Connections</option>
                                        @foreach ($connection_types as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <select name="onboard_segment_id" id="onboard_segment_id" class="form-select">
                                        <option value="">All Segments</option>
                                        @foreach ($segments as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-auto">
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
                <div class="tab-pane fade" id="nav-emp-activity" role="tabpanel" aria-labelledby="nav-emp-activity-tab" tabindex="0">
                    <form action="{{ url('reports/consumer/employee/activity') }}" id="report-emp-activity-search-form" method="GET">
                        <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 bg-secondary-subtle rounded">
                            <h4 class="mb-0 ms-2">Connection Progress - Employee</h4>
                            <div class="row gx-1 mb-0">
                                <div class="col-auto">
                                    <div class="form-control"> 
                                        GA&nbsp;<x-master.ga-filter class="float-end"/>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    @if (request()->geo_area)
                                        <div class="form-control"> 
                                            Charge Area&nbsp;<x-master.charge-area-filter class="float-end"/>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-auto">
                                    <div class="form-control"> 
                                        Role&nbsp;<x-admin.role-filter class="float-end"/>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div>
                                        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="report-emp-activity-list">
                        <div class="alert alert-info mb-0 fw-semibold">
                            Please Select GA.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="emp-progress" role="tabpanel" aria-labelledby="emp-progress-tab" tabindex="0">
            <nav>
                <div class="nav nav-tabs bg-white" id="nav-tab3" role="tablist">
                    <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap active" id="nav-team-progress-tab" data-bs-toggle="tab" data-bs-target="#nav-team-progress" type="button" role="tab" aria-controls="nav-team-progress" aria-selected="true"><i class="bi bi-people me-1"></i>&nbsp;Team Wise Report</button>
                    <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-status-activity-tab" data-bs-toggle="tab" data-bs-target="#nav-status-activity" type="button" role="tab" aria-controls="nav-status-activity" aria-selected="true"><i class="bi bi-activity me-1"></i>&nbsp;Employee Wise Report</button>                          
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContentEmp">
                <div class="tab-pane fade show active" id="nav-team-progress" role="tabpanel" aria-labelledby="nav-team-progress-tab"tabindex="0">
                    <form action="{{ url('reports/consumer/teamProgress') }}" id="report-team-progress-search-form" method="GET">
                        <div class="d-flex justify-content-between align-items-center border  border-primary bg-light my-2 p-2 rounded">
                            <h4 class="mb-0 ms-2">Team Progress Report</h4>
                            <div class="row g-1">
                                <div class="col-auto mt-4">
                                    <div class="form-control mt-2">
                                        GA&nbsp;<x-master.ga-filter class="float-end" />
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="team_date_from" class="form-label mb-1">From Date</label>
                                    <div class="input-group">
                                        <input type="text" name="team_date_from" id="team_date_from" class="form-control" placeholder="DD-MM-YYYY">
                                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="team_date_to" class="form-label mb-1">To Date</label>
                                    <div class="input-group">
                                        <input type="text" name="team_date_to" id="team_date_to" class="form-control" placeholder="DD-MM-YYYY">
                                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="team_date">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-success mt-1"><i class="bi bi-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="report-team-progress-list"></div>
                </div>
                <div class="tab-pane fade" id="nav-status-activity" role="tabpanel" aria-labelledby="nav-status-activity-tab"tabindex="0">
                    <form action="{{ url('reports/consumer/activity') }}" id="report-status-activity-search-form" method="GET">
                        <div class="d-flex justify-content-between align-items-center border my-2 border-info bg-light p-2 rounded">
                            <h4 class="mb-0 ms-2">Employee Progress Report</h4>
                            <div class="row g-1">
                                <div class="col-auto mt-4">
                                    <div class="form-control mt-2">
                                        GA&nbsp;<x-master.ga-filter class="float-end" />
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="conv_date_from" class="form-label mb-1">From Date</label>
                                    <div class="input-group">
                                        <input type="text" name="conv_date_from" id="conv_date_from" class="form-control" placeholder="DD-MM-YYYY">
                                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="conv_date_to" class="form-label mb-1">To Date</label>
                                    <div class="input-group">
                                        <input type="text" name="conv_date_to" id="conv_date_to" class="form-control" placeholder="DD-MM-YYYY">
                                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <label for="conv_date">&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-success mt-1"><i class="bi bi-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="report-status-activity-list"></div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-ga-teams" role="tabpanel" aria-labelledby="nav-ga-teams-tab" tabindex="0">
            <form action="{{ url('reports/consumer/teams') }}" id="report-ga-teams-search-form" method="GET">
                <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 rounded">
                    <h4 class="mb-0 ms-2">Teams Data</h4>
                </div>    
            </form>
            <div id="report-ga-teams-list"> 
                @include('reports.consumer.waiting-report.teams')
            </div>
        </div>
    </div>


    {{-- <nav>
        <div class="nav nav-tabs d-flex flex-nowrap gap-2" id="nav-tab2" role="tablist">
            <button class="nav-link active fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-consumers-wait-tab" data-bs-toggle="tab" data-bs-target="#nav-consumers-wait" type="button" role="tab" aria-controls="nav-consumers" aria-selected="true"><i class="bi bi-app-indicator"></i>&nbsp;Connection Progress - GA&nbsp;<span class="badge text-bg-success">{{ numberFormat($wait_list) }}</span></button>
            <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-emp-activity-tab" data-bs-toggle="tab" data-bs-target="#nav-emp-activity" type="button" role="tab" aria-controls="nav-emp-activity" aria-selected="true"><i class="bi bi-rocket-takeoff me-1"></i>&nbsp;Connection Progress - Employee</button>               
            <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-status-activity-tab" data-bs-toggle="tab" data-bs-target="#nav-status-activity" type="button" role="tab" aria-controls="nav-status-activity" aria-selected="true"><i class="bi bi-activity me-1"></i>&nbsp;Employee Progress Report</button>                          
            <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-team-progress-tab" data-bs-toggle="tab" data-bs-target="#nav-team-progress" type="button" role="tab" aria-controls="nav-team-progress" aria-selected="true"><i class="bi bi-activity me-1"></i>&nbsp;Team Progress Report</button>                          
            <button class="nav-link fs-5 border border-bottom-0 me-2 text-nowrap" id="nav-ga-teams-tab" data-bs-toggle="tab" data-bs-target="#nav-ga-teams" type="button" role="tab" aria-controls="nav-ga-teams" aria-selected="true"><i class="bi bi-people-fill me-1"></i>&nbsp;Teams&nbsp;<span class="badge bg-primary">{{ numberFormat($total_teams) }}</span></button>
        </div>
    </nav> 
    <div class="tab-content bg-white p-2 border border-top-0 d-none" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-consumers-wait" role="tabpanel" aria-labelledby="nav-consumers-wait-tab" tabindex="0">
            <form action="{{ url('reports/consumer/connectionProgress') }}" id="report-cns-waiting-search-form" method="GET">
                <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 rounded">
                    <h4 class="mb-0">Connection Progress</h4>
                    <div class="row g-1">
                        <div class="col-auto">
                            <select name="connect_type_id" id="connect_type_id" class="form-select">
                                <option value="">All Connections</option>
                                @foreach ($connection_types as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="onboard_segment_id" id="onboard_segment_id" class="form-select">
                                <option value="">All Segments</option>
                                @foreach ($segments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
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
        <div class="tab-pane fade" id="nav-emp-activity" role="tabpanel" aria-labelledby="nav-emp-activity-tab" tabindex="0">
            <form action="{{ url('reports/consumer/employee/activity') }}" id="report-emp-activity-search-form" method="GET">
                <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 bg-secondary-subtle rounded">
                    <h4 class="mb-0 ms-2">Connection Progress - Employee</h4>
                    <div class="row gx-1 mb-0">
                        <div class="col-auto">
                            <div class="form-control"> 
                                GA&nbsp;<x-master.ga-filter class="float-end"/>
                            </div>
                        </div>
                        <div class="col-auto">
                            @if (request()->geo_area)
                                <div class="form-control"> 
                                    Charge Area&nbsp;<x-master.charge-area-filter class="float-end"/>
                                </div>
                            @endif
                        </div>
                        <div class="col-auto">
                            <div class="form-control"> 
                                Role&nbsp;<x-admin.role-filter class="float-end"/>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div>
                                <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="report-emp-activity-list">
                <div class="alert alert-info mb-0 fw-semibold">
                    Please Select GA.
                </div>
            </div>
        </div>
        <div class="tab-pane fade show active" id="nav-status-activity" role="tabpanel" aria-labelledby="nav-status-activity-tab"tabindex="0">
            <form action="{{ url('reports/consumer/activity') }}" id="report-status-activity-search-form" method="GET">
                <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 rounded">
                    <h4 class="mb-0 ms-2">Employee Progress Report</h4>
                    <div class="row g-1">
                        <div class="col-auto mt-4">
                            <div class="form-control mt-2">
                                GA&nbsp;<x-master.ga-filter class="float-end" />
                            </div>
                        </div>
                        <div class="col-auto">
                            <label for="conv_date_from" class="form-label mb-1">From Date</label>
                            <div class="input-group">
                                <input type="text" name="conv_date_from" id="conv_date_from" class="form-control" placeholder="DD-MM-YYYY">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label for="conv_date_to" class="form-label mb-1">To Date</label>
                            <div class="input-group">
                                <input type="text" name="conv_date_to" id="conv_date_to" class="form-control" placeholder="DD-MM-YYYY">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label for="conv_date">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-success mt-1"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="report-status-activity-list"></div>
        </div>

        <div class="tab-pane fade" id="nav-ga-teams" role="tabpanel" aria-labelledby="nav-ga-teams-tab" tabindex="0">
            <form action="{{ url('reports/consumer/teams') }}" id="report-ga-teams-search-form" method="GET">
                <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-3 rounded">
                    <h4 class="mb-0 ms-2">Teams Report</h4>
                </div>    
            </form>
            <div id="report-ga-teams-list"> 
                @include('reports.consumer.waiting-report.teams')
            </div>
        </div>

        <div class="tab-pane fade" id="nav-team-progress" role="tabpanel" aria-labelledby="nav-team-progress-tab"tabindex="0">
            <form action="{{ url('reports/consumer/teamProgress') }}" id="report-team-progress-search-form" method="GET">
                <div class="d-flex justify-content-between align-items-center border my-2 bg-light p-2 rounded">
                    <h4 class="mb-0 ms-2">Team Progress Report</h4>
                    <div class="row g-1">
                        <div class="col-auto mt-4">
                            <div class="form-control mt-2">
                                GA&nbsp;<x-master.ga-filter class="float-end" />
                            </div>
                        </div>
                        <div class="col-auto">
                            <label for="team_date_from" class="form-label mb-1">From Date</label>
                            <div class="input-group">
                                <input type="text" name="team_date_from" id="team_date_from" class="form-control" placeholder="DD-MM-YYYY">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label for="team_date_to" class="form-label mb-1">To Date</label>
                            <div class="input-group">
                                <input type="text" name="team_date_to" id="team_date_to" class="form-control" placeholder="DD-MM-YYYY">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <label for="team_date">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-success mt-1"><i class="bi bi-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="report-team-progress-list"></div>
        </div>
    </div>--}}
</div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'report-cns-waiting'])
    @include('scripts.ajax-form-search', ['form' => 'report-status-activity'])
    @include('scripts.ajax-form-search', ['form' => 'report-emp-activity'])
    @include('scripts.ajax-form-search', ['form' => 'report-team-progress'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to', 'status_date', 'conv_date_from', 'conv_date_to', 'team_date_from', 'team_date_to']])
@endpush