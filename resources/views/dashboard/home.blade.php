@extends('layouts.layout', ['dashboard' => 1])

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('page-content')
    <div class="pb-5">
        {{-- Quick search --}}
        <div class="rounded p-4 mb-3">
            <div class="row justify-content-sm-center">
                <div class="col-sm-7">
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
                @if ($roles->count() > 0)
                    <div class="col-sm-1">
                        <div class="card" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">Consumers pending action at your stage.</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                @foreach ($roles as $id => $role)
                                    @switch($id)
                                        @case(\App\Enums\Role::MARKETING->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0;
                                                $status_val = "REGISTRATION";
                                                $status_id = \App\Enums\ConsumerStatus::PRE_REGISTER->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::MDPE->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0;
                                                $status_val = "ACCEPTANCE";
                                                $status_id = \App\Enums\ConsumerStatus::REGISTER->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::GI_ENGINEER->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0;
                                                $status_val = "EXECUTION";
                                                $status_id = \App\Enums\ConsumerStatus::ACCEPT->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::HSE->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0;
                                                $status_val = "HSC";
                                                $status_id = \App\Enums\ConsumerStatus::EXECUTE->value;
                                            @endphp
                                            @break
                                        @case(\App\Enums\Role::ACTIVATION->value)
                                            @php
                                                $count = $consumers_count[\App\Enums\ConsumerStatus::HSC->value] ?? 0;
                                                $status_val = "ACTIVATION";
                                                $status_id = \App\Enums\ConsumerStatus::HSC->value;
                                            @endphp
                                            @break
                                        @default
                                            @php
                                                $count = 0;
                                                $status_val = $status_id = '';
                                            @endphp
                                            @break
                                    @endswitch
                                    <li class="list-group-item">{{ $status_val }}&nbsp;-&nbsp;<a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [$status_id], 'geo_area' => auth()->user()->ga->pluck('id')->toArray(), 'charge_area' => auth()->user()->cas->pluck('id')->toArray()]) }}" target="_blank"><strong>{{ numberFormat($count) }}</strong></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="container">
            <div class="row p-2 rounded-3 g-2">
                <div class="col-md-6 col-xs-12">
                    <h3 class="mb-0 text-decoration-underline text-primary-emphasis">Dashboard</h3>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="float-end">
                        <form id="consumer-filter-form" method="GET" action="{{ url('/') }}">
                            <div class="row g-2 mb-0">
                                <div class="col-auto">
                                    <div class="form-control">
                                        Cluster&nbsp;<x-master.cluster-filter />
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-control">
                                        GA&nbsp;<x-master.ga-filter class="float-end" />
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i></button>
                                    <a href="{{ url('/') }}" class="btn btn-warning ajax-link" title="Reset">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="consumer-filter-loader">
                @include('dashboard.home-body')
            </div>
            {{-- Quick links --}}
            @if ($quick_link->count() > 0)
                <div class="mt-3">
                    <h3 class="text-decoration-underline text-primary-emphasis">Quick Links</h3>
                </div>
                <div class="row g-3 mt-3">
                    @foreach ($quick_link as $link)
                        <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 col-xs-6">
                            <a href="{{ $link->url }}" target="_blank">  
                            <div class="card link-card-bg p-1 text-dark bg-opacity-10 border-3 border-light">
                            <div class="d-flex p-2 align-items-center">
                                <div>
                                    <div class="link-card-bg p-1 bg-opacity-25 rounded-4 py-1 px-2 mt-2">
                                        <i class="bi {{ $link->icon }}"></i>
                                    </div>
                                </div>
                                <div class="py-1 px-1 text-start ms-1 mt-1">
                                    <small class="fs-sm text-body">{{ $link->name }}</small>
                                </div>
                            </div>
                        </div>
                        </a>
                        </div>  
                    @endforeach      
                </div>
            @endif
        </div>
    </div>
@endsection
{{-- Scripts --}}
@include('scripts.quick-search')
@include('scripts.ajax-get-form-submit', ['form' => 'consumer-filter'])
@push('scripts')
    <script type="text/javascript" src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/highcharts/funnel.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/highcharts/accessibility.js') }}"></script>
@endpush
<style>
    .boxshadow {box-shadow: #dadada 3px 4px 10px 1px;} .con-card-bg {background: #c9aeee;background: linear-gradient(180deg, rgb(62 162 236 / 31%) 0%, rgb(233 141 178 / 30%) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase;} .inv-card-bg {background: #f1e9ad;background: linear-gradient(180deg, rgb(232 235 187 / 51%) 0%, #5dc3d25c 100%);
        box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase;} .py-card-bg {background: #c7542a;background: linear-gradient(181deg, rgba(199, 84, 42, 0.14) 32%, rgba(126, 229, 247, 0.45) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase;} .cls-card-bg {background: #EEAECA;background: linear-gradient(180deg, rgb(238 174 223 / 43%) 0%, rgb(246 244 73 / 25%) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase; } .activated-card-bg {background: #dcffab;background: linear-gradient(180deg, rgba(220, 255, 171, 0.3) 0%, rgba(149, 194, 240, 0.59) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase;} .total-card-bg {background: #abf9ff; background: linear-gradient(180deg, rgb(255, 171, 171, 22%) 0%, rgba(197, 157, 237, 0.59) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase;} .disconnect-card-bg {background: #eb6d57;background: linear-gradient(180deg, rgba(235, 109, 87, 0.24) 0%, rgba(255, 255, 255, 0.59) 100%);
        box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase;} .link-card-bg {background: #4253eb;background: linear-gradient(180deg, rgba(66, 83, 235, 0.2) 0%, rgba(160, 190, 250, 0.3) 100%);
        box-shadow: #cccccc 3px 1px 4px 0px;text-transform: uppercase;} #searchResults .list-group-item:hover {background-color: hsl(210, 10%, 88%)!important;}
        /* .con-card-bg:hover {
            border: 1px solid rgb(104, 69, 156);
            box-shadow: rgba(20, 20, 20, 0.16) 0px 8px 12px;
        } */
</style>