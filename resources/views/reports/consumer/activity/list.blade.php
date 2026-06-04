<div class="mt-2">
    <div class="row row-cols-10 g-2 mb-2">
        <div class="col-2">
            <div class="bg-success bg-gradient rounded text-white py-1 px-2 fs-5">
                Employee
            </div>
        </div>

        <div class="col">
            <div class="text-bg-yellow rounded text-danger text-center py-1 px-2 fs-5">TR</div>
        </div>

        <div class="col">
            <div class="bg-primary rounded text-white text-center py-1 px-2 fs-5">Registered</div>
        </div>

        <div class="col">
            <div class="bg-info rounded text-white text-center py-1 px-2 fs-5">Verified</div>
        </div>

        <div class="col">
            <div class="bg-dark rounded text-white text-center py-1 px-2 fs-5">Executed</div>
        </div>

        <div class="col">
            <div class="text-bg-purple rounded text-white text-center py-1 px-2 fs-5">HSC</div>
        </div>

        <div class="col">
            <div class="bg-success rounded text-white text-center py-1 px-2 fs-5">Activated</div>
        </div>

        <div class="col">
            <div class="bg-warning rounded text-white text-center py-1 px-2 fs-5">TD</div>
        </div>

        <div class="col">
            <div class="bg-danger rounded text-white text-center py-1 px-2 fs-5">PD</div>
        </div>

        <div class="col">
            <div class="bg-secondary rounded text-white text-center py-1 px-2 fs-5">Rejected</div>
        </div>
    </div>
     
    @foreach($user_roles as $user)
        <div class="row row-cols-10 g-2 mb-2">
            <div class="col-2">
                <div class="bg-success-subtle rounded py-1 px-2 fs-5 text-truncate">
                    {{ $user->name }}
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::PRE_REGISTER->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::REGISTER->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::REGISTER->value] ?? 0 }}</a>
                </div>
            </div>
             <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::ACCEPT->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0 }}</a>
                </div>
            </div>
             <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::EXECUTE->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0 }}</a>
                </div>
            </div>
             <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::HSC->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::HSC->value] ?? 0 }}</a>
                </div>
            </div>
             <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::ACTIVATE->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::TD->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::TD->value] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::PD->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::PD->value] ?? 0 }}</a>
                </div>
            </div>
            <div class="col">
                <div class="border rounded text-end py-1 px-2 fs-5">
                    <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                        'status_id' => \App\Enums\ConsumerStatus::REJECT->value, 
                        'ga_id' => $geo_areas->id,
                        'ga_name' => $geo_areas->name,
                        'date_from' => request()->conv_date_from,
                        'date_to' => request()->conv_date_to,
                        'connection_type_id' => request()->connection_type_id,
                        'segment_id' => request()->segment_id,
                        'status_date' => request()->status_date,
                    ]) }}" class="link-modal">{{ $users_data[$user->id][\App\Enums\ConsumerStatus::REJECT->value] ?? 0 }}</a>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Totals  --}}
    <div class="row row-cols-10 g-2 mb-2">
        <div class="col-2">
            <div class="bg-info-subtle rounded py-1 px-2 fs-5 fw-semibold text-end">
                Totals
            </div>
        </div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0 }}</div></div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0 }}</div></div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0 }}</div></div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0 }}</div></div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::HSC->value] ?? 0 }}</div></div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}</div></div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::TD->value] ?? 0 }}</div></div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::PD->value] ?? 0 }}</div></div>

        <div class="col"><div class="bg-info-subtle border rounded text-end py-1 px-2 fs-5 fw-semibold">{{ $users_sum[\App\Enums\ConsumerStatus::REJECT->value] ?? 0 }}</div></div>
    </div> 
</div>
@include('scripts.link-modal')