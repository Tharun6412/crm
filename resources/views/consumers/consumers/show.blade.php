{{-- Consumer details --}}

@extends('layouts.layout')

@section('title', 'Consumer Details')

@section('page-title', ($consumer->crn ?? ''))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('consumers') }}">Consumers</a></li>
@endsection

@section('page-content')
    <div class="row g-2">
        <div class="col-md-2">
            <div class="row g-2">
                <div class="col-md-12">
                    <div class="border rounded-top p-2">
                        <img src="{{ asset('img/consumer-iimage.png') }}" alt="MeghaGas" class="img-fluid img-thumb">
                        <div class="mt-3 text-center">
                            <h4 class="m-0">{{ $consumer->crn }}</h4>
                            <h5 class="m-0">{{ $consumer->name }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="border">
                        <div class="list-group list-group-flush" role="tablist">
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light active" id="nav-details-tab" data-bs-toggle="tab" data-bs-target="#nav-details" role="tab" aria-controls="nav-details" aria-selected="true">
                                <i class="bi bi-person"></i>&nbsp;Consumer Details
                            </a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-sd-tab" data-bs-toggle="tab" data-bs-target="#nav-sd" role="tab" aria-controls="nav-sd" aria-selected="false">
                                <i class="bi bi-cash-stack"></i>&nbsp;SD Details
                            </a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-bills-tab" data-bs-toggle="tab" data-bs-target="#nav-bills" data-url="{{ url('consumers/invoices/' . $consumer->id . '/1') }}" role="tab" aria-controls="nav-bills" aria-selected="true">
                                <i class="bi bi-files"></i>&nbsp;Bills
                            </a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-inv-tab" data-bs-toggle="tab" data-bs-target="#nav-inv" data-url="{{ url('consumers/invoices/' . $consumer->id . '/2') }}" role="tab" aria-controls="nav-inv" aria-selected="false">
                                <i class="bi bi-files-alt"></i>&nbsp;Invoices
                            </a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-pay-tab" data-bs-toggle="tab" data-bs-target="#nav-pay" data-url="{{ url('payments/invoicePayments/show/'.$consumer->id) }}" role="tab" aria-controls="nav-pay" aria-selected="true">
                                <i class="bi bi-file-text"></i>&nbsp;Payments
                            </a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-crd-tab" data-bs-toggle="tab" data-bs-target="#nav-crd" data-url="{{ url('bill/creditNote/showCreditByConsumerId/'.$consumer->id) }}" role="tab" aria-controls="nav-crd" aria-selected="false">
                                <i class="bi bi-file-diff"></i>&nbsp;Credit / Debit Notes
                            </a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-ledger-tab" data-bs-toggle="tab" data-bs-target="#nav-ledger" data-url="{{ url('bill/ledger/'.$consumer->id) }}" role="tab" aria-controls="nav-ledger" aria-selected="true">
                                <i class="bi bi-file-ruled"></i>&nbsp;Ledger
                            </a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-calls-tab" data-bs-toggle="tab" data-bs-target="#nav-calls" data-url="{{ url('calls/consumerComplaints/' . $consumer->id) }}" role="tab" aria-controls="nav-calls" aria-selected="false">
                                <i class="bi bi-telephone-inbound"></i>&nbsp;Calls
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="row g-2">
                <div class="col-md-12">
                    <div class="d-flex border rounded-top p-2 fs-4">
                        <div>{{ $consumer->crn }}</div>&nbsp;|&nbsp;
                        <div>{{ $consumer->segment->name ?? '' }}</div>&nbsp;|&nbsp;
                        <div><x-consumer.status :status="$consumer->status" /></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="">
                        <div class="card-group">
                            <div class="card bg-success-subtle">
                                <div class="card-body">
                                    <h4 class="card-title">10,000</h4>
                                    <span>Bills</span>
                                </div>
                            </div>
                            <div class="card bg-success-subtle">
                                <div class="card-body">
                                    <h4 class="card-title">10,000</h4>
                                    <span>Invoices</span>
                                </div>
                            </div>
                            <div class="card bg-success-subtle">
                                <div class="card-body">
                                    <h4 class="card-title">20,000</h4>
                                    <span>Payments</span>
                                </div>
                            </div>
                            <div class="card bg-success-subtle">
                                <div class="card-body">
                                    <h4 class="card-title">{{ numberFormat($consumer->scheme?->balance) }}</h4>
                                    <span>Outstanding</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="nav-details" role="tabpanel" aria-labelledby="nav-details-tab" tabindex="0">
                            @include('consumers.consumers.show-details')
                        </div>
                        <div class="tab-pane fade" id="nav-sd" role="tabpanel" aria-labelledby="nav-sd-tab" tabindex="0">
                            @include('consumers.consumers.show-sd')
                        </div>
                        <div class="tab-pane fade" id="nav-bills" role="tabpanel" aria-labelledby="nav-bills-tab" tabindex="0">
                            {{-- Invoices will load dynamically --}}
                        </div>
                        <div class="tab-pane fade" id="nav-inv" role="tabpanel" aria-labelledby="nav-inv-tab" tabindex="0">
                            {{-- Invoices will load dynamically --}}
                        </div>
                        <div class="tab-pane fade" id="nav-pay" role="tabpanel" aria-labelledby="nav-pay-tab" tabindex="0">
                            {{-- @include('consumers.consumers.show-payments') --}}
                        </div>
                        <div class="tab-pane fade" id="nav-crd" role="tabpanel" aria-labelledby="nav-crd-tab" tabindex="0">
                            {{-- @include('consumers.consumers.show-credit') --}}
                        </div>
                        <div class="tab-pane fade" id="nav-ledger" role="tabpanel" aria-labelledby="nav-ledger-tab" tabindex="0">
                            {{-- @include('consumers.consumers.show-ledger') --}}
                        </div>
                        <div class="tab-pane fade" id="nav-calls" role="tabpanel" aria-labelledby="nav-calls-tab" tabindex="0">
                            {{-- @include('consumers.consumers.show-calls') --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
@endsection
{{-- Scripts --}}
@push('scripts')
    <script type="module">
        $(function(){
            // Tabs with ajax
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {                
                let target = $($(e.target).data('bs-target'));
                if (target.data('loaded')) return;
                if($(e.target).data('url')) {
                    $.get($(e.target).data('url'), function (data) {
                        target.html(data).data('loaded', true);
                    });
                }
            });
        });
    </script>
@endpush
{{-- Styles --}}
@push('styles')
    <style>
        dt {
            font-weight: 600;
        }
    </style>
@endpush