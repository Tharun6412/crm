@php
    // Data Preparation
    $stage_counts = [];
    if($prospects_data) {
	foreach($prospects_data as $prospect_counts) {
		$stage_counts[$prospect_counts->segment_id][$prospect_counts->stage] = $prospect_counts->stage_count;
	}
}
@endphp
<div class="position-relative">
	<div class="" style="padding: 0.5rem;">
		<div class="clearfix">
			<div class="float-start">
				<div class="page-header">
				  	<h3 class="page-title">
				        <span class="page-title-icon bg-gradient-primary text-white me-2">
				          	<i class="bi bi-people"></i>
				        </span> Prospects
				  	</h3>
				</div>
			</div>
			<div class="float-end">
			</div>
		</div>
        @for ($i = 2; $i >= 1; $i--)
            @php
                if($i == 2) {
                    $segment_val = "Industrial";
                }else {
                    $segment_val = "Commercial";
                }
            @endphp
            <div class="fs-5 fw-semibold"><i class="bi bi-chevron-double-right"></i>{{ $segment_val }}&nbsp; Data</div>
			<div class="spot-db-row-space">
				<div class="row">
					<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
						<div class="spot-db-card">
							<div class="spot-db-card-header">
								<div class="spot-db-card-header-title">Prospect Analysis : Fuels</div>
							</div>
							<div class="spot-db-card-body" id="fuel_group_graph_<? echo $i ?>">
								<figure class="highcharts-figure mb-0">
								  	<div id="fuelGroupChart_<? echo $i;?>"></div>
								</figure>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
						<div class="row">
                            @php
                                // Title preparation
                                $stages = [
                                    1 => ['name' => 'Suspect', 'icon' => 'bi-search', 'class' => 'bg-primary bg-gradient'],
                                    2 => ['name' => 'Prospect', 'icon' => 'bi-lightbulb', 'class' => 'bg-warning bg-gradient'],
                                    3 => ['name' => 'Approach', 'icon' => 'bi-bullseye', 'class' => 'bg-danger bg-gradient'],
                                    4 => ['name' => 'Negotiation', 'icon' => 'bi-coin', 'class' => 'bg-info bg-gradient'],
                                    5 => ['name' => 'Closure', 'icon' => 'bi-box-seam', 'class' => 'bg-success bg-gradient'],
                                    6 => ['name' => 'Order', 'icon' => 'bi-rocket-takeoff', 'class' => 'bg-secondary bg-gradient'],
                                ];
                            @endphp
                            @foreach ($stages as $id => $info)
                               <div class="col-md-6 col-sm-6 col-xs-12">
									<a href="#" class="spot-db-card">
										<div class="d-flex align-items-center overflow-hidden">
											<div class="spot-db-icon <? echo $info['class']; ?>">
												<i class="bi <? echo $info['icon']; ?>"></i>
											</div>
											<div class="spot-db-details w-100">
												<div class="spot-db-count">
                                                    {{ isset($stage_counts[$i][$id]) ? numberFormat($stage_counts[$i][$id]) : 0 }}
												</div>
												<div class="spot-db-name">
													{{  $info['name'] }}
												</div>
											</div>
										</div>
									</a>
								</div> 
                            @endforeach
						</div>
					</div>
				</div>
			</div>
        @endfor
	</div>
</div>