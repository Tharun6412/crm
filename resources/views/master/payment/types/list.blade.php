{{-- Invoice items list --}}
@extends('layouts.layout')

@section('title', 'Payment Types')

@section('page-title', 'Payment Types')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    @if ($payment_types->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-primary">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S No</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payment_types as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td><x-common.status :status="$item->status"/></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-layouts.callout-info>No records found!</x-layouts.callout-info>
    @endif
@endsection