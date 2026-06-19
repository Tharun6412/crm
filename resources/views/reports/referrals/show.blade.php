{{-- Employee collection show details --}}

<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Referral Details</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div><h4>Referrer</h4></div>
            <x-consumer.basic-details :consumer="$referral->consumer" class="bg-warning-subtle" />
            <div>
                <div class="p-1">
                    <h4>Referral Connections</h4>
                    <div class="row g-2 pb-2 mb-2 p-2 bg-info-subtle border rounded-2 border-info">
                        <div class="col-sm-2 text-end fw-semibold">Referral Name : </div>
                        <div class="col-sm-4">{{ $referral->name }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Referred Date : </div>
                        <div class="col-sm-4">{{ dateFormat($referral->created_at) }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Referral Mobile : </div>
                        <div class="col-sm-4">{{ maskNumber($referral->phone) }}</div>
                        <div class="col-sm-2 text-end fw-semibold">Total Referrer Earned : </div>
                        <div class="col-sm-4">{{ numberFormat($referral->referralConsumers->where('referrer_redeem_status',0)->sum('referrer_amount') ,2) }}</div>
                        <div class="col-sm-2 text-end fw-semibold"></div>
                        <div class="col-sm-4"></div>
                        <div class="col-sm-2 text-end fw-semibold">Total Referral Earned : </div>
                        <div class="col-sm-4">{{ numberFormat($referral->referralConsumers->where('referral_redeem_status',0)->sum('referral_amount'),2) }}</div>
                    </div>
                </div>
                <div>
                    <table class="table table-bordered table-hover table-success fs-sm align-middle" id="emp-clcn-dtls">
                        <thead class="table-success align-middle">
                            <tr>
                                <th rowspan="2" width="1%" nowrap>S No</th>
                                <th rowspan="2">CRN</th>
                                <th rowspan="2">Name</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2">Referral Status</th>
                                <th colspan="3" class="text-center">Referral</th>
                                <th colspan="3" class="text-center">Referrer</th>
                            </tr>
                            <tr>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $li = 1;
                            @endphp
                            @foreach ($referral->referralConsumers as $consumer)
                                <tr>
                                    <td>{{ $li++ }}</td>
                                    <td><a href="{{ url('consumers/'.$consumer->consumer->id) }}" target="_blank">{{ $consumer->consumer->crn ?? $consumer->consumer->t_crn}}</a></td>
                                    <td>{{ $consumer->consumer->name ?? '' }}</td>
                                    <td><x-consumer.status :status="$consumer->consumer->status" /></td>
                                    <td><x-referrals.status :status="$consumer->status" /></td>
                                    <td class="text-end">{{ numberFormat($consumer->referral_amount ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge text-bg-{{ $consumer->referral_redeem_status == 0 ? 'success' : 'danger' }} w-100">
                                            {{ $consumer->referral_redeem_status == 0 ? 'Credited' : 'Not Credited' }}
                                        </span>
                                    </td>
                                    <td>{{ ($consumer->referral_redeem_date) ? dateFormat($consumer->referral_redeem_date) : "" }}</td>
                                    <td class="text-end">{{ numberFormat($consumer->referrer_amount ?? 0, 2) }}</td>
                                    <td>
                                        <span class="badge text-bg-{{ $consumer->referrer_redeem_status == 0 ? 'success' : 'danger' }} w-100">
                                            {{ $consumer->referrer_redeem_status == 0 ? 'Credited' : 'Not Credited' }}
                                        </span>
                                    </td>
                                    <td>{{ ($consumer->referrer_redeem_date) ? dateFormat($consumer->referrer_redeem_date) : "" }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- <tfoot class="fw-semibold">
                            <tr>
                                <td colspan="7" class="text-end">Total Referral Amount</td>
                                <td class="text-end">{{ numberFormat($consumer->sum('referral_redeem_amount') , 2) }}</td>
                            </tr>
                        </tfoot> --}}
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>
@include('scripts.export-table', [
    'table' => 'emp-clcn-dtls',
    'button' => 'exportClnBtn',
    'tabBased' => false,
    'filename' => 'employee-collection-details',
    'sheet'    => 'Report',
])