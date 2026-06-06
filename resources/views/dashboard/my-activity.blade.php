@extends('layouts.layout')

@section('title', 'My Activity')
@section('page-title', 'My Activity')

@section('page-content')
    <div class="fluid-container">
        @if ($roles->count() > 0)
            <div class="m-2">Total Consumers - <strong>{{ numberFormat(array_sum($consumers_count->toArray())) }}</strong></div>
            @php
                $roleData = [
                    \App\Enums\Role::MARKETING->value => [
                        'status' => 'REGISTRATION',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::REGISTER->value,
                    ],

                    \App\Enums\Role::MDPE->value => [
                        'status' => 'ACCEPTANCE',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::ACCEPT->value,
                    ],

                    \App\Enums\Role::GI_ENGINEER->value => [
                        'status' => 'EXECUTION',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::EXECUTE->value,
                    ],

                    \App\Enums\Role::HSE->value => [
                        'status' => 'HSC',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::HSC->value,
                    ],

                    \App\Enums\Role::ACTIVATION->value => [
                        'status' => 'ACTIVATION',
                        'pending' => $consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0,
                        'completed' => $completed_consumers[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                        'total' => $consumers_count[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0,
                        'status_id' => \App\Enums\ConsumerStatus::HSC->value,
                    ],
                ];
            @endphp
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Progress</th>
                            @foreach($roles as $id => $role)
                                <th>{{ $roleData[$id]['status'] ?? $role }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total</td>
                            @foreach($roles as $id => $role)
                                <td><a href="{{ url('consumers') }}">{{ $roleData[$id]['total'] ?? 0 }}</a></td>
                            @endforeach
                        </tr>

                        <tr>
                            <td>Pending</td>
                            @foreach($roles as $id => $role)
                                <td>{{ $roleData[$id]['pending'] ?? 0 }}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td>Completed</td>
                            @foreach($roles as $id => $role)
                                <td>{{ $roleData[$id]['completed'] ?? 0 }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
        <div class="row">
            {{-- <div class="col-auto">
                @if ($roles->count() > 0)
                    <div class="col-sm-1">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Consumers pending action at your stage.</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach ($roles as $id => $role)
                                    @switch($id)
                                        @case(\App\Enums\Role::MARKETING->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0;
                                                $status_val = "REGISTRATION";
                                                $status_id = \App\Enums\ConsumerStatus::PRE_REGISTER->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::MDPE->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0;
                                                $status_val = "ACCEPTANCE";
                                                $status_id = \App\Enums\ConsumerStatus::REGISTER->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::GI_ENGINEER->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0;
                                                $status_val = "EXECUTION";
                                                $status_id = \App\Enums\ConsumerStatus::ACCEPT->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::HSE->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0;
                                                $status_val = "HSC";
                                                $status_id = \App\Enums\ConsumerStatus::EXECUTE->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::ACTIVATION->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0;
                                                $status_val = "ACTIVATION";
                                                $status_id = \App\Enums\ConsumerStatus::HSC->value;
                                            @endphp
                                            @break
                                        @default
                                            @php
                                                $count = 0;
                                                $status_val = $status_id = '';
                                            @endphp
                                            @break
                                    @endswitch
                                    <li class="list-group-item">{{ $status_val }}&nbsp;-&nbsp;<a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [$status_id], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->cas->pluck('id')->toArray()]) }}" target="_blank"><strong>{{ numberFormat($count) }}</strong></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div> --}}
            <div class="col-auto">
                @if ($teams->count() > 0)
                    <div class="col-sm-1">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Team List with Pending Consumers</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach ($teams as $id => $team)
                                    <li class="list-group-item"><a href="{{ url('admin/teams/show/'.$team->id) }}" class="link-modal">{{ $team->name }}</a>&nbsp;-&nbsp;<strong>{{ numberFormat($consumer_team_count[$team->id] ?? 0) }}</strong></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            {{-- <div class="col-auto">
                @if ($roles->count() > 0)
                    <div class="col-sm-1">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Consumers Completed List</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach ($roles as $id => $role)
                                    @switch($id)
                                        @case(\App\Enums\Role::MARKETING->value)
                                            @php
                                                $count = $completed_consumers[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0;
                                                $status_val = "REGISTRATION";
                                                $status_id = \App\Enums\ConsumerStatus::REGISTER->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::MDPE->value)
                                            @php
                                                $count = $completed_consumers[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0;
                                                $status_val = "ACCEPTANCE";
                                                $status_id = \App\Enums\ConsumerStatus::ACCEPT->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::GI_ENGINEER->value)
                                            @php
                                                $count = $completed_consumers[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0;
                                                $status_val = "EXECUTION";
                                                $status_id = \App\Enums\ConsumerStatus::EXECUTE->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::HSE->value)
                                            @php
                                                $count = $completed_consumers[\App\Enums\ConsumerStatus::HSC->value] ?? 0;
                                                $status_val = "HSC";
                                                $status_id = \App\Enums\ConsumerStatus::HSC->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::ACTIVATION->value)
                                            @php
                                                $count = $completed_consumers[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0;
                                                $status_val = "ACTIVATION";
                                                $status_id = \App\Enums\ConsumerStatus::ACTIVATE->value;
                                            @endphp
                                            @break
                                        @default
                                            @php
                                                $count = 0;
                                                $status_val = $status_id = '';
                                            @endphp
                                            @break
                                    @endswitch
                                    <li class="list-group-item">{{ $status_val }}&nbsp;-&nbsp;<a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [$status_id], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->cas->pluck('id')->toArray()]) }}" target="_blank"><strong>{{ numberFormat($count) }}</strong></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div> --}}
        </div>
    </div>
@endsection
@include('scripts.link-modal')