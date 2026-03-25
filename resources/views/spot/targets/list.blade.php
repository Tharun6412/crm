{{-- Prospects Targets --}}
@extends('layouts.layout')

@section('title', 'Targets')

@section('page-title', 'Targets')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('spot/dashboard') }}">SPot</a></li>
@endsection

@section('page-content')
    <div id="targets-list" class="current-page-reload">
        <form id="target-year-form" method="GET" method="{{ url('spot/targets') }}">
            <div class="row g-2 mb-2">
                <div class="col-auto">
                    <div class="input-group">
                        <label for="target_year" class="input-group-text">Year</label>
                        <input type="text" class="form-control" placeholder="YYYY" name="target_year" id="target_year">
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success"><i class="bi bi-arrow-right-circle"></i>&nbsp;Get Report</button>
                </div>
            </div>
        </form>
        <div id="target-year-loader"></div>
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'target-year'])
    <script type="module">
        $(function(){
            $('#target_year').datepicker({format: 'yyyy', autoHide: true});
        });
    </script>
@endpush