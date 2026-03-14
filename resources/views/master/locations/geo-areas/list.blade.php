{{-- States --}}

@extends('layouts.layout')

@section('title', 'Geo Areas')

@section('page-title', 'Geo Areas')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        <div class="mb-2">
            <span class="fw-semibold">({{ $geo_areas->count() }})</span> found
        </div>
        @if ($geo_areas->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover bg-white">
                    <thead class="table-success">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>GA Code</th>
                            <th>GA Name</th>
                            <th>State</th>
                            <th>Cluster</th>
                            <th>GA Districts</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($geo_areas as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->state->name ?? '' }}</td>
                                <td>{{ $item->cluster->name ?? '' }}</td>
                                <td>{{ $item->districts->pluck('name') ?? '' }}</td>
                                <td><x-common.status :status="$item->status"/></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection