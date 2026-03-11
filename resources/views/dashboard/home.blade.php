@extends('layouts.layout', ['dashboard' => 1])

@section('page-title', 'Welcome')

@section('page-content')
    @php
        $connection_data = [];
        // Connection Types Graph Data Preparation
        $connection_data[] = [
            'name' => "Prepaid",
            'y' => $consumer_count->total_count > 0 ? round(($consumer_count->prepaid_count/$consumer_count->total_count)*100, 2) : 0,
            'count' => $consumer_count->prepaid_count,
        ];
        $connection_data[] = [
            'name' => "Postpaid",
            'y' => $consumer_count->total_count > 0 ? round(($consumer_count->postpaid_count/$consumer_count->total_count)*100, 2) : 0,
            'count' => $consumer_count->postpaid_count,
        ];
    @endphp
    <div class="pb-5">
        {{-- Quick search --}}
        <div class="rounded p-4 mb-3">
            <div class="row justify-content-sm-center">
                <div class="col-sm-6">
                    <div class="bg-light-subtle p-3 rounded-3 border border-secondary-subtle shadow-sm">
                    <h3 class="text-start">Consumer Search</h3>
                    <div class="position-relative">
                        {{-- Quick search input --}}
                        <div class="input-group input-group-lg">
                            <label for="quickSearch" class="input-group-text bg-white"><i class="bi bi-search"></i></label>
                            <input type="text" id="quickSearch" class="form-control no-focus-ring border-start-0 border-end-0" placeholder="CRN, Name, Mobile...">
                            <label for="quickSearch" class="input-group-text bg-white cursor-pointer"><i id="qs-clr" class="bi bi-x-circle d-none"></i></label>
                        </div>
                        {{-- Result --}}
                        <div id="searchResults" class="list-group position-absolute w-100" style="z-index: 1000; display:none;">
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row g-3">
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-4">
                    <div class="card con-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex p-3 align-items-center">
                            <div class="mr-4">
                                <div class="con-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-wifi fs-1"></i>
                                </div>
                            </div>
                            <div class="py-3 px-3">
                                <h4>Prepaid Consumers</h4>                            
                                <h3>{{ numberFormat($consumer_count->prepaid_count) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-4">
                    <div class="card inv-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex p-3 align-items-center">
                            <div class="mr-4">
                                <div class="inv-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-speedometer2 fs-1"></i>
                                </div>
                            </div>
                            <div class="py-3 px-3">
                                <h4>Postpaid Consumers</h4>                            
                                <h3>{{ numberFormat($consumer_count->postpaid_count) }}</h3>
                            </div>                          
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-4">
                    <div class="card cls-card-bg p-1 text-dark bg-opacity-10 border-3 border-light align-items-center">
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                    <div class="card activated-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-3">
                            <div>
                                <h2 class="mb-2">{{ numberFormat($consumer_count->activation_count) }}</h2>
                                <span class="text-body-tertiary">Activated<br/> Consumers</span>
                            </div>
                            <div>
                                <div class="activated-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-person-check fs-2"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                            <a href="{{ url('consumers/activated') }}">View Consumers</a>
                            <a href="{{ url('consumers/activated') }}" target="_blank"><is class="bi bi-box-arrow-up-right fs-5"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                    <div class="card total-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-3">
                            <div>
                                <h2 class="mb-2">{{ numberFormat($consumer_count->total_count) }}</h2>
                                <span class="text-body-tertiary">Total<br> Registrations</span>
                            </div>
                            <div>
                                <div class="total-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-person-lines-fill fs-2"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                            <a href="{{ url('consumers') }}">View All Registrations</a>
                            <a href="{{ url('consumers') }}" target="_blank"><is class="bi bi-box-arrow-up-right fs-5"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                    <div class="card py-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-3">
                            <div>
                                <h2 class="mb-2">{{ numberFormat($consumer_count->td_count) }}</h2>
                                <span class="text-body-tertiary">Temporary<br/> Disconnections</span>
                            </div>
                            <div>
                                <div class="py-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-person-dash fs-2"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                            <a href="{{ url('consumers/td') }}">View Details</a>
                            <a href="{{ url('consumers/td') }}" target="_blank"><is class="bi bi-box-arrow-up-right fs-5"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                    <div class="card disconnect-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-3">
                            <div>
                                <h2 class="mb-2">{{ numberFormat($consumer_count->pd_count) }}</h2>
                                <span class="text-body-tertiary">Permanent<br/> Disconnections</span>
                            </div>
                            <div>
                                <div class="disconnect-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-person-x fs-2"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                            <a href="{{ url('consumers/pd') }}">View Details</a>
                            <a href="{{ url('consumers/pd') }}" target="_blank"><is class="bi bi-box-arrow-up-right fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="card py-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <figure class="highcharts-figure">
                            <div id="lineChart"></div>
                        </figure>
                    </div>
                </div>
                <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="card total-card-bg p-2 text-dark bg-opacity-10 border-3 border-light align-items-center">
                        <figure class="highcharts-figure">
                            <div id="barChart"></div>
                        </figure>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-decoration-underline text-primary-emphasis">Quick Links</h3>
            </div>
            <div class="row mt-3">
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-2 align-items-center">
                            <div>
                                <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                    <i class="bi bi-link-45deg fs-3"></i>
                                </div>
                            </div>
                            <a href="{{ url('consumers') }}" class="py-2 px-2 text-start">
                                <small class="text-body">All Consumers</small>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('consumers/register/domestic') }}">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Domestic Registration</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('consumers/tr') }}">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">TR Consumers</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('consumers/registered') }}">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Registered Consumers</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('consumers/meterChange') }}">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Meter change</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('bill/consumer/search') }}" target="_blank">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Search Gas Bills</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('consumers/rejected') }}">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Rejected Consumers</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('consumers/meterChange') }}">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Meter Change</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('bill/consumer/search') }}" target="_blank">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Invoice Generation</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('bill/invoice/search') }}" target="_blank">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Creditnote Generation</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <a href="{{ url('reports/consumer/sdReport') }}">
                        <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex justify-content-between p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi bi-link-45deg fs-3"></i>
                                    </div>
                                </div>
                                <div class="py-2 px-2 text-start">
                                    <small class="text-body">Security Depost Report</small>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-4 col-md-4 col-lg-2 col-xl-2 col-xs-6">
                    <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-2 align-items-center">
                            <div>
                                <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                    <i class="bi bi-link-45deg fs-3"></i>
                                </div>
                            </div>
                            <div class="py-2 px-2 text-start">
                                <small class="text-body">Customer Service</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.quick-search')    
    <script type="text/javascript" src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/highcharts/funnel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/highcharts/accessibility.js') }}"></script>
    <script>
        // Pie chart
        Highcharts.chart('container', {
            chart: {
                type: 'pie',
                width:300,  // fixed width in pixels
                height: 112,  // fixed height in pixels
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
                text: '',
                style: {
                    color: '#333333', // Hex color for red
                    fontSize: '14px',
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
                        distance: -15,
                        format: '{point.percentage:.1f}%',
                        style: {
                            fontSize: '9px',
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
        //Line chart
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
        // Bar chart
        Highcharts.chart('barChart', {
            chart: {
                type: 'column',
                backgroundColor: 'transparent'

            },
            title: {
                text: 'Consumer Registrations'
            },
            subtitle: {
                text: 'Region-wise distribution'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Total Registrations'
                }

            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}%'
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}%</b> of total<br/>'
            },
            series: [
                {
                    name: 'GA Names',
                    colorByPoint: true,
                    data: [
                        {
                            name: 'Krishna',
                            y: 63.06,
                            drilldown: 'Krishna'
                        },
                        {
                            name: 'Nalgonda',
                            y: 39.84,
                            drilldown: 'Nalgonda'
                        },
                        {
                            name: 'Warangal',
                            y: 24.18,
                            drilldown: 'Warangal'
                        },
                        {
                            name: 'Rangareddy',
                            y: 44.12,
                            drilldown: 'Rangareddy'
                        },
                        {
                            name: 'Khammam',
                            y: 22.33,
                            drilldown: 'Khammam'
                        },
                        {
                            name: 'Belgaum',
                            y: 15.45,
                            drilldown: 'Belgaum'
                        },
                        {
                            name: 'Tumkur',
                            y: 28.45,
                            drilldown: 'Tumkur'
                        },
                        {
                            name: 'Rayagada',
                            y: 16.45,
                            drilldown: 'Rayagada'
                        },
                        {
                            name: 'Chandrapur',
                            y: 5.45,
                            drilldown: 'Chandrapur'
                        },
                        {
                            name: 'Other',
                            y: 1.582,
                            drilldown: null
                        }
                    ]
                }
            ],
            drilldown: {
                breadcrumbs: {
                    position: {
                        align: 'right'
                    }
                },
                series: [
                    {
                        name: 'Chrome',
                        id: 'Chrome',
                        data: [
                            [
                                'v65.0',
                                0.1
                            ],
                            [
                                'v64.0',
                                1.3
                            ],
                            [
                                'v63.0',
                                53.02
                            ],
                            [
                                'v62.0',
                                1.4
                            ],
                            [
                                'v61.0',
                                0.88
                            ],
                            [
                                'v60.0',
                                0.56
                            ],
                            [
                                'v59.0',
                                0.45
                            ],
                            [
                                'v58.0',
                                0.49
                            ],
                            [
                                'v57.0',
                                0.32
                            ],
                            [
                                'v56.0',
                                0.29
                            ],
                            [
                                'v55.0',
                                0.79
                            ],
                            [
                                'v54.0',
                                0.18
                            ],
                            [
                                'v51.0',
                                0.13
                            ],
                            [
                                'v49.0',
                                2.16
                            ],
                            [
                                'v48.0',
                                0.13
                            ],
                            [
                                'v47.0',
                                0.11
                            ],
                            [
                                'v43.0',
                                0.17
                            ],
                            [
                                'v29.0',
                                0.26
                            ]
                        ]
                    },
                    {
                        name: 'Firefox',
                        id: 'Firefox',
                        data: [
                            [
                                'v58.0',
                                1.02
                            ],
                            [
                                'v57.0',
                                7.36
                            ],
                            [
                                'v56.0',
                                0.35
                            ],
                            [
                                'v55.0',
                                0.11
                            ],
                            [
                                'v54.0',
                                0.1
                            ],
                            [
                                'v52.0',
                                0.95
                            ],
                            [
                                'v51.0',
                                0.15
                            ],
                            [
                                'v50.0',
                                0.1
                            ],
                            [
                                'v48.0',
                                0.31
                            ],
                            [
                                'v47.0',
                                0.12
                            ]
                        ]
                    },
                    {
                        name: 'Internet Explorer',
                        id: 'Internet Explorer',
                        data: [
                            [
                                'v11.0',
                                6.2
                            ],
                            [
                                'v10.0',
                                0.29
                            ],
                            [
                                'v9.0',
                                0.27
                            ],
                            [
                                'v8.0',
                                0.47
                            ]
                        ]
                    },
                    {
                        name: 'Safari',
                        id: 'Safari',
                        data: [
                            [
                                'v11.0',
                                3.39
                            ],
                            [
                                'v10.1',
                                0.96
                            ],
                            [
                                'v10.0',
                                0.36
                            ],
                            [
                                'v9.1',
                                0.54
                            ],
                            [
                                'v9.0',
                                0.13
                            ],
                            [
                                'v5.1',
                                0.2
                            ]
                        ]
                    },
                    {
                        name: 'Edge',
                        id: 'Edge',
                        data: [
                            [
                                'v16',
                                2.6
                            ],
                            [
                                'v15',
                                0.92
                            ],
                            [
                                'v14',
                                0.4
                            ],
                            [
                                'v13',
                                0.1
                            ]
                        ]
                    },
                    {
                        name: 'Opera',
                        id: 'Opera',
                        data: [
                            [
                                'v50.0',
                                0.96
                            ],
                            [
                                'v49.0',
                                0.82
                            ],
                            [
                                'v12.1',
                                0.14
                            ]
                        ]
                    }
                ]
            },
            credits: [{enabled: false}]
        });
</script>
@endpush
<style>
    .boxshadow {
        box-shadow: #dadada 3px 4px 10px 1px;
    }
    .con-card-bg {
        background: #c9aeee;
        background: linear-gradient(180deg, rgb(62 162 236 / 31%) 0%, rgb(233 141 178 / 30%) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;
        text-transform: uppercase;
    }
    .inv-card-bg {
        background: #f1e9ad;
        background: linear-gradient(180deg, rgb(232 235 187 / 51%) 0%, #5dc3d25c 100%);
        box-shadow: #dadada 3px 4px 10px 1px;
        text-transform: uppercase;
    }
    .py-card-bg {
        background: #c7542a;
        background: linear-gradient(181deg, rgba(199, 84, 42, 0.14) 32%, rgba(126, 229, 247, 0.45) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;
        text-transform: uppercase;
    }
    .cls-card-bg {
        background: #EEAECA;
        background: linear-gradient(180deg, rgb(238 174 223 / 43%) 0%, rgb(246 244 73 / 25%) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;
        text-transform: uppercase;
    }
    .activated-card-bg {
        background: #dcffab;
        background: linear-gradient(180deg, rgba(220, 255, 171, 0.3) 0%, rgba(149, 194, 240, 0.59) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;
        text-transform: uppercase;
    }
    .total-card-bg {
        background: #abf9ff;
        background: linear-gradient(180deg, rgba(171, 249, 255, 0.24) 0%, rgba(197, 157, 237, 0.59) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;
        text-transform: uppercase;
    }
    .disconnect-card-bg {
    background: #eb6d57;
        background: linear-gradient(180deg, rgba(235, 109, 87, 0.24) 0%, rgba(255, 255, 255, 0.59) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;
        text-transform: uppercase;
    }
    .link-card-bg {
        background: #4253eb;
        background: linear-gradient(180deg, rgba(66, 83, 235, 0.2) 0%, rgba(160, 190, 250, 0.3) 100%);
        box-shadow: #cccccc 3px 4px 10px 1px;
        text-transform: uppercase;
    }
    #searchResults .list-group-item:hover {
        background-color: hsl(210, 10%, 88%)!important;
    }
</style>