@php
	$geo_ids = request()->geo_area;
	if ($geo_ids) {
		// render your component output as string
		$ga_title = (new \App\View\Components\Admin\GaName($geo_ids))->render()->render();
	} else {
		$ga_title = '';
	}
	$prospect_list = [];
	$prospect_sum = 0;
	// Get Status Count List
	foreach ($prospect_data as $status_id => $status_val) {
		$prospect_list[$status_val->stage->parent->name] = $status_val->status_count;
		$prospect_sum += $status_val->status_count;
	}
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
@if (request()->geo_area)
	<h3>&nbsp;{{ $ga_title }}</h3>
@endif
<div class="row">
	<div class="col-md-10"></div>
	<div class="col-md-2">
		<h3>Prospects</h3>
		<div class="row">
			@foreach ($status_list as $list)
				<div class="col-6">
					<div class="card text-center">
						<div class="card-body">
							<i class="bi bi-search"></i>
							<h5 style="white-space: nowrap;">{{ $list->name }}</h5>
							<p class="fw-bold">{{ $prospect_list[$list->name] ?? 0 }}</p>
						</div>
					</div>
					<br/>
				</div>
			@endforeach
			<div class="col">
				<div class="card">
					<div class="card-body bg-info">
						<h5 style="white-space: nowrap;">Totals</h5>
						<p class="fw-bold">{{ $prospect_sum }}</p>
					</div>
				</div>
				<br/>
			</div>
		</div>
	</div>
</div>
<br/>
<div class="row">
	@foreach ($segments as $segment)
		<div class="col-sm-6 col-md-6">
			<span class="fs-4">{{ $segment->name }}&nbsp;-&nbsp;Targets&nbsp;Vs&nbsp;Potential&nbsp;Vs&nbsp;Achieved&nbsp;[FY&nbsp;{{ $y_start->format('y') }}-{{ $y_start->format('y')+1 }}]</span>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover table-success">
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
								<td class="text-end">{{ $potential[$segment->id][$date->format('m')] ?? '' }}</td>
								<td class="text-end">{{ $achieved[$segment->id][$date->format('m')] ?? '' }}</td>
							</tr>
						@endfor
							<tr>
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
			<span class="fs-4">{{ $segment->name }}&nbsp;-&nbsp;Targets&nbsp;Vs&nbsp;Potential&nbsp;Vs&nbsp;Achieved&nbsp;[FY&nbsp;{{ $y_start->format('y') }}-{{ $y_start->format('y')+1 }}]</span>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped table-primary">
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
								<td class="text-end">{{ $potential_sum }}</td>
								<td class="text-end">{{ $achieved_sum }}</td>
							</tr>
						@endfor
							<tr>
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