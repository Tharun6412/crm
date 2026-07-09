{{-- Consumer details --}}

@extends('layouts.layout')

@section('title', 'Consumer Details')

@section('page-title', 'Consumer Details - '.($consumer->crn ?? ''))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('consumers') }}">Consumers</a></li>
@endsection

@section('page-content')
    <div class="row g-2">
        <div class="col-md-2">
            <div class="row g-2">
                <div class="col-md-12">
                    <div class="border rounded-top p-2 bg-white">
                        <img src="{{ asset('img/consumer-iimage.png') }}" alt="MeghaGas" class="img-fluid img-thumb opacity-25">
                        <div class="mt-3 text-center">
                            <h4 class="m-1 text-primary">{{ $consumer->crn }}</h4>
                            <h5 class="m-1 text-success">{{ $consumer->name }}</h5>
                            <h5 class="m-0 text-secondary"><i class="bi bi-geo-alt"></i>&nbsp;{{ $consumer->ga->name ?? '' }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="border">
                        <div class="list-group list-group-flush" role="tablist">
                            <a href="#" class="list-group-item list-group-item-action active" id="nav-details-tab" data-bs-toggle="tab" data-bs-target="#nav-details" role="tab" aria-controls="nav-details" aria-selected="true">
                                <i class="bi bi-person"></i>&nbsp;Consumer Details
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-sd-tab" data-bs-toggle="tab" data-bs-target="#nav-sd" role="tab" aria-controls="nav-sd" aria-selected="false">
                                <i class="bi bi-cash-stack"></i>&nbsp;Scheme Details
                            </a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-mdata-tab" data-bs-toggle="tab" data-bs-target="#nav-mdata" role="tab" aria-controls="nav-mdata" aria-selected="false">
                                <i class="bi bi-speedometer2"></i>&nbsp;Meter Data
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-geysers-tab" data-bs-toggle="tab" data-bs-target="#nav-geysers" data-url="{{ url('consumers/consumergeysers/' . $consumer->id) }}" role="tab" aria-controls="nav-geysers" aria-selected="false">
                                <i class="bi bi-moisture"></i>&nbsp;Geysers
                            </a>
                            @if ($consumer->connection_type_id == 2)
                                <a href="#" class="list-group-item list-group-item-action" id="nav-recharge-tab" data-bs-toggle="tab" data-bs-target="#nav-recharge" data-url="{{ url('consumers/prepaid/consumerRechargeList/' . $consumer->id) }}" role="tab" aria-controls="nav-recharge" aria-selected="true">
                                    <i class="bi bi-wallet2"></i>&nbsp;Recharge History
                                </a>
                            @endif
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light" id="nav-bills-tab" data-bs-toggle="tab" data-bs-target="#nav-bills" data-url="{{ url('consumers/invoices/' . $consumer->id . '/1') }}" role="tab" aria-controls="nav-bills" aria-selected="true">
                                <i class="bi bi-files"></i>&nbsp;Bills
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-inv-tab" data-bs-toggle="tab" data-bs-target="#nav-inv" data-url="{{ url('consumers/invoices/' . $consumer->id . '/2') }}" role="tab" aria-controls="nav-inv" aria-selected="false">
                                <i class="bi bi-files-alt"></i>&nbsp;Invoices
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-pay-tab" data-bs-toggle="tab" data-bs-target="#nav-pay" data-url="{{ url('payments/invoicePayments/show/'.$consumer->id) }}" role="tab" aria-controls="nav-pay" aria-selected="true">
                                <i class="bi bi-file-text"></i>&nbsp;Payments
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-crd-tab" data-bs-toggle="tab" data-bs-target="#nav-crd" data-url="{{ url('bill/creditNote/showCreditByConsumerId/'.$consumer->id) }}" role="tab" aria-controls="nav-crd" aria-selected="false">
                                <i class="bi bi-file-diff"></i>&nbsp;Credit / Debit Notes
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-ledger-report-tab" data-bs-toggle="tab" data-bs-target="#nav-ledger-report" data-url="{{ url('consumers/ledgerReport/' . $consumer->id) }}" role="tab" aria-controls="nav-ledger-report" aria-selected="false">
                                <i class="bi bi-file-ruled"></i>&nbsp;Ledger&nbsp;<span class="badge rounded-pill text-bg-danger">new</span>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-ledger-tab" data-bs-toggle="tab" data-bs-target="#nav-ledger" data-url="{{ url('bill/ledger/'.$consumer->id) }}" role="tab" aria-controls="nav-ledger" aria-selected="true">
                                <i class="bi bi-file-ruled"></i>&nbsp;Ledger
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-calls-tab" data-bs-toggle="tab" data-bs-target="#nav-calls" data-url="{{ url('calls/consumerComplaints/' . $consumer->id) }}" role="tab" aria-controls="nav-calls" aria-selected="false">
                                <i class="bi bi-telephone-inbound"></i>&nbsp;Calls
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-doc-tab" data-bs-toggle="tab" data-bs-target="#nav-doc" data-url="{{ url('consumers/consumerDocs/' . $consumer->id) }}" role="tab" aria-controls="nav-doc" aria-selected="false">
                                <i class="bi bi-files"></i>&nbsp;Documents
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-history-tab" data-bs-toggle="tab" data-bs-target="#nav-history" data-url="{{ url('consumers/kyc/' . $consumer->id) }}" role="tab" aria-controls="nav-History" aria-selected="false">
                                <i class="bi bi-clock-history"></i>&nbsp;History
                            </a>
                            <a href="#" class="list-group-item list-group-item-action" id="nav-tickets-tab" data-bs-toggle="tab" data-bs-target="#nav-tickets" data-url="{{ url('tickets/consumersTicket/' . $consumer->id) }}" role="tab" aria-controls="nav-tickets" aria-selected="false">
                                <i class="bi bi-ticket"></i>&nbsp;Tickets
                            </a>
                            
                            {{-- <a href="#" class="list-group-item list-group-item-action" id="nav-advance-tab" data-bs-toggle="tab" data-bs-target="#nav-advance" data-url="{{ url('payments/invoicePayments/show/' . $consumer->id) }}" role="tab" aria-controls="nav-advance" aria-selected="false">
                                <i class="bi bi-clock-history"></i>&nbsp;Advance Transactions
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="row g-2">
                <div class="col-md-12">
                    <div class="d-flex border rounded-top p-2 fs-5 bg-info-subtle">
                        <div>{{ $consumer->crn ?? $consumer->t_crn }}</div>&nbsp;<span class="text-body-tertiary">|</span>&nbsp;
                        <div>{{ $consumer->segment->name ?? '' }}</div>&nbsp;<span class="text-body-tertiary">|</span>&nbsp;
                        <div>{{ $consumer->connection_type_id == 2 ? 'Prepaid' : 'Postpaid' }}</div>&nbsp;<span class="text-body-tertiary">|</span>&nbsp;
                        <div class="mt-1"><x-consumer.status :status="$consumer->status" /></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="shadow-sm">
                        <div class="card-group">
                            <div class="card bg-{{ ($consumer->scheme?->balance > 0) ? 'danger' : 'success' }}-subtle">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="row">
                                            <h4 class="card-title mb-0"><i class="bi bi-currency-rupee"></i>&nbsp;{{ numberFormat($consumer->scheme?->balance, 2) }}</h4>
                                            <span>Security Deposit Outstanding</span>
                                        </div>
                                        <div class="p-2"><i class="bi bi-piggy-bank fs-3"></i></div>
                                    </div>
                                </div>
                            </div>
                            @php
                                $gasbill_outstand = $consumer->invoices()->where('type_id', 1)->where('status_id', '!=', 4)->sum('balance_amount');
                                $invoice_outstand = $consumer->invoices()->where('type_id', '!=', 1)->where('status_id', '!=', 4)->sum('balance_amount');
                                $total_outstand = ($consumer->scheme?->balance + $gasbill_outstand + $invoice_outstand);
                            @endphp
                            @if ($consumer->connection_type_id == 1)
                                <div class="card bg-{{ ($gasbill_outstand > 0) ? 'danger' : 'success' }}-subtle">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="card-title mb-0"><i class="bi bi-currency-rupee"></i>&nbsp;{{ numberFormat($gasbill_outstand, 2) }}</h4>
                                                <span>Gas Bills Outstanding</span>
                                            </div>
                                            <div class="p-2"><i class="bi bi-file-text fs-3"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-{{ ($invoice_outstand > 0) ? 'danger' : 'success' }}-subtle">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="card-title mb-0"><i class="bi bi-currency-rupee"></i>&nbsp;{{ numberFormat($invoice_outstand, 2) }}</h4>
                                                <span>Invoices Outstanding</span>
                                            </div>
                                            <div class="p-2"><i class="bi bi-file-ruled fs-3"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card bg-{{ ($total_outstand > 0) ? 'danger' : 'success' }}-subtle">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="card-title mb-0"><i class="bi bi-currency-rupee"></i>&nbsp;{{ numberFormat($total_outstand, 2) }}</h4>
                                                <span>Total Outstanding</span>
                                            </div>
                                            <div class="p-2"><i class="bi bi-alarm fs-3"></i></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($consumer->connection_type_id == 2)
                                <div class="card bg-primary-subtle">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4 class="card-title mb-0" id="balance">{{ numberFormat($consumer->prepaidData->balance ?? 0, 2) }}</h4>
                                                <span>Available Meter Balance</span>
                                                <span class="text-body-tertiary">|</span>
                                                <small id="balance_date">Last Fetch Date: &nbsp;&nbsp;</small><a type="button" onclick="getPrepaidBalance({{ $consumer->id }})"><i class="bi bi-arrow-repeat fs-4 text-primary"></i></a>
                                                <small class="text-danger" id="message"></small>
                                            </div>
                                            <div class="p-2 text-end"><i class="bi bi-wallet fs-3"></i></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="tab-content bg-white">
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
                        <div class="tab-pane fade" id="nav-doc" role="tabpanel" aria-labelledby="nav-doc-tab" tabindex="0">
                            {{-- @include('consumers.consumers.show-calls') --}}
                        </div>
                        <div class="tab-pane fade" id="nav-mdata" role="tabpanel" aria-labelledby="nav-mdata-tab" tabindex="0">
                            @include('consumers.consumers.show-maintanance')
                        </div>
                        <div class="tab-pane fade" id="nav-recharge" role="tabpanel" aria-labelledby="nav-recharge-tab" tabindex="0"></div>
                        <div class="tab-pane fade" id="nav-history" role="tabpanel" aria-labelledby="nav-history-tab" tabindex="0"></div>
                        <div class="tab-pane fade" id="nav-ledger-report" role="tabpanel" aria-labelledby="nav-ledger-report-tab" tabindex="0"></div>
                        <div class="tab-pane fade" id="nav-geysers" role="tabpanel" aria-labelledby="nav-geysers-tab" tabindex="0">
                            {{-- @include('consumers.consumers.show-geysers') --}}
                        </div>
                        <div class="tab-pane fade" id="nav-tickets" role="tabpanel" aria-labelledby="nav-tickets-tab" tabindex="0"></div>

                        {{-- <div class="tab-pane fade" id="nav-advance-report" role="tabpanel" aria-labelledby="nav-advance-report-tab" tabindex="0"></div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
@endsection
{{-- Scripts --}}
<script type="text/javascript">
    // To get Updated consumer Balance 
    function getPrepaidBalance(consumer_id)
    {
        $.get("{{ url('consumers/prepaid/balance') }}/"+consumer_id, function(data) {
            $('#balance').text(data.balance);
            $('#balance_date').text('Balance Date: ' + data.balance_date);
            if(data.message) {
                $('#message').text(data.message);
            }
        }).fail(function (xhr) {
            if (xhr.status === 429) {
                // Too many requests
                $('#message').text("Too many requests. Please try again a minute");
            } else {
                // Other errors
                $('#message').text("Failed to fetch updated balance");
            }
        });
    }
</script>
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
        .dt { font-weight: 600; } 
        .bg-yellow { background-color: yellow; } 
        .border-yellow { border-color: yellow!important; } 
        .bg-purple { background-color: purple; }  
        .border-purple { border-color: purple!important; }   
    </style>
@endpush