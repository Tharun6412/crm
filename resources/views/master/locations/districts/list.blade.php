{{-- States --}}

@extends('layouts.layout')

@section('title', 'Districts')

@section('page-title', 'Districts')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        <div class="mb-2">
            ({{ $districts->count() }}) Records found
        </div>
        @if ($districts->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>GA</th>
                            <th>State</th>
                            <th>Cluster</th>
                            <th>CAs</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($districts as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->ga->name ?? '' }}</td>
                                <td>{{ $item->cluster->name ?? '' }}</td>
                                <td>{{ $item->state->name ?? '' }}</td>
                                <td>{{ $item->cas->count() ?? 0 }}</td>
                                <td><x-common.status :status="$item->status"/></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection