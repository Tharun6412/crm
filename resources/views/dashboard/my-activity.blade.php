@extends('layouts.layout')

@section('title', 'My Activity Dashboard')
@section('page-title', 'My Activity Dashboard')

@section('page-content')
    <div class="fluid-container">
        <h4 class="border-start border-5 border-info p-1">My Responsible Consumers</h4>
        @if ($roles->count() > 0)
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-success align-middle">
                        <tr>
                            <th rowspan="2">S.No</th>
                            <th rowspan="2">Department</th>
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
                        @foreach($filteredRoleData as $id => $role)
                            <tr>
                                <td  class="text-center" width="1%">{{ $loop->iteration }}</td>
                                <td>{{ $role['status'] ?? $role }}</td>
                                <td  class="text-center fs-5">
                                    <a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$role['status_id']],'target_status' => [$role['status_id']+1],'ugas' => auth()->user()->ga->pluck('id')->toArray(), 'ucas' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [2]]) }}" target="_blank">{{ $role['unassigned'] }}</a>
                                    {{-- <a href="{{ url('myActivity/getConsumersByCa') }}?{{ http_build_query(['cns_status' => $role['status_id'], 'geo_area' => auth()->user()->ga->pluck('id')->toArray()]) }}" class="link-canvas float-end">{{ $role['unassigned'] }}</a> --}}
                                </td>
                                <td class="text-center fs-5"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$role['status_id']],'target_status' => [$role['status_id']+1], 'ugas' => auth()->user()->ga->pluck('id')->toArray(), 'ucas' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [0], 'user_id' => auth()->id()]) }}" target="_blank">{{ $role['consumers_pending'] }}</a></td>
                                <td class="text-center fs-5">{{ $role['pending'] }}</td>
                                <td class="text-center fs-5"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$role['status_id']],'target_status' => [$role['status_id']+1], 'ugas' => auth()->user()->ga->pluck('id')->toArray(), 'ucas' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [1]]) }}" target="_blank">{{ $role['consumers_completed'] }}</a></td>
                            </tr>
                            @endforeach
                            <tr class="fs-5">
                                <td colspan="4" class="text-end fw-semibold">Total Count</td>
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
                            <tr>
                                <td width="1%">{{ $loop->iteration }}</td>
                                <td>{{ $team->name }}</td>
                                <td>{{ $team->departments?->name }}</td>
                                <td class="text-center fs-5"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => $cns_status[$team->id] ?? NULL,'target_status' => $cns_status[$team->id] ?? NULL,'ugas' => auth()->user()->ga->pluck('id')->toArray(), 'ucas' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [0], 'team_id' => [$team->id]]) }}" target="_blank">{{ $consumers_list[$team->id][0] ?? 0 }}</a></td>
                                <td class="text-center fs-5"><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => $cns_status[$team->id] ?? NULL,'target_status' => $cns_status[$team->id] ?? NULL, 'ugas' => auth()->user()->ga->pluck('id')->toArray(), 'ucas' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [1], 'team_id' => [$team->id]]) }}" target="_blank">{{ $consumers_list[$team->id][1] ?? 0 }}</a></td>
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
