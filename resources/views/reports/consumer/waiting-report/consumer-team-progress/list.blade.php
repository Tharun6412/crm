{{-- Consumer Team Progress Report --}}

@extends('layouts.layout')

@section('title', 'Consumer Progress')

@section('page-title', 'Consumer Progress')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('reports') }}">Reports</a></li>
@endsection

@section('page-content')
    <div>
        <form action="{{ url('reports/consumer/progressList') }}" method="GET">
            <div class="d-flex justify-content-between mb-1">
                <div class="row gx-1">
                    <div class="col-auto">
                        <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
                    </div>
                    @foreach(request()->except(['key', 'page']) as $name => $value)
                        @if(is_array($value))
                            @foreach($value as $item)
                                <input type="hidden" name="{{ $name }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <div class="col-auto">
                        <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ url('reports/consumer/progressList') }}?{{ http_build_query(request()->except(['key', 'charge_area', 'area'])) }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </div>
            </div>
            <div id="team-consumers-list" class="mt-2">
                @include('reports.consumer.waiting-report.consumer-team-progress.list-body')
            </div>
        </form>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'team-consumers'])
@endpush