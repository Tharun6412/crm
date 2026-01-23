@extends('layouts.layout', ['dashboard' => 1])

@section('page-title', 'Welcome')

@section('page-content')
    <div>
        {{-- Quick search --}}
        <div class="rounded bg-info-subtle p-3 mb-3">
            <div class="row justify-content-sm-center">
                <div class="col-sm-6">
                    <h3 class="text-center">Consumer Search</h3>
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
                <div class="col-sm-3">
                    <div class="card">
                        <div class="d-flex justify-content-between p-4">
                            <div>
                                <h2 class="mb-0">{{ $consumer_count }}</h2>
                                <span>Consumer</span>
                            </div>
                            <div>
                                <div class="bg-light rounded-4 py-2 px-3">
                                    <i class="bi bi-person fs-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between bg-light py-1 px-2 rounded-bottom">
                            <a href="{{ url('consumers') }}">All Consumers</a>
                            <a href="{{ url('consumers') }}"><is class="bi bi-arrow-right-circle-fill"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="d-flex justify-content-between p-4">
                            <div>
                                <h2 class="mb-0">{{ $invoice_count }}</h2>
                                <span>Invoices</span>
                            </div>
                            <div>
                                <div class="bg-light rounded-4 py-2 px-3">
                                    <i class="bi bi-upc-scan fs-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between bg-light py-1 px-2 rounded-bottom">
                            <a href="">All Invoices</a>
                            <a href=""><is class="bi bi-arrow-right-circle-fill"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="d-flex justify-content-between p-4">
                            <div>
                                <h2 class="mb-0">{{ $payments_count }}</h2>
                                <span>Payments</span>
                            </div>
                            <div>
                                <div class="bg-light rounded-4 py-2 px-3">
                                    <i class="bi bi-transparency fs-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between bg-light py-1 px-2 rounded-bottom">
                            <a href="">All Payments</a>
                            <a href=""><is class="bi bi-arrow-right-circle-fill"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="d-flex justify-content-between p-4">
                            <div>
                                <h2 class="mb-0">{{ $calls_list }}</h2>
                                <span>Consumer Calls</span>
                            </div>
                            <div>
                                <div class="bg-light rounded-4 py-2 px-3">
                                    <i class="bi bi-tsunami fs-2 text-primary"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between bg-light py-1 px-2 rounded-bottom">
                            <a href="{{ url('calls') }}">All calls</a>
                            <a href="{{ url('calls') }}"><is class="bi bi-arrow-right-circle-fill"></i></a>
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
