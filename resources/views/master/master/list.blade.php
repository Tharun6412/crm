{{-- Master landing page --}}

@extends('layouts.layout')

@section('title', 'Master Data')

@section('page-title', 'Master Data')

@section('page-content')
    <div>
        <ul class="list-group">
            @foreach ($modules as $module)
                <li class="list-group-item">
                    <div class="fs-5">
                        <i class="bi {{ $module->icon }}"></i>&nbsp;
                        {{ $module->name ?? '' }}
                    </div>
                    @if ($module->recursiveActiveChilds->count() > 0)
                        {{-- {{ $module->recursiveActiveChilds }} --}}
                        <ul class="list-group list-group-horizontal list-group-flush">
                            @foreach ($module->recursiveActiveChilds as $sub_module)
                                <li class="list-group-item border-0">
                                    <a href="{{ url($sub_module->url) }}" class="btn btn-outline-success btn-lg">
                                        <i class="bi {{ $sub_module->icon }}"></i>&nbsp;{{ $sub_module->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection