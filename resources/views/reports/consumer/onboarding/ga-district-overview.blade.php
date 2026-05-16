{{-- Employee collection show details --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Onboarding Status Overview</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $request_data['ga_name'] }}&nbsp;{{ $connect_type ? "> ".$connect_type : '' }}&nbsp;{{ $segment ? "> ".$segment : '' }}</h1>
            {{-- Consumer sattus --}}
            <div class="mt-2">
                <div class="row row-cols-8 g-2 mb-2">
                    <div class="col-2">
                        <div class="bg-success bg-gradient rounded text-white py-1 px-2 fs-5">District</div>
                    </div>
                    <div class="col">
                        <div class="text-bg-yellow bg-gradient rounded text-danger text-center py-1 px-2 fs-5">TR</div>
                    </div>
                    <div class="col">
                        <div class="bg-primary bg-gradient rounded text-white text-center py-1 px-2 fs-5">Registered</div>
                    </div>
                    <div class="col">
                        <div class="bg-info bg-gradient rounded text-white text-center py-1 px-2 fs-5">Verified</div>
                    </div>
                    <div class="col">
                        <div class="bg-dark bg-gradient rounded text-white text-center py-1 px-2 fs-5">Executed</div>
                    </div>
                    <div class="col">
                        <div class="text-bg-purple bg-gradient rounded text-white text-center py-1 px-2 fs-5">HSC</div>
                    </div>
                    <div class="col">
                        <div class="bg-success bg-gradient rounded text-white text-center py-1 px-2 fs-5">Activated</div>
                    </div>
                    <div class="col">
                        <div class="bg-warning bg-gradient rounded text-white text-center py-1 px-2 fs-5">TD</div>
                    </div>
                    <div class="col">
                        <div class="bg-danger bg-gradient rounded text-white text-center py-1 px-2 fs-5">PD</div>
                    </div>
                    <div class="col">
                        <div class="bg-secondary bg-gradient rounded text-white text-center py-1 px-2 fs-5">Rejected</div>
                    </div>
                </div>
                @php
                    $tot_pre_reg_sum = $tot_reg_sum = $tot_ver_sum = $tot_exe_sum = $tot_hsc_sum = $tot_act_sum = $tot_td_sum = $tot_pd_sum = $tot_reject_sum = 0;
                    // Consumer Status Cummulative Preparation 
                    $registerStatus = [
                        \App\Enums\ConsumerStatus::REGISTER->value,
                        \App\Enums\ConsumerStatus::ACCEPT->value,
                        \App\Enums\ConsumerStatus::EXECUTE->value,
                        \App\Enums\ConsumerStatus::HSC->value,
                        \App\Enums\ConsumerStatus::ACTIVATE->value,
                        \App\Enums\ConsumerStatus::TD->value,
                        \App\Enums\ConsumerStatus::PD->value,
                        \App\Enums\ConsumerStatus::REJECT->value,
                    ];
                    $acceptStatus = [
                        \App\Enums\ConsumerStatus::ACCEPT->value,
                        \App\Enums\ConsumerStatus::EXECUTE->value,
                        \App\Enums\ConsumerStatus::HSC->value,
                        \App\Enums\ConsumerStatus::ACTIVATE->value,
                        \App\Enums\ConsumerStatus::TD->value,
                        \App\Enums\ConsumerStatus::PD->value,
                    ];
                    $executeStatus = [
                        \App\Enums\ConsumerStatus::EXECUTE->value,
                        \App\Enums\ConsumerStatus::HSC->value,
                        \App\Enums\ConsumerStatus::ACTIVATE->value,
                        \App\Enums\ConsumerStatus::TD->value,
                        \App\Enums\ConsumerStatus::PD->value,
                    ];
                    $hscStatus = [
                        \App\Enums\ConsumerStatus::HSC->value,
                        \App\Enums\ConsumerStatus::ACTIVATE->value,
                        \App\Enums\ConsumerStatus::TD->value,
                        \App\Enums\ConsumerStatus::PD->value,
                    ];
                    $activateStatus = [
                        \App\Enums\ConsumerStatus::ACTIVATE->value,
                        \App\Enums\ConsumerStatus::TD->value,
                        \App\Enums\ConsumerStatus::PD->value,
                    ];
                    $tdStatus = [
                        \App\Enums\ConsumerStatus::TD->value,
                        \App\Enums\ConsumerStatus::PD->value,
                    ];
                    $pdStatus = [
                        \App\Enums\ConsumerStatus::PD->value,
                    ];
                    $rejectStatus = [
                        \App\Enums\ConsumerStatus::REJECT->value,
                    ];
                @endphp
                @foreach($districts as $district)
                    @php
                        // Consumer Status
                        $register = $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::REGISTER->value] ?? 0;
                        $accept = $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0;
                        $execute = $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0;
                        $hsc = $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::HSC->value] ?? 0;
                        $activate = $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0;
                        $td = $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::TD->value] ?? 0;
                        $pd = $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::PD->value] ?? 0;
                        $reject = $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::REJECT->value] ?? 0;
                        // Cumulative Status
                        $pre_reg_sum = array_sum($consumer_status_counts[$district->id] ?? []);
                        $reg_sum = $register + $accept + $execute + $hsc + $activate + $td + $pd + $reject;
                        $ver_sum = $accept + $execute + $hsc + $activate + $td + $pd;
                        $exe_sum = $execute + $hsc + $activate + $td + $pd;
                        $hsc_sum = $hsc + $activate + $td + $pd;
                        $act_sum = $activate + $td + $pd;
                        $td_sum =  $td + $pd;
                        $pd_sum =  $pd;
                        // Total Counts
                        $tot_pre_reg_sum += $pre_reg_sum;
                        $tot_reg_sum += $reg_sum;
                        $tot_ver_sum += $ver_sum;
                        $tot_exe_sum += $exe_sum;
                        $tot_hsc_sum += $hsc_sum;
                        $tot_act_sum += $act_sum;
                        $tot_td_sum += $td_sum;
                        $tot_pd_sum += $pd_sum;
                        $tot_reject_sum += $reject;
                    @endphp
                    <div class="row row-cols-10 g-2 mb-2">
                        <div class="col-2">
                            <div class="bg-success-subtle rounded py-1 px-2 fs-5 text-truncate">{{ $district->name }}</div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat(array_sum($consumer_status_counts[$district->id] ?? [])) }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'cns_status' => $registerStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($reg_sum) }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'cns_status' => $acceptStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($ver_sum) }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'cns_status' => $executeStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($exe_sum) }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'cns_status' => $hscStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($hsc_sum) }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'cns_status' => $activateStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($act_sum) }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'cns_status' => $tdStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($td_sum) }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'cns_status' => $pdStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($pd_sum) }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'district' => [$district->id], 'cns_status' => $rejectStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($reject) }}</a>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- Totals --}}
                <div class="row row-cols-10 g-2 mb-2">
                    <div class="col-2">
                        <div class="bg-info-subtle rounded py-1 px-2 fs-5 fw-semibold text-truncate text-end">Totals</div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_pre_reg_sum) }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'cns_status' => $registerStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_reg_sum) }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'cns_status' => $acceptStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_ver_sum) }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'cns_status' => $executeStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_exe_sum) }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'cns_status' => $hscStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_hsc_sum) }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'cns_status' => $activateStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_act_sum) }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'cns_status' => $tdStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_td_sum) }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'cns_status' => $pdStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_pd_sum) }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$request_data['ga_id']], 'cns_status' => $rejectStatus, 'connection_type_id' => request()->connection_type_id, 'segments' => [request()->segments]]) }}" target="_blank">{{ numberFormat($tot_reject_sum) }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.export-table', [
    'table' => 'emp-clcn-dtls',
    'button' => 'exportClnBtn',
    'tabBased' => false,
    'filename' => 'employee-collection-details',
    'sheet'    => 'Report',
])