{{-- Dashboard --}}

@extends('layouts.layout')

@section('title', 'Customer Service')

@section('page-title', 'Customer Service')

@section('page-content')
    <div class="bg-body-tertiary p-2 rounded-2">
        @php
            // Array Preparation
            $complaint_status = $complaint_segment = [];
            foreach ($complaints as $key => $value) {
                # code...
                if (!isset($complaint_status[$value->status_id])) {
                    $complaint_status[$value->status_id] = 0;
                }
                $complaint_status[$value->status_id] += $value->status_count;
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
        <div class="row g-2 mb-3 mt-2">
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::PNGDOM->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="p-3 dpng-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="p-1 dpng-bg rounded-4 py-2 px-3 shadow-sm"><i class="bi bi-building fs-3"></i></div>
                            <div class="fs-4 fw-semibold text-center">{{ $complaint_segment[ComplaintSegmentType::PNGDOM->value] ?? 0 }}<br/><span class="fs-5">DPNG</span></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::PNGCOM->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="p-3 cpng-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="p-1 cpng-bg rounded-4 py-2 px-3 shadow-sm"><i class="bi bi-house fs-3"></i></div>
                            <div class="fs-4 fw-semibold text-center">{{ $complaint_segment[ComplaintSegmentType::PNGCOM->value] ?? 0 }}<br/><span class="fs-5">CPNG</span></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::PNGIND->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="p-3 ipng-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="p-1 ipng-bg rounded-4 py-2 px-3 shadow-sm"><i class="bi bi-buildings fs-3"></i></div>
                            <div class="fs-4 fw-semibold text-center">{{ $complaint_segment[ComplaintSegmentType::PNGIND->value] ?? 0 }}
                                <br/><span class="fs-5">IPNG</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::CNG->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="p-3 cng-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="p-1 cng-bg rounded-4 py-2 px-3 shadow-sm"><i class="bi bi-fuel-pump fs-3"></i></div>
                            <span class="fs-4 fw-semibold text-center">{{ $complaint_segment[ComplaintSegmentType::CNG->value] ?? 0 }}
                                <br/><span class="fs-5">CNG</span>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col">
                <a href="{{ url('calls') }}?{{ http_build_query(['segment_id'=> [ComplaintSegmentType::GENERAL->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="p-3 general-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="p-1 general-bg bg-opacity-25 rounded-4 py-2 px-3 shadow-sm"><i class="bi bi-headset fs-3"></i></div>
                            <div class="fs-4 fw-semibold text-center">{{ $complaint_segment[ComplaintSegmentType::GENERAL->value] ?? 0 }}
                                <br/><span class="fs-5">General</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        {{-- Call status counts --}}
        <h4>Call Status</h4>
        {{-- Calls Dynamic Count --}}
        <div class="row g-2 mb-3 mt-2">
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::REGISTER->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="py-2 px-2 registered-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center p-2">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $register }}</div>
                                <div class="fw-semibold text-secondary">Registered</div>
                            </div>
                            <div class="p-1 registered-bg bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                                <i class="fs-3 bi bi-telephone-inbound"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::ASSIGN->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="py-2 px-2 assign-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center p-2">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $assign }}</div>
                                <div class="fw-semibold text-secondary">Assigned</div>
                            </div>
                            <div class="p-1 assign-bg bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                                <i class="fs-3 bi bi-arrow-left-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::IN_PROGRESS->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="py-2 px-2 in-progress-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center p-2">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $inprogress }}</div>
                                <div class="fw-semibold text-secondary">In-Progress</div>
                            </div>
                            <div class="p-1 in-progress-bg bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                                <i class="fs-3 bi bi-hourglass-split"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::INVESTIGATION->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="py-2 px-2 investigation-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center p-2">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $investigation }}</div>
                                <div class="fw-semibold text-secondary">Investigation</div>
                            </div>
                            <div class="p-1 investigation-bg bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                                <i class="fs-3 bi bi-search"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::CLOSE->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="py-2 px-2 close-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center p-2">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $close }}</div>
                                <div class="fw-semibold text-secondary">Closed</div>
                            </div>
                            <div class="p-1 close-bg bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                                <i class="fs-3 bi bi-check2-circle"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}?{{ http_build_query(['cmp_status'=> [ComplaintStatus::CANCEL->value]]) }}" target="_blank" class="text-body-secondary">
                    <div class="py-2 px-2 cancel-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center p-2">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $cancel }}</div>
                                <div class="fw-semibold text-secondary">Cancelled</div>
                            </div>
                            <div class="p-1 cancel-bg bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                                <i class="fs-3 bi bi-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{ url('calls') }}" target="_blank" class="text-body-secondary">
                    <div class="py-2 px-2 totals-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex justify-content-between align-items-center p-2">
                            <div class="text-center">
                                <div class="fs-3 fw-semibold">{{ $total }}</div>
                                <div class="fw-semibold text-secondary">Total</div>
                            </div>
                            <div class="p-1 totals-bg bg-opacity-25 rounded-4 py-2 px-3 shadow-sm">
                                <i class="fs-3 bi bi-files"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
         {{-- Reports --}}
        <div>
            <h4>Calls Reports</h4>
        </div>
        {{-- Calls Dynamic Count --}}
        <div class="row g-2 mb-2 mt-2">
            <div class="col-sm-2">
                <a href="{{ url('calls/reports/complaints') }}?tab=ga" target="_blank" class="text-body-secondary">
                    <div class="p-3 reports-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-1 reports-bg rounded-4 py-2 px-3 shadow-sm"><i class="bi bi-geo-alt fs-4"></i></div>
                            <div class="fs-6 fw-semibold text-center"><span>GA Report</span></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="{{ url('calls/reports/complaints') }}?tab=category" target="_blank" class="text-body-secondary">
                    <div class="p-3 reports-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-1 reports-bg rounded-4 py-2 px-3 shadow-sm"><i class="bi bi-list-check fs-4"></i></div>
                            <div class="fs-6 fw-semibold text-center"><span>Category Report</span></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-2">
                <a href="{{ url('calls/reports/complaints') }}?tab=deviation" target="_blank" class="text-body-secondary">
                    <div class="p-3 reports-bg border border-3 border-light shadow-sm rounded-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-1 reports-bg rounded-4 py-2 px-3 shadow-sm"><i class="bi bi-signpost-split fs-4"></i></div>
                            <div class="fs-6 fw-semibold text-center"><span>Deviaton Report</span></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>    
    </div>
@endsection
<style>
    .dpng-bg {background: #97c9e6;background: linear-gradient(180deg, rgba(151, 201, 230, 0.27) 14%, rgba(184, 136, 247, 0.38) 100%);}
    .cpng-bg {background: rgba(151, 226, 230, 0.3);background: linear-gradient(180deg, rgba(151, 226, 230, 0.35) 14%, rgba(136, 179, 247, 0.47) 100%);}
    .ipng-bg {background: #faa2a2;background: linear-gradient(180deg, rgba(250, 162, 162, 0.35) 14%, rgba(247, 72, 59, 0.47) 100%);}
    .cng-bg{background: #5fe84d;background: linear-gradient(180deg, rgba(95, 232, 77, 0.35) 14%, rgba(69, 212, 30, 0.47) 100%);}
    .general-bg{background: #e8d64d;background: linear-gradient(180deg, rgba(232, 214, 77, 0.35) 14%, rgba(205, 222, 49, 0.47) 100%);}
    .registered-bg {background: #4de876;background: linear-gradient(180deg, rgba(77, 232, 118, 0.35) 14%, rgba(121, 222, 49, 0.47) 100%);}
    .assign-bg {background: #bdb0f5;background: linear-gradient(180deg, rgba(189, 176, 245, 0.27) 29%, rgba(234, 136, 247, 0.32) 100%);}
    .in-progress-bg {background: #6fe8ce;background: linear-gradient(180deg, rgba(111, 232, 206, 0.35) 14%, rgba(36, 212, 168, 0.47) 100%); }
    .investigation-bg {background: #418df0;
background: linear-gradient(2deg, rgba(65, 141, 240, 0.3) 0%, rgba(210, 195, 250, 0.6) 100%, rgba(237, 221, 83, 0.3) 100%);}
    .close-bg {background: #f7dc79;background: linear-gradient(180deg, rgba(247, 220, 121, 0.35) 14%, rgba(81, 198, 240, 0.47) 100%);}
    .cancel-bg {background: #f77981;background: linear-gradient(180deg, rgba(247, 121, 129, 0.35) 14%, rgba(240, 81, 145, 0.47) 100%);}
    .totals-bg {background: #79e6f7;background: linear-gradient(180deg, rgba(121, 230, 247, 0.35) 14%, rgba(81, 208, 240, 0.47) 100%);}
    .reports-bg {background: #418df0;
background: linear-gradient(2deg, rgba(65, 141, 240, 0.3) 0%, rgba(210, 195, 250, 0.6) 100%, rgba(237, 221, 83, 0.3) 100%);}
</style>   
