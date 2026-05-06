{{-- Prepaid Consumers List By Districts --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">
                Conversions - 
                {{ $request_data['ga_name'] }}&nbsp;>&nbsp;({{ $request_data['conv_date_from'] }}&nbsp;To&nbsp;{{ $request_data['conv_date_to'] }})&nbsp;{{ $segment ? "> ".$segment : '' }}
            </h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Consumer sattus --}}
            <div class="mt-2">
                <div class="row row-cols-8 g-2 mb-2">
                    <div class="col-2">
                        <div class="bg-primary bg-gradient rounded text-white text-center py-1 px-2 fs-5">District</div>
                    </div>
                    <div class="col">
                        <div class="bg-success bg-gradient rounded text-white text-center py-1 px-2 fs-5">Activated</div>
                    </div>
                    <div class="col">
                        <div class="bg-warning bg-gradient rounded text-white text-center py-1 px-2 fs-5">TD</div>
                    </div>
                    <div class="col">
                        <div class="bg-danger bg-gradient rounded text-white text-center py-1 px-2 fs-5">PD</div>
                    </div>
                </div>
                @foreach($districts as $district)
                    <div class="row row-cols-10 g-2 mb-2">
                        <div class="col-2">
                            <div class="bg-primary-subtle rounded py-1 px-2 fs-5 text-truncate">{{ $district->name }}</div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                {{ $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                {{ $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::TD->value] ?? 0 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                {{ $consumer_status_counts[$district->id][\App\Enums\ConsumerStatus::PD->value] ?? 0 }}
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- Totals --}}
                <div class="row row-cols-10 g-2 mb-2">
                    <div class="col-2">
                        <div class="bg-info-subtle rounded py-1 px-2 fs-5 fw-semibold text-truncate text-end">Totals</div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            {{ $consumer_status_sum[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            {{ $consumer_status_sum[\App\Enums\ConsumerStatus::TD->value] ?? 0 }}
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            {{ $consumer_status_sum[\App\Enums\ConsumerStatus::PD->value] ?? 0 }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>