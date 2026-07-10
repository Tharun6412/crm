{{-- Ticket Dashboard --}}
@extends('layouts.layout')
@section('title','Dashboard')
@section('page-title','Tickets Dashboard')
@section('page-content')
<div class="bg-body-tertiary p-2 rounded-2">
    @php
        $ticket_status = [];
        foreach ($tickets as $key => $value) {
            $ticket_status[$value->status_id] = 0;
            $ticket_status[$value->status_id] += $value->status_count;
        }

        use \App\Enums\TicketStatus;
        $register = $ticket_status[TicketStatus::REGISTER->value] ?? 0;
        $approve = $ticket_status[TicketStatus::APPROVE->value] ?? 0;
        $processing = $ticket_status[TicketStatus::PROCESSING->value] ?? 0;
        $close = $ticket_status[TicketStatus::CLOSE->value] ?? 0;
        $hold = $ticket_status[TicketStatus::HOLD->value] ?? 0;
        $cancel = $ticket_status[TicketStatus::CANCEL->value] ?? 0;
        $total = $register+$approve+$processing+$close+$hold+$cancel;
    @endphp
    <div class="row g-2 mb-3">
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::REGISTER->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3 reg-bg">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($register) }}</div>
                            <div class="fw-semibold text-secondary">Registered</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm border border-dark-subtle">
                            <i class="fs-3 bi bi-person-square"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::APPROVE->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3 approve-bg">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($approve) }}</div>
                            <div class="fw-semibold text-secondary">Approve</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm border border-dark-subtle">
                            <i class="fs-3 bi bi-check2-circle"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::PROCESSING->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3 processing-bg">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($processing) }}</div>
                            <div class="fw-semibold text-secondary">Processing</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm border border-dark-subtle">
                            <i class="fs-3 bi bi-gear-wide-connected"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::CLOSE->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3 closed-bg">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($close) }}</div>
                            <div class="fw-semibold text-secondary">Close</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm border border-dark-subtle">
                            <i class="fs-3 bi bi-check2-square"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::HOLD->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3 hold-bg">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($hold) }}</div>
                            <div class="fw-semibold text-secondary">Hold</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm border border-dark-subtle">
                            <i class="fs-3 bi bi-pause-circle"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::CANCEL->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3 cancel-bg">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($cancel) }}</div>
                            <div class="fw-semibold text-secondary">Cancel</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm border border-dark-subtle">
                            <i class="fs-3 bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3 total-bg">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($total) }}</div>
                            <div class="fw-semibold text-secondary">Total</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm border border-dark-subtle">
                            <i class="fs-3 bi bi-files"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
<style>
    .reg-bg{background: #e8d64d;background: linear-gradient(180deg, rgba(232, 214, 77, 0.35) 14%, rgba(205, 222, 49, 0.47) 100%);}
    .approve-bg {background: #6fe8ce;background: linear-gradient(180deg, rgba(111, 232, 206, 0.35) 14%, rgba(36, 212, 168, 0.47) 100%); }
    .processing-bg {background: #418df0; background: linear-gradient(2deg, rgba(65, 141, 240, 0.3) 0%, rgba(210, 195, 250, 0.6) 100%, rgba(237, 221, 83, 0.3) 100%);}
    .closed-bg {background: #4de876;background: linear-gradient(180deg, rgba(77, 232, 118, 0.35) 14%, rgba(121, 222, 49, 0.47) 100%);}
    .hold-bg {background: #e697e3;background: linear-gradient(125deg, rgb(230 122 212 / 27%) 14%, rgb(203 57 151 / 38%) 100%)}
    .total-bg {background: rgba(151, 226, 230, 0.3);background: linear-gradient(180deg, rgba(151, 226, 230, 0.35) 14%, rgba(136, 179, 247, 0.47) 100%);}
    .cancel-bg {background: #faa2a2;background: linear-gradient(180deg, rgba(250, 162, 162, 0.35) 14%, rgba(247, 72, 59, 0.47) 100%);}
</style>   