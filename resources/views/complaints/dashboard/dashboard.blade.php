{{-- Dashboard --}}

@extends('layouts.layout')

@section('title', 'Customer Service')

@section('page-title', 'Customer Service')

@section('page-content')
    <div>
        @php
            // Array Preparation
            $complaint_status = $complaint_segment = [];
            foreach ($complaints as $key => $value) {
                # code...
                $complaint_status[$value->status_id] = $value->status_count;
                // SEGMENT Based Count
                if (!isset($complaint_segment[$value->segment_id])) {
                    $complaint_segment[$value->segment_id] = 0;
                }
                $complaint_segment[$value->segment_id] += $value->segment_count;
            }
            // Complaint Segment Dynamic
            use \App\Enums\ComplaintSegmentType;
            // Complaints Enums
            use \App\Enums\ComplaintStatus;
            // Complaint Status Dynamic
            $register = $complaint_status[ComplaintStatus::REGISTER->value] ?? 0;
            $assign = $complaint_status[ComplaintStatus::ASSIGN->value] ?? 0;
            $inprogress = $complaint_status[ComplaintStatus::IN_PROGRESS->value] ?? 0;
            $investigation = $complaint_status[ComplaintStatus::INVESTIGATION->value] ?? 0;
            $close = $complaint_status[ComplaintStatus::CLOSE->value] ?? 0;
            $cancel = $complaint_status[ComplaintStatus::CANCEL->value] ?? 0;
            $total = $register+$assign+$inprogress+$investigation+$close+$cancel;
        @endphp
        <div class="row g-2 mb-3">
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::PNGDOM->value]]) }}" target="_blank">
                    <div class="border border-primary rounded p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>DPNG</span>
                            <span class="fs-3 fw-semibold">{{ $complaint_segment[ComplaintSegmentType::PNGDOM->value] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::PNGCOM->value]]) }}" target="_blank">
                    <div class="border border-primary rounded p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>CPNG</span>
                            <span class="fs-3 fw-semibold">{{ $complaint_segment[ComplaintSegmentType::PNGCOM->value] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::PNGIND->value]]) }}" target="_blank">
                    <div class="border border-primary rounded p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>IPNG</span>
                            <span class="fs-3 fw-semibold">{{ $complaint_segment[ComplaintSegmentType::PNGIND->value] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::CNG->value]]) }}" target="_blank">
                    <div class="border border-primary rounded p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>CNG</span>
                            <span class="fs-3 fw-semibold">{{ $complaint_segment[ComplaintSegmentType::CNG->value] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::GENERAL->value]]) }}" target="_blank">
                    <div class="border border-primary rounded p-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>General</span>
                            <span class="fs-3 fw-semibold">{{ $complaint_segment[ComplaintSegmentType::GENERAL->value] ?? 0 }}</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        {{-- Call status counts --}}
        <h4>Call Status</h4>
        {{-- Calls Dynamic Count --}}
        <div class="row g-2">
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::REGISTER->value]]) }}" target="_blank">
                    <div class="border border-warning text-warning rounded py-2 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $register }}</div>
                                <div>Registered</div>
                            </div>
                            <div>
                                <i class="fs-2 bi bi-telephone-inbound"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::ASSIGN->value]]) }}" target="_blank">
                    <div class="border border-info text-info rounded py-2 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $assign }}</div>
                                <div>Assigned</div>
                            </div>
                            <div>
                                <i class="fs-2 bi bi-arrow-left-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::IN_PROGRESS->value]]) }}" target="_blank">
                    <div class="border border-primary text-primary rounded py-2 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $inprogress }}</div>
                                <div>In-Progress</div>
                            </div>
                            <div>
                                <i class="fs-2 bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::INVESTIGATION->value]]) }}" target="_blank">
                    <div class="border border-secondary text-secondary rounded py-2 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $investigation }}</div>
                                <div>Investigation</div>
                            </div>
                            <div>
                                <i class="fs-2 bi bi-search"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::CLOSE->value]]) }}" target="_blank">
                    <div class="border border-success text-success rounded py-2 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $close }}</div>
                                <div>Closed</div>
                            </div>
                            <div>
                                <i class="fs-2 bi bi-check2-circle"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::CANCEL->value]]) }}" target="_blank">
                    <div class="border border-danger text-danger rounded py-2 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $cancel }}</div>
                                <div>Cancelled</div>
                            </div>
                            <div>
                                <i class="fs-2 bi bi-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}" target="_blank">
                    <div class="border border-dark text-dark rounded py-2 px-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $total }}</div>
                                <div>Total</div>
                            </div>
                            <div>
                                <i class="fs-2 bi bi-files"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection