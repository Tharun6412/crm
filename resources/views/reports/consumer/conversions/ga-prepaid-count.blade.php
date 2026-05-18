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
                    <div class="col text-nowrap">
                        <div class="bg-danger bg-gradient rounded text-white text-center py-1 px-2 fs-5">Target Conversions</div>
                    </div>
                    <div class="col text-nowrap">
                        <div class="bg-success bg-gradient rounded text-white text-center py-1 px-2 fs-5">Total Conversions</div>
                    </div>
                    <div class="col">
                        <div class="bg-warning bg-gradient rounded text-white text-center py-1 px-2 fs-5">TD</div>
                    </div>
                    <div class="col">
                        <div class="bg-danger bg-gradient rounded text-white text-center py-1 px-2 fs-5">PD</div>
                    </div>
                    <div class="col">
                        <div class="bg-success bg-gradient rounded text-white text-center py-1 px-2 fs-5">Reconnections</div>
                    </div>
                </div>
                @foreach($districts as $district)
                    <div class="row row-cols-10 g-2 mb-2">
                        <div class="col-2">
                            <div class="bg-primary-subtle rounded py-1 px-2 fs-5 text-truncate">{{ $district->name }}</div>
                        </div>
                        <div class="col text-nowrap">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query([
                                    'geo_area' => [request()->ga_id],
                                    'district' => [$district->id], 
                                    'cns_status' => [\App\Enums\ConsumerStatus::ACTIVATE->value], 
                                    'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                                    'segments' => [request()->conv_segment_id],
                                    'date_from' => request()->conv_date_from,
                                    'date_to' => request()->conv_date_to
                                ]) }}" target="_blank">{{ $consumer_target_counts[$district->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}</a>
                            </div>
                        </div>
                        <div class="col text-nowrap">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                {{ $consumer_status_counts[$district->id] ?? 0 }}
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query([
                                    'geo_area' => [request()->ga_id],
                                    'district' => [$district->id], 
                                    'cns_status' => [\App\Enums\ConsumerStatus::TD->value], 
                                    'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                                    'segments' => [request()->conv_segment_id],
                                    'date_from' => request()->conv_date_from,
                                    'date_to' => request()->conv_date_to
                                ]) }}" target="_blank">{{ $consumer_target_counts[$district->id][\App\Enums\ConsumerStatus::TD->value] ?? 0 }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                <a href="{{ url('consumers') }}?{{ http_build_query([
                                    'geo_area' => [request()->ga_id],
                                    'district' => [$district->id], 
                                    'cns_status' => [\App\Enums\ConsumerStatus::PD->value], 
                                    'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                                    'segments' => [request()->conv_segment_id],
                                    'date_from' => request()->conv_date_from,
                                    'date_to' => request()->conv_date_to
                                ]) }}" target="_blank">{{ $consumer_target_counts[$district->id][\App\Enums\ConsumerStatus::PD->value] ?? 0 }}</a>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded text-end py-1 px-2 fs-5">
                                {{ $reconnect_status[$district->id] ?? 0 }}
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
                            <a href="{{ url('consumers') }}?{{ http_build_query([
                                'geo_area'=> [request()->ga_id], 
                                'cns_status' => [\App\Enums\ConsumerStatus::ACTIVATE->value], 
                                'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                                'segments' => [request()->conv_segment_id]
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
                                'geo_area'=> [request()->ga_id], 
                                'cns_status' => [\App\Enums\ConsumerStatus::TD->value], 
                                'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                                'segments' => [request()->conv_segment_id]
                            ]) }}" target="_blank">{{ $consumer_target_sum[\App\Enums\ConsumerStatus::TD->value] ?? 0 }}</a>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">
                            <a href="{{ url('consumers') }}?{{ http_build_query([
                                'geo_area'=> [request()->ga_id], 
                                'cns_status' => [\App\Enums\ConsumerStatus::PD->value], 
                                'connection_type_id' => \App\Enums\ConnectionType::POSTPAID->value, 
                                'segments' => [request()->conv_segment_id]
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
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>