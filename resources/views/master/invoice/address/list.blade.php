{{-- Invoice Address list --}}
@extends('layouts.layout')

@section('title', 'Invoice Address')

@section('page-title', 'Invoice Address')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        @if ($addresses->count() > 0)
            <table class="table table-bordered table-striped align-middle bg-white">
                <thead class="table-success">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>GA</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($addresses as $address)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $address->ga->name }}</td>
                            <td>
                                {{ $address->line1 }},<br>
                                {{ $address->line2 }},<br>
                                {{ $address->city }}, {{ $address->district->name ?? '' }},<br>
                                {{ $address->state->name ?? '' }} - {{ $address->pincode }}.
                            </td>
                            <td>
                                <a href="{{ url('master/invoice/addresses/' . $address->id) }}" class="link-canvas btn btn-outline-info">View</a>
                                <a href="{{ url('master/invoice/addresses/' . $address->id . '/edit') }}" class="link-modal btn btn-outline-info">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.link-modal')
    @include('scripts.link-canvas')
@endpush