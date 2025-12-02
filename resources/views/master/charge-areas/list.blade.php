{{-- States --}}

@extends('layouts.layout')

@section('title', 'Charge Areas')

@section('page-title', 'Charge Areas')

@section('page-content')
    <div>
        <div class="mb-2">
            ({{ $charge_areas->count() }}) Records found
        </div>
        @if ($charge_areas->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>District</th>
                            <th>GA</th>
                            <th>Areas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($charge_areas as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->district->name ?? '' }}</td>
                                <td>{{ $item->ga->name ?? '' }}</td>
                                <td>{{ $item->areas->count() ?? 0 }}</td>
                                <td><x-common.status :status="$item->status"/></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection