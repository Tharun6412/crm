{{-- States --}}

@extends('layouts.layout')

@section('title', 'Clusters')

@section('page-title', 'Clusters')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        <div class="mb-2">
            ({{ $clusters->count() }}) Records found
        </div>
        @if ($clusters->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Code</th>
                            <th>Cluster Name</th>
                            <th>Description</th>
                            <th>Cluster GAs</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clusters as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->gas->pluck('name') ?? '' }}</td>
                                <td><x-common.status :status="$item->status"/></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection