{{-- Consumer conversions with status --}}

{{-- Dsiplay --}}
<div class="mt-2">
    <div class="row row-cols-8 g-2 mb-2">
        <div class="col-3">
            <div class="bg-primary bg-gradient rounded text-white py-1 px-2 fs-5">GA</div>
        </div>
        <div class="col">
            <div class="bg-danger bg-gradient rounded text-white text-center py-1 px-2 fs-5">Target Conversions</div>
        </div>
        <div class="col">
            <div class="bg-success bg-gradient rounded text-white text-center py-1 px-2 fs-5">Total Conversions</div>
        </div>
        <div class="col">
            <div class="bg-warning bg-gradient rounded text-white text-center py-1 px-2 fs-5">TD</div>
        </div>
        <div class="col">
            <div class="bg-danger bg-gradient rounded text-white text-center py-1 px-2 fs-5">PD</div>
        </div>
        <div class="col">
            <div class="bg-info bg-gradient rounded text-white text-center py-1 px-2 fs-5">Reconnections</div>
        </div>
    </div>
    @foreach($geo_areas as $ga)
        <div class="row row-cols-10 g-2 mb-2">
            <div class="col-3">
                <div class="bg-primary-subtle rounded py-1 px-2 fs-5 text-truncate">
                    <div class="d-flex justify-content-between">
                        <span>{{ $ga->name }}</span>
                        <a href="{{ url('reports/consumer/conversions/getPrepaidCountByDistricts') }}?{{ http_build_query([
                            'ga_id' => $ga->id,
                            'ga_name' => $ga->name,
                            'conv_date_from' => request()->conv_date_from,
                            'conv_date_to' => request()->conv_date_to,
                            'conv_segment_id' => request()->conv_segment_id
                        ]) }}" class="link-modal" title="Districts Counts">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            {{-- Target Conversion --}}
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query([
                        'geo_area'=> [$ga->id], 
                        'cns_status' => [\App\Enums\ConsumerStatus::ACTIVATE->value], 
                        'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                        'segments' => [request()->conv_segment_id]
                        ]) }}" target="_blank">{{ $consumer_target_counts[$ga->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}</a>
                </div>
            </div>
            {{-- Prepaid Conversions --}}
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/conversions/prepaidConsumers') }}?{{ http_build_query([
                        'ga_id' => $ga->id,
                        'ga_name' => $ga->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'conv_segment_id' => request()->conv_segment_id,
                    ]) }}" class="link-modal">{{ $consumer_status_counts[$ga->id] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query([
                        'geo_area'=> [$ga->id], 
                        'cns_status' => [\App\Enums\ConsumerStatus::TD->value], 
                        'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                        'segments' => [request()->conv_segment_id],
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                    ]) }}" target="_blank">{{ $consumer_target_counts[$ga->id][\App\Enums\ConsumerStatus::TD->value] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query([
                        'geo_area'=> [$ga->id], 
                        'cns_status' => [\App\Enums\ConsumerStatus::PD->value], 
                        'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                        'segments' => [request()->conv_segment_id],
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                    ]) }}" target="_blank">{{ $consumer_target_counts[$ga->id][\App\Enums\ConsumerStatus::PD->value] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::RECONNECT->value, 
                        'ga_id' => $ga->id,
                        'ga_name' => $ga->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'segment_id' => request()->segment_id,
                    ]) }}" class="link-modal">{{ $reconnect_status[$ga->id] ?? 0 }}</a>
                </div>
            </div>
        </div>
    @endforeach
    {{-- Totals --}}
    <div class="row row-cols-10 g-2 mb-2">
        <div class="col-3">
            <div class="bg-info-subtle rounded py-1 px-2 fs-5 fw-semibold text-truncate text-end">Totals</div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query([
                        'cns_status' => [\App\Enums\ConsumerStatus::ACTIVATE->value], 
                        'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                        'segments' => [request()->conv_segment_id],
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                    ]) }}" target="_blank">{{ $consumer_target_sum[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ $consumer_status_sum ?? 0 }}
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query([
                        'cns_status' => [\App\Enums\ConsumerStatus::TD->value], 
                        'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                        'segments' => [request()->conv_segment_id],
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                    ]) }}" target="_blank">{{ $consumer_target_sum[\App\Enums\ConsumerStatus::TD->value] ?? 0 }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query([
                        'cns_status' => [\App\Enums\ConsumerStatus::PD->value], 
                        'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                        'segments' => [request()->conv_segment_id],
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                    ]) }}" target="_blank">{{ $consumer_target_sum[\App\Enums\ConsumerStatus::PD->value] ?? 0 }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ numberFormat(($reconnect_status ?? collect())->sum()) }}
            </div>
        </div>
    </div>
</div>
@include('scripts.link-modal')