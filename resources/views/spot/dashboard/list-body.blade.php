@php
	// Fuel group counts
	$fuel_group_names = [
		1 => 'Gaseous',
		2 => 'Liquid',
		3 => 'Solid',
	];
	$prospect_list = [];
	$prospect_sum = [];
	// Get Status Count List
	foreach ($prospect_data as $status_id => $status_val) {
		$prospect_list[$status_val->segment_id][$status_val->parent_id] = $status_val->status_count;
		$prospect_sum[$status_val->segment_id] = ($prospect_sum[$status_val->segment_id] ?? 0) + $status_val->status_count;
	}
@endphp
@foreach ($segments as $segment)
	{{-- Fuel Data Preparation for Chart/Graph --}}
	@php
		$fuel_chart[$segment->id] = [];
		$fuel_chart_group[$segment->id] = [];
		// Fuel Types
		foreach($fuel_types as $type) {
			// Group Chart
			$fuel_chart[$segment->id][] = [$type->name, $fuel_data[$segment->id][$type->id] ?? 0];
		}
		// Fuel Groups
		foreach($fuel_group_names as $key => $name) {
			$fuel_chart_group[$segment->id][] = [$name, $group_potential[$segment->id][$key] ?? 0];
		}
	@endphp
	<div class="row p-2 g-2">
		<div class="col-md-4">
			<div class="card">
				<div class="card-header fs-5 fw-semibold">Prospect Analysis : Fuels</div>
				<div class="card-body p-0" id="fuel_graph_{{ $segment->id }}">
					<figure class="highcharts-figure mb-0">
						<div id="fuel_chart_{{ $segment->id }}" class="rounded"></div>
					</figure>
					<div class="text-center fw-semibold">
						Total : {{ $total_potential[$segment->id] ?? 0 }}
						<a href="javascript:void(0)" onclick="toggleFuelGraph(1, {{ $segment->id }})" class="fs-5 float-end" title="More details"><i class="bi bi-chevron-double-right"></i></a>
					</div>
				</div>
				<div class="card-body p-0 d-none" id="fuel_group_graph_{{ $segment->id }}">
					<figure class="highcharts-figure mb-0">
						<div id="fuel_group_chart_{{ $segment->id }}" class="rounded"></div>
					</figure>
					<div class="text-center fw-semibold">
						Total : {{ $total_potential[$segment->id] ?? 0 }}
						<a href="javascript:void(0)" onclick="toggleFuelGraph(0, {{ $segment->id }})" class="fs-5 float-end" title="Over view"><i class="bi bi-chevron-double-left"></i></a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-header fs-5 fw-semibold">Prospect Analysis : Potential</div>
				<div class="card-body p-0">
					<figure class="highcharts-figure mb-0">
						<div id="container_{{ $segment->id }}_funnel" class="rounded"></div>
					</figure>
					<div class="text-center fw-semibold">Total : 0</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card">
				<div class="card-header fs-5 fw-semibold">Prospect Status Counts</div>
				<div class="card-body p-2">
					<div class="row g-2">
						@foreach ($status_list as $list)
							<div class="col-6">
								<a href="{{ url('spot/prospects') }}?{{ http_build_query(['stage_id'=> [$list->id]]) }}" target="_blank">
									<div class="d-flex align-items-center bg-{{ $list->color ?? 'success' }}-subtle rounded">
										<div class="flex-fill w-50 fs-2 text-center">
											<i class="bi bi-{{ $list->icon ?? 'flash' }} text-{{ $list->color ?? 'dark' }}"></i>
										</div>
										<div class="flex-fill w-50 text-center">
											<div class="pt-2 fs-4">{{ $prospect_list[$segment->id][$list->id] ?? 0 }}</div>
											<div class="pb-2">{{ $list->name }}</div>
										</div>
									</div>
								</a>
							</div>
						@endforeach
						<div class="col">
							<a href="{{ url('spot/prospects') }}" target="_blank">
								<div class="d-flex align-items-center bg-primary-subtle rounded">
									<div class="flex-fill w-50 fs-2 text-center">
										<i class="bi bi-people"></i>
									</div>
									<div class="flex-fill w-50 text-center">
										<div class="pt-2 fs-4">{{ $prospect_sum[$segment->id] ?? 0 }}</div>
										<div class="pb-2">Total</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endforeach
<div>
    @include('spot.dashboard.targets')
</div>
<script type="text/javascript">
	// toggle fucntion
	function toggleFuelGraph(d, i) {
		if(d == 1) {
			$('#fuel_graph_' + i).addClass('d-none');
			$('#fuel_group_graph_' + i).removeClass('d-none');
		}
		else {
			$('#fuel_graph_' + i).removeClass('d-none');
			$('#fuel_group_graph_' + i).addClass('d-none');
		}
	}
</script>
<script type="module">
	$(function(){
		@foreach ($segments as $segment)	
			Highcharts.chart('fuel_chart_{{ $segment->id }}', {
				chart: {
					type: 'pie',
					zooming: {
						type: 'xy'
					},
					panning: {
						enabled: true,
						type: 'xy'
					},
					panKey: 'shift'
				},
				title: {
					text: ''
				},
				tooltip: {
					valueSuffix: ' SCMD'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: [{
							enabled: true,
							distance: 20
						},
					]}
				},
				series: [
					{
						name: 'Potential',
						showInLegend:true,
						colorByPoint: true,
						data: @json($fuel_chart_group[$segment->id] ?? 0),
					}
				],
				credits: [{enabled: false}]
			});
			// Group chart
			Highcharts.chart("fuel_group_chart_{{ $segment->id }}", {
				chart: {
					type: "pie",
					options3d: {
						enabled: true,
						alpha: 45
					}
				},
				title: {
					text: ''
				},
				subtitle: {
					text: "",
					align: "left"
				},
				plotOptions: {
					pie: {
						innerSize: 90,
						depth: 45
					}
				},
				series: [
					{
						showInLegend:true,
						name: "SCMD",
						data: @json($fuel_chart[$segment->id] ?? []),
					}
				],
				credits: {
					enabled: false
				},
			});
			Highcharts.chart('container_{{ $segment->id }}_funnel', {
				chart: {
					type: 'funnel'
				},
				title: {
					text: ''
				},
				colors: ['#1874fd','#ffc927',' #e04d5b','#28d0f1','#349568','#80888e'],
				plotOptions: {
					series: {
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b> ({point.y:,.0f})',
							softConnector: true
						},
						center: ['40%', '50%'],
						neckWidth: '30%',
						neckHeight: '25%',
						width: '80%'
					}
				},
				legend: {
					enabled: false
				},
				series: [{
					name: 'Unique users',
					data: [
						['S', 1560],
						['P', 2064],
						['A', 1987],
						['N', 976],
						['C', 846],
						['O', 846],
					]
				}],
				credits: [{enabled: false}],

				responsive: {
					rules: [{
						condition: {
							maxWidth: 500
						},
						chartOptions: {
							plotOptions: {
								series: {
									dataLabels: {
										inside: true
									},
									center: ['50%', '50%'],
									width: '100%'
								}
							}
						}
					}]
				}
			});
		@endforeach
	});
</script>