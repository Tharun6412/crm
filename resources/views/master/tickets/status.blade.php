{{-- Status --}}

@extends('layouts.layout')

@section('title', 'Ticket Status')

@section('page-title', 'Ticket Status')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master/tickets/status') }}">Status</a></li>
@endsection

@section('page-content')
    <div>
        <div class="mb-2">
            <span class="fw-semibold">({{ $status->count() }})</span> found
        </div>
        @if ($status->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover bg-white">
                    <thead class="table-success">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($status as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection