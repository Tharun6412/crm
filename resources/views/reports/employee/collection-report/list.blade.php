{{-- Employee Collection Report --}}

<div class="p-2">
    <form action="{{ url('reports/employee/collection') }}" id="employee-report-form">
        <div class="row g-2">
            <div class="col-auto">
                <div id="tar-div">
                    <div class="input-group">
                        <label for="date_from" class="input-group-text"><i class="bi bi-calendar3"></i>&nbsp;From</label>
                        <input type="text" aria-label="From Date" class="form-control" name="date_from" id="date_from" placeholder="DD-MM-YYYY">  
                        <label for="date_to" class="input-group-text"><i class="bi bi-calendar3"></i>&nbsp;To</label>
                        <input type="text" aria-label="To Date" class="form-control" name="date_to" id="date_to" placeholder="DD-MM-YYYY">
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <label class="input-group-text">Geo Area</label>
                    <select class="form-select" name="ga_id" id="ga_id">
                        <option value="">select</option>
                        @foreach ($geo_areas as $ga)
                            <option value="{{ $ga->id }}">{{ $ga->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-arrow-right-circle"></i>&nbsp;Get Report
                </button>
            </div>
            <div class="col-auto">
                <a href="{{ url('reports/employee/collection') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
            </div>
            <div class="col-auto">
                <!-- Export -->
                <button type="button" id="exportBtn" class="btn btn-outline-info text-end"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
            </div>                
        </div>
    </form>
    <div id="employee-report-loader" class="mt-3">
        <div class="alert alert-info text-center">Pick Dates to Generate Report</div>
    </div>
</div>

{{-- Scripts --}}
@push('scripts')
    @include('scripts.ajax-get-form-submit', ['form' => 'employee-report'])
    @include('scripts.datepicker', ['list' => ['date_from', 'date_to']])
    <script>
        $(function(){
            $('input[name="filter_name"]').change(function() {
                $('#tar-div').toggle($(this).val() === "show");
            });
        });
    </script>
@endpush