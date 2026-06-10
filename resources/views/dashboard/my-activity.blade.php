@extends('layouts.layout')

@section('title', 'My Activity')
@section('page-title', 'My Activity')

@section('page-content')
    <div class="fluid-container">
        @if ($roles->count() > 0)
            {{-- <div class="m-2">Total Consumers - <strong>{{ numberFormat(array_sum($consumers_count)) }}</strong></div> --}}
            @php
                $roleData = [
                    \App\Enums\Role::MARKETING->value => [
                        'status' => 'REGISTRATION',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::PRE_REGISTER->value,
                        'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                        'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0),
                    ],

                    \App\Enums\Role::MDPE->value => [
                        'status' => 'ACCEPTANCE',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::REGISTER->value,
                        'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                        'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0),
                    ],

                    \App\Enums\Role::GI_ENGINEER->value => [
                        'status' => 'EXECUTION',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::ACCEPT->value,
                        'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                        'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0),
                    ],

                    \App\Enums\Role::HSE->value => [
                        'status' => 'HSC',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::EXECUTE->value,
                        'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                        'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::HSC->value] ?? 0),
                    ],

                    \App\Enums\Role::ACTIVATION->value => [
                        'status' => 'ACTIVATION',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::HSC->value,
                        'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                        'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0),
                    ],
                ];
            @endphp
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>Progress</th>
                            <th>Unassigned</th>
                            <th>Assigned</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $id => $role)
                            <tr>
                                <td>{{ $roleData[$id]['status'] ?? $role }}</td>
                                <td><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$roleData[$id]['status_id']], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [2]]) }}" target="_blank">{{ $roleData[$id]['unassigned'] }}</a></td>
                                <td><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$roleData[$id]['status_id']], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [0,1]]) }}" target="_blank">{{ $roleData[$id]['assigned'] }}</a></td>
                                <td>{{ $roleData[$id]['pending'] ?? $role }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Total</td>
                                <td class="fw-semibold">{{ numberFormat(array_sum($consumers_count)) }}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        @endif
        <div>
            @if ($teams->count() > 0)
                @php
                    $consumers_list = $cns_status = [];
                    foreach ($consumer_teams as $key => $value) {
                        switch($value->status_id) {
                            case \App\Enums\ConsumerStatus::PRE_REGISTER->value:
                                $status_id = 1;break;
                            case \App\Enums\ConsumerStatus::REGISTER->value:
                                $status_id = \App\Enums\ConsumerStatus::PRE_REGISTER->value;break;
                            case \App\Enums\ConsumerStatus::ACCEPT->value:
                                $status_id = \App\Enums\ConsumerStatus::REGISTER->value;break;
                            case \App\Enums\ConsumerStatus::EXECUTE->value:
                                $status_id = \App\Enums\ConsumerStatus::ACCEPT->value;break;
                            case \App\Enums\ConsumerStatus::HSC->value:
                                $status_id = \App\Enums\ConsumerStatus::EXECUTE->value;break;
                            case \App\Enums\ConsumerStatus::ACTIVATE->value:
                                $status_id = \App\Enums\ConsumerStatus::HSC->value;break;
                            default:$status_id = NULL;break;
                        }
                        $consumers_list[$value->team_id][$value->status] = $value->team_count;
                        $cns_status[$value->team_id] = $status_id ?? NULL;
                    }
                    // print "<pre>";print_r($cns_status);
                @endphp
                <h3>My Teams</h3>
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>Team</th>
                            <th>Department</th>
                            <th>Pending</th>
                            <th>Completed</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team_id => $team)
                            <tr>
                                <td>{{ $team->name }}</td>
                                <td>{{ $team->departments?->name }}</td>
                                <td><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$cns_status[$team->id] ?? NULL], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [0], 'team_id' => [$team->id]]) }}" target="_blank">{{ $consumers_list[$team->id][0] ?? 0 }}</a></td>
                                <td><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$cns_status[$team->id] ?? NULL], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [1], 'team_id' => [$team->id]]) }}" target="_blank">{{ $consumers_list[$team->id][1] ?? 0 }}</a></td>
                                <td>{{ array_sum($consumers_list[$team->id] ?? []) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
@include('scripts.link-modal')