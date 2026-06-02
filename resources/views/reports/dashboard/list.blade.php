{{-- Reports.dashboard.list --}}

@extends('layouts.layout')

@section('title', 'Reports')

@section('page-title', 'Reports')

@section('page-content') 
    <div class="row g-3 mt-1 mb-2">
        @foreach ($modules as $module)
            <div class="col-12">
                <div class="border-start border-4 border-info rounded bg-info-subtle px-3 py-2 mb-2">
                    <h4 class="mb-0 fw-bold text-uppercase p-1">
                        {{ $module->name ?? '' }}
                    </h4>
                </div>
            </div>
            @if ($module->recursiveActiveChilds->isNotEmpty())
                {{-- Third level --}}
                @foreach ($module->recursiveActiveChilds as $module1)
                    <div class="col-md-6 col-sm-6 col-lg-4 col-xl-3 col-xs-12">
                        <a href="{{ url($module1->url) }}" class="text-dark">
                            <div class="d-flex align-middle border border-secondary-subtle report-card-bg rounded p-3 fs-18 shadow-sm">
                                <div><i class="bi {{ $module1->icon }} report-card-bg shadow-sm p-2 fs-3 rounded-3 border border-secondary-subtle"></i></div>
                                <div class="mt-1 fs-6">&nbsp;{{ $module1->name ?? '' }}</div>                    
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        @endforeach
    </div>
@endsection
<style>
.report-card-bg { background: #c9aeee;background: linear-gradient(240deg, rgb(62 222 236 / 31%) 0%, rgb(211 233 141 / 30%) 100%);box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase;} .fs-18 {font-size: 1.05rem;}
</style>