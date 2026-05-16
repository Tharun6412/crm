{{-- Consumer sattus --}}

<div class="mt-2">
    <div class="row row-cols-8 g-2 mb-2">
        <div class="col-2">
            <div class="bg-success bg-gradient rounded text-white py-1 px-2 fs-5">GA</div>
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
        // Register Status
        $registerStatuses = [
            \App\Enums\ConsumerStatus::REGISTER->value,
            \App\Enums\ConsumerStatus::ACCEPT->value,
            \App\Enums\ConsumerStatus::EXECUTE->value,
            \App\Enums\ConsumerStatus::HSC->value,
            \App\Enums\ConsumerStatus::ACTIVATE->value,
            \App\Enums\ConsumerStatus::TD->value,
            \App\Enums\ConsumerStatus::PD->value,
        ];
        // Verify Status
        $acceptStatuses = [
            \App\Enums\ConsumerStatus::ACCEPT->value,
            \App\Enums\ConsumerStatus::EXECUTE->value,
            \App\Enums\ConsumerStatus::HSC->value,
            \App\Enums\ConsumerStatus::ACTIVATE->value,
            \App\Enums\ConsumerStatus::TD->value,
            \App\Enums\ConsumerStatus::PD->value,
        ];
        // Execute Status
        $executeStatuses = [
            \App\Enums\ConsumerStatus::EXECUTE->value,
            \App\Enums\ConsumerStatus::HSC->value,
            \App\Enums\ConsumerStatus::ACTIVATE->value,
            \App\Enums\ConsumerStatus::TD->value,
            \App\Enums\ConsumerStatus::PD->value,
        ];
        // HSC Status
        $hscStatuses = [
            \App\Enums\ConsumerStatus::HSC->value,
            \App\Enums\ConsumerStatus::ACTIVATE->value,
            \App\Enums\ConsumerStatus::TD->value,
            \App\Enums\ConsumerStatus::PD->value,
        ];
        $activateStatuses = [
            \App\Enums\ConsumerStatus::ACTIVATE->value,
            \App\Enums\ConsumerStatus::TD->value,
            \App\Enums\ConsumerStatus::PD->value,
        ];
        $tdStatuses = [
            \App\Enums\ConsumerStatus::TD->value,
            \App\Enums\ConsumerStatus::PD->value,
        ];
        $pdStatuses = [
            \App\Enums\ConsumerStatus::PD->value,
        ];
        $rejectStatuses = [
            \App\Enums\ConsumerStatus::REJECT->value,
        ];
    @endphp
    @foreach($geo_areas as $ga)
        @php
            // Consumer Status
            $register = $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::REGISTER->value] ?? 0;
            $accept = $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0;
            $execute = $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0;
            $hsc = $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::HSC->value] ?? 0;
            $activate = $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0;
            $td = $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::TD->value] ?? 0;
            $pd = $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::PD->value] ?? 0;
            $reject = $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::REJECT->value] ?? 0;
            // Cumulative Status
            $pre_reg_sum = array_sum($consumer_status_counts[$ga->id] ?? []);
            $reg_sum = $register + $accept + $execute + $hsc + $activate + $td + $pd;
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
                <div class="bg-body-secondary rounded py-1 px-2 fs-5 text-truncate">
                    <div class="d-flex justify-content-between">
                        <span>{{ $ga->name }}</span>
                        <a href="{{ url('reports/consumer/onboarding/getDistrictsOverviewCount') }}?{{ http_build_query(['ga_id' => [$ga->id],'ga_name' => $ga->name, 'connection_type_id' => request()->connect_type, 'segments' => request()->cumulative_segment_id]) }}" class="link-modal" title="Districts Counts">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat(array_sum($consumer_status_counts[$ga->id] ?? [])) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'cns_status' => $registerStatuses, 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($reg_sum) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'cns_status' => $acceptStatuses, 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($ver_sum) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'cns_status' => $executeStatuses, 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($exe_sum) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'cns_status' => $hscStatuses, 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($hsc_sum) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'cns_status' => $activateStatuses, 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($act_sum) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'cns_status' => $tdStatuses, 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($td_sum) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'cns_status' => $pdStatuses, 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($pd_sum) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => [$ga->id], 'cns_status' => $rejectStatuses, 'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ $reject }}</a>
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
                <a href="{{ url('consumers') }}?{{ http_build_query(['connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_pre_reg_sum) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => $registerStatuses,'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_reg_sum) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => $acceptStatuses,'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_ver_sum) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => $executeStatuses,'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_exe_sum) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => $hscStatuses,'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_hsc_sum) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => $activateStatuses,'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_act_sum) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => $tdStatuses,'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_td_sum) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => $pdStatuses,'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_pd_sum) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => $rejectStatuses,'connection_type_id' => [request()->connect_type], 'segments' => [request()->cumulative_segment_id]]) }}" target="_blank">{{ numberFormat($tot_reject_sum) }}</a>
            </div>
        </div>
    </div>
</div>
@include('scripts.link-modal')