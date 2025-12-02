{{-- States --}}

@extends('layouts.layout')

@section('title', 'States')

@section('page-title', 'Areas')

@section('page-content')
    <div>
        <div class="mb-2">
            ({{ $areas->count() }}) Records found
        </div>
        @if ($areas->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Name</th>
                            <th>CA</th>
                            <th>District</th>
                            <th>GA</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($areas as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->ca->name ?? '' }}</td>
                                <td>{{ $item->ca->district->name ?? '' }}</td>
                                <td>{{ $item->ca->ga->name ?? '' }}</td>
                                <td><x-common.status :status="$item->status"/></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection