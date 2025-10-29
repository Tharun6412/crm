@php
	// print "<pre>"; print_r($targets);
@endphp
<div class="row">
	@foreach ($segments as $segment)
		<div class="col-sm-6 col-md-6">
			<h3>{{ $segment->name }}</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Month</th>
							<th class="text-end">Target (SCMD)</th>
							<th class="text-end">Potential (SCMD)</th>
							<th class="text-end">Achieved (SCMD)</th>
						</tr>
					</thead>
					<tbody>
						@for ($date = $y_start->copy(); $date->lte($y_end); $date->addMonth())
							<tr>
								<td class="text-center">{{ $date->format('M-y') }}</td>
								<td class="text-end">{{ $targets[$segment->id][$date->format('Y-m-01')] ?? '' }}</td>
								<td class="text-end"></td>
								<td class="text-end"></td>
							</tr>
						@endfor
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
			<h3>{{ $segment->name }}</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Quarter</th>
							<th class="text-end">Target (SCMD)</th>
							<th class="text-end">Potential (SCMD)</th>
							<th class="text-end">Achieved (SCMD)</th>
						</tr>
					</thead>
					<tbody>
						@for ($q = 1; $q <= 4; $q++)
							@php
								$q_start = $y_start->copy()->addMonths(($q-1)*3);
								$q_end = $q_start->copy()->addMonths(2)->startOfMonth();
								$target_sum = 0;
								for($q1 = $q_start->copy(); $q1->lte($q_end); $q1->addMonth()){
									$target_val = $targets[$segment->id][$q1->format('Y-m-d')] ?? 0;
									$target_sum += $target_val;
								}
							@endphp
							<tr>
								<td class="text-center">{{ $q }}</td>
								<td class="text-end">{{ $target_sum }}</td>
								<td class="text-end"></td>
								<td class="text-end"></td>
							</tr>
						@endfor
					</tbody>
				</table>
			</div>
		</div>
	@endforeach
</div>