<div class="mb-2 text-end">
    <!-- Export -->
    <button type="button" id="exportBtn" class="btn btn-outline-info"><i class="bi bi-file-earmark-excel"></i>&nbsp;Export</button>
</div>
<div class="table-responsive mt-2" id="employee_progress_table">
    <table class="table table-bordered table-striped table-hover">
        <thead class='table-success'>
            <tr class="text-center">
                <th class="text-center">S.No</th>
                <th class="text-start">Employee</th>
                <th>TR</th>
                <th>Registered</th>
                <th>Accept</th>
                <th>Executed</th>
                <th>HSC</th>
                <th>Activated</th>
                <th>TD</th>
                <th>PD</th>
                <th>Rejected</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user_roles as $userId => $userName)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $userName }}</td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                            'status_id' => \App\Enums\ConsumerStatus::PRE_REGISTER->value,
                            'user_id' => $userId,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0 }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                             'user_id' => $userId,
                            'status_id' => \App\Enums\ConsumerStatus::REGISTER->value,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::REGISTER->value] ?? 0 }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                             'user_id' => $userId,
                            'status_id' => \App\Enums\ConsumerStatus::ACCEPT->value,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                           
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0 }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                            'user_id' => $userId,
                            'status_id' => \App\Enums\ConsumerStatus::EXECUTE->value,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0 }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                             'user_id' => $userId,
                            'status_id' => \App\Enums\ConsumerStatus::HSC->value,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::HSC->value] ?? 0 }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                             'user_id' => $userId,
                            'status_id' => \App\Enums\ConsumerStatus::ACTIVATE->value,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                             'user_id' => $userId,
                            'status_id' => \App\Enums\ConsumerStatus::TD->value,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::TD->value] ?? 0 }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                             'user_id' => $userId,
                            'status_id' => \App\Enums\ConsumerStatus::PD->value,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::PD->value] ?? 0 }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ url('reports/consumer/onboardingStatusReport') }}?{{ http_build_query([
                             'user_id' => $userId,
                            'status_id' => \App\Enums\ConsumerStatus::REJECT->value,
                            'ga_id' => $geo_areas->id,
                            'ga_name' => $geo_areas->name,
                            'date_from' => request()->conv_date_from,
                            'date_to' => request()->conv_date_to,
                            'connection_type_id' => request()->connection_type_id,
                            'segment_id' => request()->segment_id,
                            'status_date' => request()->status_date,
                        ]) }}" class="link-modal fs-5">
                            {{ $users_data[$userId][\App\Enums\ConsumerStatus::REJECT->value] ?? 0 }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-info fw-bold fs-5">
                <td colspan="2" class="text-end">Total Counts</td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::PRE_REGISTER->value] ?? 0 }}
                </td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::REGISTER->value] ?? 0 }}
                </td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::ACCEPT->value] ?? 0 }}
                </td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::EXECUTE->value] ?? 0 }}
                </td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::HSC->value] ?? 0 }}
                </td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::ACTIVATE->value] ?? 0 }}
                </td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::TD->value] ?? 0 }}
                </td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::PD->value] ?? 0 }}
                </td>
                <td class="text-center">
                    {{ $users_sum[\App\Enums\ConsumerStatus::REJECT->value] ?? 0 }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
@include('scripts.link-modal')
@include('scripts.export-table', [
    'table' => 'employee_progress_table',
    'button' => 'exportBtn',
    'tabBased' => false,
    'filename' => 'employee_progress_report',
    'sheet'    => 'Report',
])