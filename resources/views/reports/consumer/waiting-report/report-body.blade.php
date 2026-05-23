{{-- Consumer sattus --}}

<div class="mt-2">
    <div class="row row-cols-8 g-2 mb-2">
        <div class="col-2">
            <div class="bg-success bg-gradient rounded text-white py-1 px-2 fs-5">GA</div>
        </div>
        <div class="col-2">
            <div class="bg-info bg-gradient rounded text-white text-center py-1 px-2 fs-5 text-nowrap">Waiting for Accepatance</div>
        </div>
        <div class="col-2">
            <div class="bg-dark bg-gradient rounded text-white text-center py-1 px-2 fs-5">Waiting for Execution</div>
        </div>
        <div class="col-2">
            <div class="text-bg-purple bg-gradient rounded text-white text-center py-1 px-2 fs-5">Waiting for HSC</div>
        </div>
        <div class="col-2">
            <div class="bg-success bg-gradient rounded text-white text-center py-1 px-2 fs-5">Waiting for Activation</div>
        </div>
        <div class="col">
            <div class="bg-primary bg-gradient rounded text-white text-center py-1 px-2 fs-5">Total</div>
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
                        <span>{{ $ga->name }}</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $ga->id, 'cns_status' => \App\Enums\ConsumerStatus::REGISTER->value, 'connection_type_id' => request()->connect_type_id, 'segments' => request()->onboard_segment_id]) }}" class="link-modal">{{ numberFormat($accept) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $ga->id, 'cns_status' => \App\Enums\ConsumerStatus::ACCEPT->value, 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" class="link-modal">{{ numberFormat($execute) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $ga->id, 'cns_status' => \App\Enums\ConsumerStatus::EXECUTE->value, 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" class="link-modal">{{ numberFormat($hsc) }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/waiting/consumersListForEmployees') }}?{{ http_build_query(['ga_id'=> $ga->id, 'cns_status' => \App\Enums\ConsumerStatus::HSC->value, 'connection_type_id' => request()->connect_type_id, 'segments' => [request()->onboard_segment_id]]) }}" class="link-modal">{{ numberFormat($activate) }}</a>
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