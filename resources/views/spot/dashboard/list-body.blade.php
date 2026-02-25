@php
	$geo_ids = request()->geo_area;
	if ($geo_ids) {
		// render your component output as string
		$ga_title = ''; //(new \App\View\Components\Admin\GaName($geo_ids))->render()->render();
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
@endphp
@if (request()->geo_area)
	<h3>&nbsp;{{ $ga_title }}</h3>
@endif
<div class="row p-2 g-2">
	<div class="col-md-4">
		<div class="card">
			<div class="card-header fs-5 fw-semibold">Prospect Analysis : Fuels</div>
			<div class="card-body p-0">
				<div id="container1" class="rounded"></div>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card">
			<div class="card-header fs-5 fw-semibold">Prospect Analysis : Potential</div>
			<div class="card-body p-0">
				<div id="container2" class="rounded"></div>
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
										<div class="pt-2 fs-4">{{ $prospect_list[$list->name] ?? 0 }}</div>
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
									<div class="pt-2 fs-4">{{ $prospect_sum }}</div>
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
<script type="module">
	$(function(){
		Highcharts.chart('container1', {
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
					}, {
						enabled: true,
						distance: -40,
						format: '{point.percentage:.1f}%',
						style: {
							fontSize: '1.2em',
							textOutline: 'none',
							opacity: 0.7
						},
						filter: {
							operator: '>',
							property: 'percentage',
							value: 10
						}
					}]
				}
			},
			series: [
				{
					name: 'Potential',
					colorByPoint: true,
					data: [
						{
							name: 'Solid',
							y: 6000
						},
						{
							name: 'Liquid',
							sliced: true,
							selected: true,
							y: 2000
						},
						{
							name: 'Gaseous',
							y: 1000
						},
					]
				}
			],
			credits: [{enabled: false}]
		});

		Highcharts.chart('container2', {
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
	});
</script>