{{-- Payment gateways list --}}

@extends('layouts.layout')

@section('title', 'Payment Gateways')

@section('page-title', 'Payment Gateways')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        <div class="d-flex justify-content-between mb-2">
            <h5>( {{ $payment_gateways->count() }} ) Records found</h5>
            <a href="#" class="btn btn-success btn-sm"><i class="bi bi-plus-lg"></i>&nbsp;Add</a>
        </div>
        @if ($payment_gateways->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead class="table-primary">
                        <tr>
                            <th width="1%" nowrap>S No</th>
                            <th>Gateway Name</th>
                            <th>Mode</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payment_gateways as $gateway)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $gateway->gateway }}</td>
                                <td>{{ $gateway->mode }}</td>
                                <td>{{ $gateway->is_active }}</td>
                                <td>
                                    <a href="{{ url('master/payment/paymentGateways/' . $gateway->id . '/edit') }}" class="btn btn-sm link-modal">
                                        Edit
                                    </a>
                                    <a href="{{ url('master/payment/paymentGateways/' . $gateway->id) }}" class="btn btn-sm link-canvas">
                                        View
                                    </a>
                                </td>
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
    @include('scripts.link-canvas')
@endpush