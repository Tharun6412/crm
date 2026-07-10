{{-- targets --}}
@php
	// Targets
	$targets = [];
	foreach($targets_data as $id2 => $target) {
		$targets[$target->segment_id][str_pad($target->target_date, 2,0,STR_PAD_LEFT)] = $target->target_value;
	}
	// Potential Data
	$potential = [];
	foreach($potential_data as $id => $value){
		$potential[$value->segment_id][str_pad($value->expected_month, 2,0, STR_PAD_LEFT)] = $value->potential;
	}
	// Achieved Data
	$achieved = [];
	foreach($achieved_data as $id1 => $achieved_val) {
		$achieved[$achieved_val->segment_id][str_pad($achieved_val->expected_month,2,0,STR_PAD_LEFT)] = $achieved_val->potential;
	}
@endphp
<div class="row">
    @foreach ($segments as $segment)
        <div class="col-sm-6 col-md-6">
            <span class="fs-5"><i class="bi bi-layers"></i>&nbsp;{{ $segment->name }}&nbsp;-&nbsp;Targets&nbsp;Vs&nbsp;Potential&nbsp;Vs&nbsp;Achieved&nbsp;[FY&nbsp;{{ $y_start->format('y') }}-{{ $y_start->format('y')+1 }}]</span>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-success">
                    <thead class="bg-success">
                        <tr>
                            <th>Month</th>
                            <th class="text-end">Target (SCMD)</th>
                            <th class="text-end">Potential (SCMD)</th>
                            <th class="text-end">Achieved (SCMD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_targets = $total_potential = $total_achieved = 0;
                        @endphp
                        @for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
                            @php
                                //To Get the Totals 
                                $total_targets += $targets[$segment->id][$date->format('m')] ?? 0;
                                $total_potential += $potential[$segment->id][$date->format('m')] ?? 0;
                                $total_achieved += $achieved[$segment->id][$date->format('m')] ?? 0;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $date->format('M-y') }}</td>
                                <td class="text-end">{{ $targets[$segment->id][$date->format('m')] ?? '' }}</td>
                                <td class="text-end">
                                    <a href="{{ url('spot/prospects?'. http_build_query(['segments' => [$segment->id]]) .'&expected_date_from='. $date->copy()->startOfMonth()->format('d-m-Y') .'&expected_date_to='. $date->copy()->endOfMonth()->format('d-m-Y'). '&date_type=monthly') }}" target="_blank">
                                        {{ $potential[$segment->id][$date->format('m')] ?? '' }}
                                    </a>
                                </td>
                                <td class="text-end">
                                    <a href="{{ url('spot/prospects?'. http_build_query(['segments' => [$segment->id]]) .'&expected_date_from='. $date->copy()->startOfMonth()->format('d-m-Y') .'&expected_date_to='. $date->copy()->endOfMonth()->format('d-m-Y') . '&' . http_build_query(['sub_stage_id' => [19]])) }}" target="_blank">
                                        {{ $achieved[$segment->id][$date->format('m')] ?? '' }}
                                    </a>
                                </td>
                            </tr>
                        @endfor
                            <tr class="fw-semibold">
                                <td class="text-end">Totals</td>
                                <td class="text-end">{{ $total_targets }}</td>
                                <td class="text-end">{{ $total_potential }}</td>
                                <td class="text-end">{{ $total_achieved }}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br/>
    @endforeach
</div>
<div class="row">
    @foreach ($segments as $segment)
        <div class="col-sm-6 col-md-6">
            <span class="fs-5"><i class="bi bi-layers"></i>&nbsp;{{ $segment->name }}&nbsp;-&nbsp;Targets&nbsp;Vs&nbsp;Potential&nbsp;Vs&nbsp;Achieved&nbsp;[FY&nbsp;{{ $y_start->format('y') }}-{{ $y_start->format('y')+1 }}]</span>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-primary">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center">Quarter</th>
                            <th class="text-end">Target (SCMD)</th>
                            <th class="text-end">Potential (SCMD)</th>
                            <th class="text-end">Achieved (SCMD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $q_tot_target = $q_tot_pot = $q_tot_achieved = 0;
                        @endphp
                        @for ($q = 1; $q <= 4; $q++)
                            @php
                                $q_start = $y_start->copy()->addMonths(($q-1)*3);
                                $q_end = $q_start->copy()->addMonths(2)->startOfMonth();
                                $target_sum = $potential_sum = $achieved_sum = 0;
                                for($q1 = $q_start->copy(); $q1->lte($q_end); $q1->addMonth()){
                                    $target_val = $targets[$segment->id][$q1->format('m')] ?? 0;
                                    $target_sum += $target_val;
                                    // Add Potential
                                    $q_potential = $potential[$segment->id][$q1->format('m')] ?? 0;
                                    $potential_sum += $q_potential;
                                    // Achieved SCMD
                                    $q_achieved = $achieved[$segment->id][$q1->format('m')] ?? 0;
                                    $achieved_sum += $q_achieved;
                                    // Sum of all Quarters
                                    $q_tot_target += $target_val;
                                    $q_tot_pot += $q_potential;
                                    $q_tot_achieved += $q_achieved;
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ "Q".$q }}({{ $q_start->format('M y')." - ".$q_end->format('M y') }})</td>
                                <td class="text-end">{{ $target_sum }}</td>
                                <td class="text-end">
                                    <a href="{{ url('spot/prospects?' . http_build_query(['segments' => [$segment->id]]) . '&expected_date_from='. $q_start->format('d-m-Y') .'&expected_date_to='. $q_end->endOfMonth()->format('d-m-Y')) }}">
                                        {{ $potential_sum }}
                                    </a>
                                </td>
                                <td class="text-end">
                                    <a href="{{ url('spot/prospects?' . http_build_query(['segments' => [$segment->id]]) . '&expected_date_from='. $q_start->format('d-m-Y') .'&expected_date_to='. $q_end->endOfMonth()->format('d-m-Y') . '&' . http_build_query(['sub_stage_id' => [19]])) }}">
                                        {{ $achieved_sum }}
                                    </a>
                                </td>
                            </tr>
                        @endfor
                            <tr class="fw-semibold">
                                <td class="text-end">Totals</td>
                                <td class="text-end">{{ $q_tot_target }}</td>
                                <td class="text-end">{{ $q_tot_pot }}</td>
                                <td class="text-end">{{ $q_tot_achieved }}</td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>