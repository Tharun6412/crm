@extends('layouts.layout')
@section('title','Team Consumers')
@section('page-title','Team Consumers')
@section('page-content')
<div>
    <form action="{{ url('consumers/waiting/pending-consumers') }}" method="GET" id="team-consumers-search-form">
        <div class="d-flex justify-content-between mb-1">
            <div class="row gx-1">
                <div class="col-auto">
                    <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
                </div>
                @foreach(request()->except(['key', 'page', 'geo_area', 'charge_area', 'status']) as $name => $value)
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
                    <a href="{{ url('consumers/waiting/pending-consumers') }}?{{ http_build_query(request()->except(['key', 'geo_area', 'charge_area'])) }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>
        </div>
        <div id="team-consumers-list" class="current-page-reload">
            @include('consumers.team-consumers.list-body')
        </div>
    </form>    
</div>
@endsection
@include('scripts.ajax-form-search',['form' => 'team-consumers'])
@include('scripts.ajax-form-submit', ['form' => 'team-bulk'])
