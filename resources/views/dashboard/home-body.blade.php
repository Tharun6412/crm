@php
    $clusters_name = $consumer_total_data = $connection_data = [];
    // Connection Types Graph Data Preparation
    $connection_data[] = [
        'name' => "Prepaid",
        'y' => $total_count > 0 ? round(($total_prepaid/$total_count)*100, 2) : 0,
        'count' => $total_prepaid,
    ];
    $connection_data[] = [
        'name' => "Postpaid",
        'y' => $total_count > 0 ? round(($total_postpaid/$total_count)*100, 2) : 0,
        'count' => $total_postpaid,
    ];

    // Clusters Totals Based on Status
    foreach($clusters as $cluster) {
        $clusters_name[] = $cluster->code;
        if(!isset($consumer_total_data[$cluster->id])) {
            $consumer_total_data[$cluster->id] = [
                'registration' => 0,
                'execution' => 0,
                'activation' => 0,
                'td' => 0,
                'pd' => 0,
            ];
        }
        $consumer_total_data[$cluster->id]['registration'] += $consumer_data[$cluster->id]['registration_count'] ?? 0;
        $consumer_total_data[$cluster->id]['execution'] += $consumer_data[$cluster->id]['execution_count'] ?? 0;
        $consumer_total_data[$cluster->id]['activation'] += $consumer_data[$cluster->id]['activation_count'] ?? 0;
        $consumer_total_data[$cluster->id]['td'] += $consumer_data[$cluster->id]['td_count'] ?? 0;
        $consumer_total_data[$cluster->id]['pd'] += $consumer_data[$cluster->id]['pd_count'] ?? 0;
    }
    // Graphical Data Preparation
    $registration = array_column($consumer_total_data, 'registration');
    $activation = array_column($consumer_total_data, 'activation');
    $execution = array_column($consumer_total_data, 'execution');
    $td = array_column($consumer_total_data, 'td');
    $pd = array_column($consumer_total_data, 'pd');
@endphp
<div class="row g-3">
    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xs-12">
        <div class="card con-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
            <div class="d-flex p-2 align-items-center">
                <div class="mr-4">
                    <div class="con-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                        <i class="bi bi-wifi fs-1"></i>
                    </div>
                </div>
                <div class="py-3 px-3">
                    <span class="text-body-tertiary">Domestic</span>                            
                    <h4>Prepaid Consumers</h4>
                    <h3>{{ numberFormat($consumer_segment[App\Enums\SegmentType::DOMESTIC->value]['prepaid'] ?? 0) }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xs-12">
        <div class="card inv-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
            <div class="d-flex p-2 align-items-center">
                <div class="mr-4">
                    <div class="inv-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                        <i class="bi bi-wifi fs-1"></i>
                    </div>
                </div>
                <div class="py-3 px-3">
                    <span class="text-body-tertiary">Commercial</span>                            
                    <h4>Prepaid Consumers</h4>
                    <h3>{{ numberFormat($consumer_segment[App\Enums\SegmentType::COMMERCIAL->value]['prepaid'] ?? 0) }}</h3>
                </div>                          
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-4 col-xs-12">
        <div class="card cls-card-bg p-1 text-dark bg-opacity-10 border-3 border-light align-items-center">
            <div class="d-flex p-2 align-items-center">
                <div class="mr-4">
                    <div class="inv-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                        <i class="bi bi-speedometer2 fs-1"></i>
                    </div>
                </div>
                <div class="py-3 px-3">
                    <span class="text-body-tertiary">Total</span>                            
                    <h4>Postpaid Consumers</h4>                            
                    <h3>{{ numberFormat($total_postpaid) }}</h3>
                </div>                          
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-3">
    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xs-12">
        <div class="card activated-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
            <div class="d-flex justify-content-between p-3">
                <div>
                    <h2 class="mb-2">{{ numberFormat(array_sum(array_column($consumer_data, 'activation_count'))) }}</h2>
                    <span class="text-body-tertiary">Activated<br/> Consumers</span>
                </div>
                <div>
                    <div class="activated-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                        <i class="bi bi-person-check fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                <a href="{{ url('consumers/activated') }}?{{ http_build_query(['geo_area' => request()->geo_area, 'cluster' => request()->cluster]) }}">View Consumers</a>
                <a href="{{ url('consumers/activated') }}?{{ http_build_query(['geo_area' => request()->geo_area, 'cluster' => request()->cluster]) }}" target="_blank"><i class="bi bi-box-arrow-up-right fs-5"></i></a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xs-12">
        <div class="card total-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
            <div class="d-flex justify-content-between p-3">
                <div>
                    <h2 class="mb-2">{{ numberFormat($total_count) }}</h2>
                    <span class="text-body-tertiary">Total<br> Registrations</span>
                </div>
                <div>
                    <div class="total-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                        <i class="bi bi-person-lines-fill fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => request()->geo_area, 'cluster' => request()->cluster]) }}">View All Registrations</a>
                <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area' => request()->geo_area, 'cluster' => request()->cluster]) }}" target="_blank"><is class="bi bi-box-arrow-up-right fs-5"></i></a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xs-12">
        <div class="card py-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
            <div class="d-flex justify-content-between p-3">
                <div>
                    <h2 class="mb-2">{{ numberFormat(array_sum(array_column($consumer_data, 'td_count'))) }}</h2>
                    <span class="text-body-tertiary">Temporary<br/> Disconnections</span>
                </div>
                <div>
                    <div class="py-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                        <i class="bi bi-person-dash fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                <a href="{{ url('consumers/td') }}?{{ http_build_query(['geo_area' => request()->geo_area, 'cluster' => request()->cluster]) }}">View Details</a>
                <a href="{{ url('consumers/td') }}?{{ http_build_query(['geo_area' => request()->geo_area, 'cluster' => request()->cluster]) }}" target="_blank"><is class="bi bi-box-arrow-up-right fs-5"></i></a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3 col-xs-12">
        <div class="card disconnect-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
            <div class="d-flex justify-content-between p-3">
                <div>
                    <h2 class="mb-2">{{ numberFormat(array_sum(array_column($consumer_data, 'pd_count'))) }}</h2>
                    <span class="text-body-tertiary">Permanent<br/> Disconnections</span>
                </div>
                <div>
                    <div class="disconnect-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                        <i class="bi bi-person-x fs-2"></i>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                <a href="{{ url('consumers/pd') }}?{{ http_build_query(['geo_area' => request()->geo_area, 'cluster' => request()->cluster]) }}">View Details</a>
                <a href="{{ url('consumers/pd') }}?{{ http_build_query(['geo_area' => request()->geo_area, 'cluster' => request()->cluster]) }}" target="_blank"><is class="bi bi-box-arrow-up-right fs-5"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="row g-3 mt-3">
    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
        <div class="card py-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
            <figure class="highcharts-figure">
                {{-- <div id="lineChart"></div> --}}
                <div id="postpaid-vs-prepaid"></div>
            </figure>
        </div>
    </div>
    <div class="col-sm-12 col-md-8 col-lg-8 col-xl-8">
        <div class="overflow-y-auto shadow">
            <div class="card total-card-bg p-2 text-dark bg-opacity-10 border-3 border-light align-items-center">
                <figure class="highcharts-figure">
                    <div id="cluster-consumers"></div>
                </figure>
            </div>
        </div>
    </div>
</div>
{{-- Scripts --}}
<script type="module">
    $(function() {
        // Postpaid Vs Prepaid
        Highcharts.chart('postpaid-vs-prepaid', {
            chart: {
                type: 'pie',
                zooming: {
                    type: 'xy'
                },
                panning: {
                    enabled: true,
                    type: 'xy'
                },
                panKey: 'shift',
                backgroundColor: 'transparent'
            },
            title: {
                text: 'Postpaid Vs Prepaid',
                style: {
                    color: '#333333', // Hex color for red
                 }
            },
            tooltip: {
                pointFormat:'<b>{point.count}</b>'
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
                        }
                    }]
                }
            },
            series: [
                {
                    name: 'Count',
                    colorByPoint: true,
                    data: @json($connection_data),
                }
            ],
            credits: {enabled: false}
        });
        // Clusters Vs Consumers
        Highcharts.chart('cluster-consumers', {
            chart: {
                type: 'column',
                backgroundColor: 'transparent',
            },
            title: {
                text: 'Consumer Status Clusters',
            },
            xAxis: {
                categories: @json($clusters_name),
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                },
                stackLabels: {
                    enabled: true
                }
            },
            legend: {
                verticalAlign: 'top',
                // align: 'center',
                // floating: false,
                // borderWidth: 0,
                // shadow: false
            },
            tooltip: {
                headerFormat: '<b>{category}</b><br/>',
                pointFormat: '{series.name}: {point.y}'
            },
            plotOptions: {
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                name: 'Registered',
                data: @json($registration)
            }, {
                name: 'Executed',
                data: @json($execution)
            }, {
                name: 'Activated',
                data: @json($activation)
            }, {
                name: 'TD',
                data: @json($td)
            }, {
                name: 'PD',
                data: @json($pd)
            }],
            credits: [{enabled: false}]
        });

        //Line chart
        /*
        Highcharts.chart('lineChart', {
            chart: {
                type: 'line',
                backgroundColor: 'transparent'
            },
            title: {
                text: 'Monthly Average Registrations'
            },
            xAxis: {
                categories: [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ]
            },
            yAxis: {
                title: {
                    text: 'Number of Registrations'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: [{
                name: 'Industrial',
                data: [
                    16.0, 18.2, 23.1, 27.9, 32.2, 36.4, 39.8, 38.4, 35.5, 29.2, 22.0, 17.8
                ]
            }, {
                name: 'Commercial',
                data: [
                    -2.9, -3.6, -0.6, 4.8, 10.2, 14.5, 17.6, 16.5, 12.0, 6.5, 2.0, -0.9
                ]
            }],
            credits: [{enabled: false}]
        });
        */
        // Bar chart
        /*
        Highcharts.chart('barChart', {
            chart: {
                type: 'column',
                backgroundColor: 'transparent'
            },
            title: {
                text: 'Cluster wise Consumers'
            },
            subtitle: {
                text: ''
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category',
                categories: ['Registered', 'Executed', 'Activated', 'TD', 'PD'],
            },
            yAxis: {
                title: {
                    text: ''
                },
                 stackLabels: {
                    enabled: true
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                // series: {
                //     borderWidth: 0,
                //     dataLabels: {
                //         enabled: true,
                //         format: '{point.y}'
                //     }
                // }
                column: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="color:{point.color}">{point.name}</span><br>',
                pointFormat: '<span>{series.name}</span>: ' + '<b>{point.y}</b>'
            },
            series: [
                {
                    name: 'Consumers',
                    colorByPoint: true,
                    data: [
                        {
                            name: 'AP & TS',
                            // y: 45000,
                            data: [1, 2, 3, 4, 5]
                        },
                        // {
                        //     name: 'Karnataka',
                        //     y: 35084,
                        // },
                        // {
                        //     name: 'Tamilnadu',
                        //     y: 2418,
                        // },
                        // {
                        //     name: 'Central',
                        //     y: 1200,
                        // },
                        // {
                        //     name: 'North',
                        //     y: 2233,
                        // },
                    ]
                }
            ],
            credits: [{enabled: false}]
        });
        */
    })
</script>