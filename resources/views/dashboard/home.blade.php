@extends('layouts.layout', ['dashboard' => 1])

@section('page-title', 'Welcome')

@section('page-content')
    <div class="dashboardBg">
        {{-- Quick search --}}
        <div class="rounded p-4 mb-3">
            <div class="row justify-content-sm-center">
                <div class="col-sm-6">
                    <h3 class="text-start">Consumer Search</h3>
                    <div class="position-relative">
                        {{-- Quick search input --}}
                        <div class="input-group input-group-lg">
                            <label for="quickSearch" class="input-group-text bg-white"><i class="bi bi-search"></i></label>
                            <input type="text" id="quickSearch" class="form-control no-focus-ring border-start-0 border-end-0" placeholder="CRN, Name, Mobile...">
                            <label for="quickSearch" class="input-group-text bg-white cursor-pointer"><i id="qs-clr" class="bi bi-x-circle d-none"></i></label>
                        </div>
                        {{-- Result --}}
                        <div id="searchResults" class="list-group position-absolute w-100" style="z-index: 1000; display:none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <h3>Dashboard</h3>
            <div class="row g-3">
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                    <div class="card con-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-4">
                            <div>
                                <h2 class="mb-0">{{ $consumer_count }}</h2>
                                <span>Total Consumers</span>
                            </div>
                            <div>
                                <div class="con-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-people-fill fs-2"></i>
                                    {{-- <i class="bi bi-person fs-2 text-primary"></i> --}}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                            <a href="{{ url('consumers') }}">All Consumers</a>
                            <a href="{{ url('consumers') }}"><is class="bi bi-eye fs-5"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                    <div class="card inv-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-4">
                            <div>
                                <h2 class="mb-0">{{ $invoice_count }}</h2>
                                <span>Invoices</span>
                            </div>
                            <div>
                                <div class="inv-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-receipt-cutoff fs-2"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                            <a href="">All Invoices</a>
                            <a href=""><is class="bi bi-eye fs-5"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                    <div class="card py-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-4">
                            <div>
                                <h2 class="mb-0">{{ $payments_count }}</h2>
                                <span>Payments</span>
                            </div>
                            <div>
                                <div class="py-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-currency-rupee fs-2"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                            <a href="">All Payments</a>
                            <a href=""><is class="bi bi-eye fs-5"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 col-md-4 col-lg-4 col-xl-3">
                    <div class="card cls-card-bg p-2 text-dark bg-opacity-10 border-3 border-light">
                        <div class="d-flex justify-content-between p-4">
                            <div>
                                <h2 class="mb-0">{{ $calls_list }}</h2>
                                <span>Consumer Calls</span>
                            </div>
                            <div>
                                <div class="cls-card-bg p-2 bg-opacity-25 rounded-4 py-2 px-3">
                                    <i class="bi bi-headset fs-2"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between py-1 px-2 rounded-bottom">
                            <a href="{{ url('calls') }}">All calls</a>
                            <a href="{{ url('calls') }}"><is class="bi bi-eye fs-5"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.quick-search')
@endpush
<style>
.dashboardBg {
    background: #a7c3f2;
    background: linear-gradient(180deg, rgba(167, 195, 242, 0.3) 0%, rgba(208, 208, 214, 0.37) 100%);
    background-position: center;
    background-repeat: no-repeat;
    min-height: 100%;
    border-radius: 12px 12px;
}
.con-card-bg {
    background: #c9aeee;
    background: linear-gradient(180deg, rgba(201, 174, 238, 0.31) 0%, rgba(148, 233, 226, 0.37) 100%);
    box-shadow: #dadada 3px 4px 10px 1px;
    text-transform: uppercase;
}
.inv-card-bg {
background: #f7f1c1;
background: linear-gradient(180deg, rgba(247, 241, 193, 0.3) 0%, rgba(144, 222, 193, 0.57) 100%);
    box-shadow: #dadada 3px 4px 10px 1px;
    text-transform: uppercase;
}
.py-card-bg {
    background: #c7542a;
    background: linear-gradient(181deg, rgba(199, 84, 42, 0.14) 32%, rgba(126, 229, 247, 0.45) 100%);
    box-shadow: #dadada 3px 4px 10px 1px;
    text-transform: uppercase;
}
.cls-card-bg {
background: #EEAECA;
background: linear-gradient(180deg, rgba(238, 174, 202, 0.31) 0%, rgba(148, 187, 233, 0.37) 100%);
    box-shadow: #dadada 3px 4px 10px 1px;
    text-transform: uppercase;
}
#searchResults .list-group-item:hover {
    background-color: hsl(210, 10%, 88%)!important;
}
</style>