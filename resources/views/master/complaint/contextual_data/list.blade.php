{{-- Complaint categories list --}}

@extends('layouts.layout')

@section('page-title', 'Complaint Contextual Data')

@section('title', 'Contextual Data')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        <div class="accordion" id="accordionContextual">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panel-segments" aria-expanded="true" aria-controls="panel-segments">
                        <i class="bi bi-list-stars"></i>&nbsp;Segments
                    </button>
                </h2>
                <div id="panel-segments" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        @if ($segments->count() > 0)
                            @foreach ($segments as $item)
                                <div class="mb-1"><i class="bi bi-arrow-right-short"></i>&nbsp;{{ $item->name }}</div>
                            @endforeach
                        @else
                            <x-layouts.callout-info>No data found!</x-common-callout-info>
                        @endif
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panel-types" aria-expanded="false" aria-controls="panel-types">
                        <i class="bi bi-chat-left-dots"></i>&nbsp;Types
                    </button>
                </h2>
                <div id="panel-types" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        @if ($types->count() > 0)
                            @foreach ($types as $item)
                                <div class="mb-1"><i class="bi bi-arrow-right-short"></i>&nbsp;{{ $item->name }}</div>
                            @endforeach
                        @else
                            <x-layouts.callout-info>No data found!</x-common-callout-info>
                        @endif
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panel-media" aria-expanded="true" aria-controls="panel-media">
                        <i class="bi bi-cast"></i>&nbsp;Media
                    </button>
                </h2>
                <div id="panel-media" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        @if ($media->count() > 0)
                            @foreach ($media as $item)
                                <div class="mb-1"><i class="bi bi-arrow-right-short"></i>&nbsp;{{ $item->name }}</div>
                            @endforeach
                        @else
                            <x-layouts.callout-info>No data found!</x-common-callout-info>
                        @endif
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panel-status" aria-expanded="true" aria-controls="panel-status">
                        <i class="bi bi-person-gear"></i>&nbsp;Status
                    </button>
                </h2>
                <div id="panel-status" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        @if ($status->count() > 0)
                            @foreach ($status as $item)
                                <div class="mb-1"><i class="bi bi-arrow-right-short"></i>&nbsp;{{ $item->name }}</div>
                            @endforeach
                        @else
                            <x-layouts.callout-info>No data found!</x-common-callout-info>
                        @endif
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fs-5 fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panel-priorities" aria-expanded="true" aria-controls="panel-priorities">
                        <i class="bi bi-hourglass-split"></i>&nbsp;Priorities
                    </button>
                </h2>
                <div id="panel-priorities" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        @if ($priorities->count() > 0)
                            @foreach ($priorities as $item)
                                <div class="mb-1"><i class="bi bi-arrow-right-short"></i>&nbsp;{{ $item->name }}</div>
                            @endforeach
                        @else
                            <x-layouts.callout-info>No data found!</x-common-callout-info>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection