@extends('layouts.layout')

@section('title', 'My Activity Dashboard')
@section('page-title', 'My Activity Dashboard')

@section('page-content')
    <div class="fluid-container">
        {{-- Data Preparation --}}
        @php
            $roleData = [
                \App\Enums\Role::MARKETING->value => [
                    'status' => 'REGISTRATION',
                    'pending' => $consumers_count[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0,
                    'my_pending_list' => $user_pending_list[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                    'completed' => $completed_consumers[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                    'total' => $consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                    'status_id' => \App\Enums\ConsumerStatus::PRE_REGISTER->value,
                    'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                    'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0),
                ],

                \App\Enums\Role::MDPE->value => [
                    'status' => 'ACCEPTANCE',
                    'pending' => $consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                    'my_pending_list' => $user_pending_list[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                    'completed' => $completed_consumers[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                    'total' => $consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                    'status_id' => \App\Enums\ConsumerStatus::REGISTER->value,
                    'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                    'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0),
                ],

                \App\Enums\Role::GI_ENGINEER->value => [
                    'status' => 'EXECUTION',
                    'pending' => $consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                    'my_pending_list' => $user_pending_list[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                    'completed' => $completed_consumers[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                    'total' => $consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                    'status_id' => \App\Enums\ConsumerStatus::ACCEPT->value,
                    'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                    'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0),
                ],

                \App\Enums\Role::HSE->value => [
                    'status' => 'HSC',
                    'pending' => $consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                    'my_pending_list' => $user_pending_list[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                    'completed' => $completed_consumers[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                    'total' => $consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                    'status_id' => \App\Enums\ConsumerStatus::EXECUTE->value,
                    'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                    'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::HSC->value] ?? 0),
                ],

                \App\Enums\Role::ACTIVATION->value => [
                    'status' => 'ACTIVATION',
                    'pending' => $consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                    'my_pending_list' => $user_pending_list[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                    'completed' => $completed_consumers[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                    'total' => $consumers_count[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                    'status_id' => \App\Enums\ConsumerStatus::HSC->value,
                    'assigned' => $assigned_consumers[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                    'unassigned' => ($consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0) - ($assigned_consumers[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0),
                ],
            ];
            // print "<pre>"; print_r($roleData[\App\Enums\Role::MARKETING->value]['my_pending_list']);
        @endphp
        @if ($roles->count() > 0)
            <div class="table-responsive">
                <h4>My Responsible Consumers</h4>
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th rowspan="2">S.No</th>
                            <th rowspan="2">Department</th>
                            <th colspan="3" class="text-center">Consumers</th>
                        </tr>
                        <tr>
                            <th>UnAssigned</th>
                            <th>Assigned</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $id => $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $roleData[$id]['status'] ?? $role }}</td>
                                <td><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$roleData[$id]['status_id']], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [2]]) }}" target="_blank">{{ $roleData[$id]['unassigned'] }}</a></td>
                                <td><a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(['cns_status' => [$roleData[$id]['status_id']], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->ca->pluck('id')->toArray(), 'status' => [0,1]]) }}" target="_blank">{{ $roleData[$id]['assigned'] }}</a></td>
                                <td>{{ $roleData[$id]['pending'] ?? $role }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="text-end fw-semibold">Total</td>
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
                <h4>My Team Assigned Consumers</h4>
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-success">
                        <tr>
                            <th rowspan="2">S.No</th>
                            <th rowspan="2">Team</th>
                            <th rowspan="2">Department</th>
                            <th colspan="3" class="text-center">Consumers</th>
                        </tr>
                        <tr>
                            <th>Pending</th>
                            <th>Completed</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($teams as $team_id => $team)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
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
        @if ($roles->count() > 0)
            <h4>My Work Report</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            @foreach ($status_list as $list)
                                <th>{{ $list->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($status_list as $status)
                                <td>
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
        @endif
    </div>
@endsection
@include('scripts.link-modal')