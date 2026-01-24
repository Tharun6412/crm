{{-- Dashboard --}}

@extends('layouts.layout')

@section('title', 'Customer Service')

@section('page-title', 'Customer Service')

@section('page-content')
    <div>
        @php
            //Segments Enums
            use \App\Enums\SegmentType; 
            // Complaints Enums
            use \App\Enums\ComplaintStatus;
            // Complaint Status Dynamic
            $register = $complaints[ComplaintStatus::REGISTER->value] ?? 0;
            $assign = $complaints[ComplaintStatus::ASSIGN->value] ?? 0;
            $inprogress = $complaints[ComplaintStatus::IN_PROGRESS->value] ?? 0;
            $investigation = $complaints[ComplaintStatus::INVESTIGATION->value] ?? 0;
            $close = $complaints[ComplaintStatus::CLOSE->value] ?? 0;
            $cancel = $complaints[ComplaintStatus::CANCEL->value] ?? 0;
            $total = $register+$assign+$inprogress+$investigation+$close+$cancel;
        @endphp
        <div class="row g-2 mb-3">
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>DPNG</span>
                        <span class="fs-3 fw-semibold">{{ $consumers[SegmentType::DOMESTIC->value] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>DPNG</span>
                        <span class="fs-3 fw-semibold">{{ $consumers[SegmentType::DOMESTIC->value] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>CPNG</span>
                        <span class="fs-3 fw-semibold">{{ $consumers[SegmentType::COMMERCIAL->value] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>IPNG</span>
                        <span class="fs-3 fw-semibold">{{ $consumers[SegmentType::INDUSTRIAL->value] ?? 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>CNG</span>
                        <span class="fs-3 fw-semibold">{{ $consumers[SegmentType::CNG->value] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Call status counts --}}
        <h4>Call Status</h4>
        {{-- Calls Dynamic Count --}}
        <div class="row g-2">
            <div class="col-sm-3">
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
            </div>
            <div class="col-sm-3">
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
            </div>
            <div class="col-sm-3">
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
            </div>
            <div class="col-sm-3">
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
            </div>
            <div class="col-sm-3">
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
            </div>
            <div class="col-sm-3">
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
            </div>
            <div class="col-sm-3">
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
            </div>
        </div>
    </div>
@endsection