{{-- Employee collection show details --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $ga_name }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @php
                $total = array_sum($district_count->toArray());
            @endphp
            <h5>Total Consumers : {{ $total }}</h5>
            <div class="row">
                @foreach ($districts as $district)
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-center">
                                <h6 class="card-title mb-2">
                                    {{ $district->name }}
                                </h6>
                                <span class="badge bg-primary fs-6">
                                    {{ $district_count[$district->id] ?? 0 }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.export-table', [
    'table' => 'emp-clcn-dtls',
    'button' => 'exportClnBtn',
    'tabBased' => false,
    'filename' => 'employee-collection-details',
    'sheet'    => 'Report',
])