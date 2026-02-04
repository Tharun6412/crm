{{-- Consumer sattus --}}

{{-- Dsiplay --}}
<div class="mt-2">
    <div class="row row-cols-8 g-2 mb-2">
        <div class="col-2">
            <div class="bg-success bg-gradient rounded text-white py-1 px-2 fs-5">GA</div>
        </div>
        <div class="col">
            <div class="text-bg-yellow bg-gradient rounded text-danger text-center py-1 px-2 fs-5">TR</div>
        </div>
        <div class="col">
            <div class="bg-primary bg-gradient rounded text-white text-center py-1 px-2 fs-5">Registered</div>
        </div>
        <div class="col">
            <div class="bg-info bg-gradient rounded text-white text-center py-1 px-2 fs-5">Verified</div>
        </div>
        <div class="col">
            <div class="bg-dark bg-gradient rounded text-white text-center py-1 px-2 fs-5">Executed</div>
        </div>
        <div class="col">
            <div class="text-bg-purple bg-gradient rounded text-white text-center py-1 px-2 fs-5">HSC</div>
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
        <div class="col">
            <div class="bg-secondary bg-gradient rounded text-white text-center py-1 px-2 fs-5">Rejected</div>
        </div>
    </div>
    @foreach($geo_areas as $ga)
        <div class="row row-cols-10 g-2 mb-2">
            <div class="col-2">
                <div class="bg-success-subtle rounded py-1 px-2 fs-5 text-truncate">{{ $ga->name }}</div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0 }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::REGISTER->value] ?? 0 }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0 }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0 }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::HSC->value] ?? 0 }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::TD->value] ?? 0 }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::PD->value] ?? 0 }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    {{ $consumer_status_data[$ga->id][\App\Enums\ConsumerStatus::REJECT->value] ?? 0 }}
                </div>
            </div>
        </div>
    @endforeach
    {{-- Totals --}}
    <div class="row row-cols-10 g-2 mb-2">
        <div class="col-2">
            <div class="bg-success-subtle rounded py-1 px-2 fs-5 fw-semibold text-truncate">Totals</div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::HSC->value] ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::TD->value] ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::PD->value] ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-light border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $status_sum[\App\Enums\ConsumerStatus::REJECT->value] ?? 0 }}
            </div>
        </div>
    </div>
</div>