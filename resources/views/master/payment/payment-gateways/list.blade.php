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
            <h5 class="mt-2">( {{ $payment_gateways->count() }} ) Records found</h5>
            <a href="#" class="btn btn-success"><i class="bi bi-plus-lg"></i>&nbsp;Add</a>
        </div>
        @if ($payment_gateways->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white">
                    <thead class="table-success">
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
                                    <a href="{{ url('master/payment/paymentGateways/' . $gateway->id) }}" class="btn btn-outline-info btn-sm link-canvas"><i class="bi bi-chevron-right"></i>&nbsp;View</a>
                                    <a href="{{ url('master/payment/paymentGateways/' . $gateway->id . '/edit') }}" class="btn btn-outline-info btn-sm link-modal"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>                                    
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