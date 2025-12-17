{{-- Invoice items list --}}
@extends('layouts.layout')

@section('title', 'Invoice Types')

@section('page-title', 'Invoice Types')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    @if ($invoice_types->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-primary">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S No</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice_types as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <x-layouts.callout-info>No records found!</x-layouts.callout-info>
    @endif
@endsection