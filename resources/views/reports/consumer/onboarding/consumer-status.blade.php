{{-- Consumer sattus --}}

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
        <div class="col">
            <div class="bg-primary bg-gradient rounded text-white text-center py-1 px-2 fs-5">Total</div>
        </div>
    </div>
    @foreach($geo_areas as $ga)
        <div class="row row-cols-10 g-2 mb-2">
            <div class="col-2">
                <div class="bg-body-secondary rounded py-1 px-2 fs-5 text-truncate">
                    <div class="d-flex justify-content-between">
                        <span>{{ $ga->name }}</span>
                        <a href="{{ url('reports/consumer/onboarding/getCountByDistricts') }}?ga_id={{ $ga->id }}&ga_name={{ $ga->name }}&{{ http_build_query(request()->all()) }}" class="link-modal" title="Districts Counts">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::PRE_REGISTER->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::REGISTER->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::REGISTER->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::ACCEPT->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::EXECUTE->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::HSC->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::HSC->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::ACTIVATE->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::TD->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::TD->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::PD->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::PD->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'cns_status' => [\App\Enums\ConsumerStatus::REJECT->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_counts[$ga->id][\App\Enums\ConsumerStatus::REJECT->value] ?? 0) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('consumers') }}?{{ http_build_query(['geo_area'=> [$ga->id], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat(array_sum($consumer_status_counts[$ga->id] ?? [])) }}</a>
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
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::PRE_REGISTER->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::REGISTER->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::ACCEPT->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::EXECUTE->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::HSC->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::HSC->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::ACTIVATE->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::TD->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::TD->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::PD->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::PD->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['cns_status' => [\App\Enums\ConsumerStatus::REJECT->value], 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat($consumer_status_sum[\App\Enums\ConsumerStatus::REJECT->value] ?? 0) }}</a>
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                <a href="{{ url('consumers') }}?{{ http_build_query(['connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" target="_blank">{{ numberFormat(array_sum($consumer_status_sum ?? [])) }}</a>
            </div>
        </div>
    </div>
</div>
@include('scripts.link-modal')