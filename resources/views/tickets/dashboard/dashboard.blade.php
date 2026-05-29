{{-- Ticket Dashboard --}}
@extends('layouts.layout')
@section('title','Dashboard')
@section('page-title','Ticket Dashboard')
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
    <h4>Ticket Status</h4>
    <div class="row g-2 mb-3 mt-2">
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::REGISTER->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($register) }}</div>
                            <div class="fw-semibold text-secondary">Registered</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                            <i class="fs-3 bi bi-person-square"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::APPROVE->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($approve) }}</div>
                            <div class="fw-semibold text-secondary">Approve</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                            <i class="fs-3 bi bi-check2-circle"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::PROCESSING->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($processing) }}</div>
                            <div class="fw-semibold text-secondary">Processing</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                            <i class="fs-3 bi bi-gear-wide-connected"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('master') }}?{{ http_build_query(['status' => [TicketStatus::CLOSE->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($close) }}</div>
                            <div class="fw-semibold text-secondary">Close</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                            <i class="fs-3 bi bi-check2-square"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::HOLD->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($hold) }}</div>
                            <div class="fw-semibold text-secondary">Hold</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                            <i class="fs-3 bi bi-pause-circle"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::CANCEL->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($cancel) }}</div>
                            <div class="fw-semibold text-secondary">Cancel</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                            <i class="fs-3 bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-3">
            <a href="{{ url('tickets') }}?{{ http_build_query(['status' => [TicketStatus::CANCEL->value]]) }}" target="_blank" class="text-body-secondary">
                <div class="py-2 px-2 border border-3 border-light shadow-sm rounded-3">
                    <div class="d-flex justify-content-between align-items-center p-2">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">{{ numberFormat($total) }}</div>
                            <div class="fw-semibold text-secondary">Total</div>
                        </div>
                        <div class="p-1 bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                            <i class="fs-3 bi bi-files"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection