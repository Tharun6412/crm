{{-- States --}}

@extends('layouts.layout')

@section('title', 'States')

@section('page-title', 'States')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        <div class="mb-2">
            ({{ $states->count() }}) Records found
        </div>
        @if ($states->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($states as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td><x-common.status :status="$item->status"/></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection