{{-- Invoice landing page --}}

@extends('layouts.layout')

@section('title', 'Invoice Details')

@section('page-title', 'Invoice Details')

@section('page-content')
    {{-- Quick search --}}
    <div>
        <form action="{{ url('bill/invoice/search') }}" id="invoice-search-form" method="GET">
            <div class="rounded bg-info-subtle p-3 mb-3">
                <div class="row justify-content-sm-center">
                    <div class="col-sm-6">
                        <h3 class="text-center">Invoice Search</h3>
                        <div class="position-relative">
                            {{-- Quick search input --}}
                            <div class="input-group input-group-lg">
                                <label for="quickSearch" class="input-group-text bg-white"><i class="bi bi-search"></i></label>
                                <input type="text" name="search" id="search" class="form-control no-focus-ring border-start-0 border-end-0" placeholder="CRN, Invoice Number..." value="{{ request()->search }}">
                                <label for="quickSearch" class="input-group-text bg-white cursor-pointer"><i id="qs-clr" class="bi bi-x-circle d-none"></i></label>
                                <button type="submit" class="btn btn-success">GO</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div id="invoice-list" class="current-page-reload">
        </div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-form-search', ['form' => 'invoice'])
@endpush