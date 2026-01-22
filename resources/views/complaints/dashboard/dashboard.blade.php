{{-- Dashboard --}}

@extends('layouts.layout')

@section('title', 'Customer Service')

@section('page-title', 'Customer Service')

@section('page-content')
    <div>
        <div class="row g-2 mb-3">
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>DPNG</span>
                        <span class="fs-3 fw-semibold">100</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>DPNG</span>
                        <span class="fs-3 fw-semibold">100</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>CPNG</span>
                        <span class="fs-3 fw-semibold">100</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>IPNG</span>
                        <span class="fs-3 fw-semibold">100</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border border-primary rounded p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>CNG</span>
                        <span class="fs-3 fw-semibold">100</span>
                    </div>
                </div>
            </div>
        </div>
        {{-- Call status counts --}}
        <h4>Call Status</h4>
        <div class="row g-2">
            <div class="col-sm-3">
                <div class="border border-warning text-warning rounded py-2 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-center">
                            <div class="fs-3 fw-semibold">100</div>
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
                            <div class="fs-3 fw-semibold">100</div>
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
                            <div class="fs-3 fw-semibold">100</div>
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
                            <div class="fs-3 fw-semibold">100</div>
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
                            <div class="fs-3 fw-semibold">100</div>
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
                            <div class="fs-3 fw-semibold">100</div>
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
                            <div class="fs-3 fw-semibold">100</div>
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