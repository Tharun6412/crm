{{-- Consumer sattus --}}

<div class="mt-2">
    <div class="row row-cols-8 g-2 mb-2">
        <div class="col-2">
            <div class="bg-success bg-gradient rounded text-white py-1 px-2 fs-5">GA</div>
        </div>
        <div class="col-2">
            <div class="bg-info bg-gradient rounded text-white text-end py-1 px-3 fs-5 text-nowrap">Approve</div>
        </div>
        <div class="col-2">
            <div class="bg-dark bg-gradient rounded text-white text-end py-1 px-3 fs-5">Execute</div>
        </div>
        <div class="col-2">
            <div class="text-bg-purple bg-gradient rounded text-white text-end py-1 px-3 fs-5">Gassify</div>
        </div>
        <div class="col-2">
            <div class="bg-success bg-gradient rounded text-white text-end py-1 px-3 fs-5">Activate</div>
        </div>
        <div class="col">
            <div class="bg-primary bg-gradient rounded text-white text-end py-1 px-3 fs-5">Total</div>
        </div>
    </div>
    @php
        $total_wait_sum = 0;
    @endphp
    @foreach($geo_areas as $ga)
        @php
            // Consumer Waiting Status Count
            $accept = $consumer_wait_list[$ga->id][\App\Enums\ConsumerStatus::REGISTER->value] ?? 0;
            $execute = $consumer_wait_list[$ga->id][\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0;
            $hsc = $consumer_wait_list[$ga->id][\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0;
            $activate = $consumer_wait_list[$ga->id][\App\Enums\ConsumerStatus::HSC->value] ?? 0;
            $total_wait_list = $accept + $execute + $hsc + $activate;
            $total_wait_sum += $total_wait_list;
        @endphp
        <div class="row row-cols-10 g-2 mb-2">
            <div class="col-2">
                <div class="bg-body-secondary rounded py-1 px-2 fs-5 text-truncate">
                    <div class="d-flex justify-content-between">
                        <span class="fs-5">{{ $ga->name }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $ga->id, 'cns_status' => \App\Enums\ConsumerStatus::REGISTER->value, 'connection_type_id' => request()->connect_type_id, 'segments' => request()->onboard_segment_id]) }}" class="link-modal text-start">{{ numberFormat($accept) }}</a>&nbsp;&nbsp;
                    <a type="button" href="{{ url('reports/consumer/connectionProgress/ageingProgress') }}?{{ http_build_query(['ga_id'=> $ga->id,'status_name' => 'Register', 'cns_status' => \App\Enums\ConsumerStatus::REGISTER->value, 'connection_type_id' => request()->connect_type_id, 'segments' => request()->onboard_segment_id]) }}" class="link-canvas text-end" title="Click to view Ageing Report"><i class="bi bi-box-arrow-right fs-4"></i></a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $ga->id, 'cns_status' => \App\Enums\ConsumerStatus::ACCEPT->value, 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" class="link-modal">{{ numberFormat($execute) }}</a>&nbsp;&nbsp;
                    <a type="button" href="{{ url('reports/consumer/connectionProgress/ageingProgress') }}?{{ http_build_query(['ga_id'=> $ga->id,'status_name' => 'Accept', 'cns_status' => \App\Enums\ConsumerStatus::ACCEPT->value, 'connection_type_id' => request()->connect_type_id, 'segments' => request()->onboard_segment_id]) }}" class="link-canvas text-end" title="Click to view Ageing Report"><i class="bi bi-box-arrow-right fs-4"></i></a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $ga->id, 'cns_status' => \App\Enums\ConsumerStatus::EXECUTE->value, 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" class="link-modal">{{ numberFormat($hsc) }}</a>&nbsp;&nbsp;
                    <a type="button" href="{{ url('reports/consumer/connectionProgress/ageingProgress') }}?{{ http_build_query(['ga_id'=> $ga->id,'status_name' => 'Execute', 'cns_status' => \App\Enums\ConsumerStatus::EXECUTE->value, 'connection_type_id' => request()->connect_type_id, 'segments' => request()->onboard_segment_id]) }}" class="link-canvas text-end" title="Click to view Ageing Report"><i class="bi bi-box-arrow-right fs-4"></i></a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $ga->id, 'cns_status' => \App\Enums\ConsumerStatus::HSC->value, 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" class="link-modal">{{ numberFormat($activate) }}</a>&nbsp;&nbsp;
                    <a type="button" href="{{ url('reports/consumer/connectionProgress/ageingProgress') }}?{{ http_build_query(['ga_id'=> $ga->id,'status_name' => 'HSC', 'cns_status' => \App\Enums\ConsumerStatus::HSC->value, 'connection_type_id' => request()->connect_type_id, 'segments' => request()->onboard_segment_id]) }}" class="link-canvas text-end" title="Click to view Ageing Report"><i class="bi bi-box-arrow-right fs-4"></i></a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">{{ numberFormat($total_wait_list) }}</div>
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
                {{ numberFormat($consumer_wait_sum[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0) }}
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ numberFormat($consumer_wait_sum[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0) }}
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ numberFormat($consumer_wait_sum[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0) }}
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                {{ numberFormat($consumer_wait_sum[\App\Enums\ConsumerStatus::HSC->value] ?? 0) }}
            </div>
        </div>
        <div class="col">
            <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ numberFormat($total_wait_sum) }}</div>
        </div>
    </div>
</div>
@include('scripts.link-modal')
@include('scripts.link-canvas')