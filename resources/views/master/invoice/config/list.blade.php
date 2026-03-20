{{-- Invoice items list --}}
@extends('layouts.layout')

@section('title', 'Invoice Config')

@section('page-title', 'Invoice Config')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        {{-- Taxes --}}
        <h4>Taxes</h4>
        @if ($taxes->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered bg-white">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S.No</th>
                            <th>Tax</th>
                            <th>Group</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxes as $tax)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $tax->name }}</td>
                                <td>{{ $tax->taxGroup->name ?? '' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Invoice Number TAX Counter --}}
        <div class="d-flex justify-content-between mb-2">
            <h4>Invoice Numbering</h4>
            <a href="{{ url('master/invoice/configuration/create') }}" class="btn btn-outline-success link-modal">
                <i class="bi bi-plus-lg"></i>&nbsp;Add
            </a>
        </div>
        @if ($invoice_numbering->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered bg-white">
                    <thead class="table-success">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>State</th>
                            <th>Tax</th>
                            <th>Series</th>
                            <th class="text-end">Count</th>
                            <th>Updated At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice_numbering as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item->state->name ?? '' }}</td>
                                <td>{{ $item->taxGroup->name ?? '' }}</td>
                                <td>{{ $item->invoice_code ?? '' }}</td>
                                <td class="text-end">{{ $item->count ?? '' }}</td>
                                <td>{{ $item->updated_at?->format('d-m-Y H:i') }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.link-modal')
@endpush