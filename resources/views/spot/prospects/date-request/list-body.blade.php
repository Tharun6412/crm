{{-- Date Request List --}}
@extends('layouts.layout')

@section('title', 'Date Change Requests')

@section('page-title', 'Date Change Requests')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('spot/dashboard') }}">SPot</a></li>
@endsection
@section('page-content')
    <div class="bd-callout bd-callout-info mt-0 mb-3">Displaying latest 50 date change requests</div>
    @if ($date_requests->count() > 0)
        <table class="table table-bordered table-hover table-primary">
            <thead class="bg-primary-subtle">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th nowrap>Prospect</th>
                    <th nowrap>Existing</th>
                    <th nowrap>New</th>
                    <th nowrap>Notes</th>
                    <th nowrap>Status</th>
                    <th nowrap>Requested By</th>
                    <th nowrap>Requested Date</th>
                    <th nowrap>Approved By</th>
                    <th nowrap>Approved Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($date_requests as $request)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a class="link-modal" href="{{ url('spot/prospects/'.$request->prospect_id) }}">
                                {{ $request->prospects->name }}
                            </a>
                        </td>
                        <td>{{ $request->current_date?->format('d-m-Y') }}</td>
                        <td>{{ $request->new_date?->format('d-m-Y') }}</td>
                        <td>{{ $request->note }}</td>
                        <td>
                            @switch($request->status)
                                @case(1)
                                    <span class='badge text-success border border-success'><i class='bi bi-check'></i>&nbsp;Approved</span>
                                    @break
                                @case(2)
                                    <span class='badge text-danger border border-danger'><i class='bi bi-check'></i>&nbsp;Rejected</span>
                                    @break
                                @default
                                    <span class='badge text-warning border border-warning'><i class='bi bi-pause-circle'></i>&nbsp;Pending</span>
                            @endswitch
                        </td>
                        <td>{{ $request->createdBy->first_name }}&nbsp;{{ $request->createdBy->last_name }}</td>
                        <td>{{ $request->created_at?->format('d-m-Y H:i:s') }}</td>
                        <td>{{ $request->approvedBy->first_name }}&nbsp;{{ $request->createdBy->last_name }}</td>
                        <td>{{ $request->approved_at?->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="bd-callout bd-callout-info mt-0 mb-3">No date request found</div>
    @endif
@endsection
@include('scripts.link-modal')
