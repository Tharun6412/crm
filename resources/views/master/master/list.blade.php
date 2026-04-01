{{-- Master landing page --}}

@extends('layouts.layout')

@section('title', 'Master Data')

@section('page-title', 'Master Data')

@section('page-content')
    <div>
        <ul class="list-group gap-2 mb-2">
            @foreach ($modules as $module)
                <li class="list-group-item border rounded-3 shadow">
                    <div class="fs-5 bg-body-secondary rounded-3 p-2">
                        <i class="bi {{ $module->icon }} text-dark fw-semibold ms-2"></i>&nbsp;
                        <span class="text-dark fw-semibold">{{ $module->name ?? '' }}</span>
                    </div>
                    <div class="row g-2 align-items-center mt-2 mb-3">
                    @if ($module->recursiveActiveChilds->count() > 0)
                        {{-- {{ $module->recursiveActiveChilds }}
                        <ul class="list-group list-group-horizontal list-group-flush">
                            @foreach ($module->recursiveActiveChilds as $sub_module)
                                <li class="list-group-item border-0">
                                    <a href="{{ url($sub_module->url) }}" class="btn btn-outline-primary shadow-sm">
                                        <i class="bi {{ $sub_module->icon }}"></i>&nbsp;{{ $sub_module->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul> --}}
                         @foreach ($module->recursiveActiveChilds as $sub_module)
                         <div class="col-md-6 col-sm-6 col-lg-4 col-xl-3 col-xs-12">
                            <a href="{{ url($sub_module->url) }}" class="text-dark">
                                <div class="d-flex align-middle border border-secondary-subtle master-card-bg rounded p-3 fs-5 shadow-sm">
                                    <div><i class="bi {{ $sub_module->icon }} master-card-bg shadow-sm p-2 fs-4 rounded-3 border border-secondary-subtle"></i></div>
                                    <div class="mt-1">&nbsp;{{ $sub_module->name }}</div> 
                                </div>
                            </a>
                        </div>
                        @endforeach
                    @endif
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
<style>
.master-card-bg {background: #c9aeee;background: linear-gradient(240deg, rgb(43 200 206 / 31%) 0%, rgb(215 141 233 / 30%) 100%);
    box-shadow: #dadada 3px 4px 10px 1px;text-transform: uppercase;}
</style>