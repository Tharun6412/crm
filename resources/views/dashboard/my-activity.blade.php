@extends('layouts.layout')

@section('title', 'My Activity Dashboard')
@section('page-title', 'My Activity Dashboard')

@section('page-content')
    <div class="fluid-container">
        <h4 class="border-start border-5 border-info p-1">My Responsible Consumers</h4>
        @if ($delivery_units->count() > 0)
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-success align-middle">
                        <tr>
                            <th rowspan="2">S.No</th>
                            <th rowspan="2">Delivery Unit</th>
                            <th rowspan="2">Action Required</th>
                            <th colspan="3" class="text-center">Consumers Waiting</th>
                            <th rowspan="2" class="text-center">Completed</th>
                        </tr>
                        <tr class="text-center">
                            <th class="bg-success bg-opacity-75 text-center">UnAssigned</th>
                            <th class="bg-success bg-opacity-75 text-center">Assigned</th>
                            <th class="bg-success bg-opacity-75 text-center">Totals</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($delivery_units as $id => $du)
                            @php
                                $current_status = $consumers_count[$du->responsible_status_id] ?? 0;
                                $assigned = $assign_list[$du->action_status_id][0] ?? 0;
                                // UnAssigned
                                $unassigned = abs($current_status - $assigned);
                                // $total = $assigned + $unassigned;
                                $completed = $assign_list[$du->action_status_id][1] ?? 0;
                                // charge Area Ids
                                $caIds = $du->areas->pluck('ca_id')->filter()->unique()->values()->toArray();
                                $area_ids = $du->areas->pluck('id')->unique()->toArray();
                            @endphp
                            <tr>
                                <td  class="text-center" width="1%">{{ $loop->iteration }}</td>
                                <td><a href="{{ url('lms/deliveryUnits/'.$du->id) }}" class="link-modal">{{ $du->name }}</a></td>
                                <td>{{ $du->actionStatus?->name }}</td>
                                <td  class="text-center fs-5"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$du->responsible_status_id],'target_status' => [$du->action_status_id],'ugas' => [$du->ga_id], 'ucas' => $caIds, 'area_ids' => $area_ids, 'status' => [2], 'du_id' => $du->id]) }}" target="_blank">{{ $unassigned }}</a>
                                </td>
                                <td class="text-center"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$du->responsible_status_id],'target_status' => [$du->action_status_id],'ugas' => [$du->ga_id], 'ucas' => $caIds, 'area_ids' => $area_ids, 'status' => [0], 'du_id' => $du->id]) }}" target="_blank">{{ $assigned }}</a></td>
                                <td class="text-center">{{ $current_status }}</td>
                                <td class="text-center"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$du->responsible_status_id],'target_status' => [$du->action_status_id],'ugas' => [$du->ga_id], 'ucas' => $caIds, 'area_ids' => $area_ids, 'status' => [1], 'du_id' => $du->id]) }}" target="_blank">{{ $completed }}</a></td>
                            </tr>
                        @endforeach
                            <tr class="fs-5">
                                <td colspan="5" class="text-end fw-semibold">Total Count</td>
                                <td class="fw-semibold text-center">{{ numberFormat(array_sum($consumers_count)) }}</td>
                                <td></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">
                <div>No Consumers found</div>
            </div>
        @endif
        <div class="mb-4">
            <h4 class="border-start border-5 border-warning p-1">My Team Assigned Consumers</h4>
            @if ($teams->count() > 0)
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-success align-middle">
                        <tr>
                            <th rowspan="2">S.No</th>
                            <th rowspan="2">Team</th>
                            <th rowspan="2">Team Lead</th>
                            <th rowspan="2">Delivery Unit</th>
                            <th rowspan="2">Delivery Manager</th>
                            <th rowspan="2">Department</th>
                            <th colspan="3" class="text-center">Consumers</th>
                        </tr>
                        <tr>
                            <th class="bg-success bg-opacity-75 text-center">Pending</th>
                            <th class="bg-success bg-opacity-75 text-center">Completed</th>
                            <th class="bg-success bg-opacity-75 text-center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team_id => $team)
                            @php
                                $du_cas = $team?->deliveryUnit?->areas?->pluck('ca_id')->filter()->unique()->values()->toArray();
                                $du_areas = $team?->deliveryUnit?->areas?->pluck('id')->unique()->toArray();
                            @endphp
                            <tr>
                                <td width="1%">{{ $loop->iteration }}</td>
                                <td><a href="{{ url('lms/teams/show/'.$team->id) }}" class="link-modal">{{ $team->name }}</a></td>
                                <td>{{ $team->responsibleUser?->name }}</td>
                                <td>{{ $team->deliveryUnit?->name }}</td>
                                <td>{{ $team->deliveryUnit?->duIncharge?->name }}</td>
                                <td>{{ $team->departments?->name }}</td>
                                <td class="text-center fs-5"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => $cns_status[$team->id] ?? NULL,'target_status' => $cns_status[$team->id] ?? NULL,'ugas' => [$team->deliveryUnit->ga_id], 'ucas' => $du_cas, 'area_ids' => $du_areas, 'status' => [0], 'team_id' => [$team->id]]) }}" target="_blank">{{ $consumers_list[$team->id][0] ?? 0 }}</a></td>
                                <td class="text-center fs-5"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => $cns_status[$team->id] ?? NULL,'target_status' => $cns_status[$team->id] ?? NULL, 'ugas' => [$team->deliveryUnit->ga_id], 'ucas' => $du_cas, 'area_ids' => $du_areas, 'status' => [1], 'team_id' => [$team->id]]) }}" target="_blank">{{ $consumers_list[$team->id][1] ?? 0 }}</a></td>
                                <td class="text-center fs-5">{{ array_sum($consumers_list[$team->id] ?? []) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">
                    <div>No Teams available</div>
                </div>
            @endif
        </div>
        {{-- @if ($roles->count() > 0) --}}
        <div class="table-responsive mt-3">
            <h4 class="border-start border-5 border-primary p-1">My Work Report</h4>
            <table class="table table-bordered table-striped table-hover text-center align-middle">
                <thead class="table-success">
                    <tr>
                        @foreach ($status_list as $list)
                            <th>{{ $list->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($status_list as $status)
                            <td class="fs-5">
                                <a href="{{ url('myActivity/myConsumersList') }}?{{ http_build_query([
                                    'status_id' => $status->id, 
                                    'user_id' => auth()->id(),
                                ]) }}" class="link-modal">{{ $completed_consumers[$status->id] ?? 0}}</a>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
        {{-- @endif --}}
    </div>
@endsection
@include('scripts.link-modal')
@include('scripts.link-canvas')
