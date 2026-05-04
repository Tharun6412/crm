{{-- Consumer sattus --}}

{{-- Dsiplay --}}
<div class="mt-2">
    <div class="row row-cols-8 g-2 mb-2">
        <div class="col-2">
            <div class="bg-success bg-gradient rounded text-white py-1 px-2 fs-5">GA</div>
        </div>
        <div class="col">
            <div class="bg-primary bg-gradient rounded text-white text-center py-1 px-2 fs-5">Activated</div>
        </div>
        <div class="col">
            <div class="bg-warning bg-gradient rounded text-white text-center py-1 px-2 fs-5">TD</div>
        </div>
        <div class="col">
            <div class="bg-danger bg-gradient rounded text-white text-center py-1 px-2 fs-5">PD</div>
        </div>
    </div>
    @foreach($geo_areas as $ga)
        <div class="row row-cols-10 g-2 mb-2">
            <div class="col-2">
                <div class="bg-success-subtle rounded py-1 px-2 fs-5 text-truncate">
                    <a href="{{ url('reports/consumer/conversions/getPrepaidCountByDistricts') }}?ga_id={{ $ga->id }}&ga_name={{ $ga->name }}&{{ http_build_query(request()->all()) }}" class="link-modal">{{ $ga->name }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/conversions/prepaidConsumers') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::ACTIVATE->value, 
                        'ga_id' => $ga->id,
                        'ga_name' => $ga->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'segment_id' => request()->segment_id,
                    ]) }}" class="link-modal">{{ $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/conversions/prepaidConsumers') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::TD->value, 
                        'ga_id' => $ga->id,
                        'ga_name' => $ga->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'segment_id' => request()->segment_id,
                    ]) }}" class="link-modal">{{ $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::TD->value] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/conversions/prepaidConsumers') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::PD->value, 
                        'ga_id' => $ga->id,
                        'ga_name' => $ga->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'segment_id' => request()->segment_id,
                    ]) }}" class="link-modal">{{ $consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::PD->value] ?? 0 }}</a>
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
@include('scripts.link-modal')