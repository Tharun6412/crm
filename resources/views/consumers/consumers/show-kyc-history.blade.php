<div class="mt-1">
    <div class="bg-primary-subtle p-2 fs-5 fw-semibold">
        <i class="bi bi-speedometer2"></i>&nbsp;History Details
    </div>
    <div class="p-2">
        @if($kyc_details->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-primary">
                    <thead>
                        <tr class="bg-primary-subtle">
                            <th width="1%">S.No</th>
                            <th>Name</th>
                            <th>Relation</th>
                            <th>Email</th>
                            <th>Aadhar</th>
                            <th>Phone</th>
                            <th>Alternate Phone</th>
                            <th>Nominee</th>
                            <th>Nominee Relation</th>
                            <th>Added By</th>
                            <th>Added Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kyc_details as $kyc)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $kyc->name }}</td>
                                <td><small>{{ $kyc->cofDisplay->name }}</small>&nbsp;{{ $kyc->cof_name }}</td>
                                <td>{{ $kyc->email }}</td>
                                <td>{{ maskNumber($kyc->aadhar) }}</td>
                                <td>{{ maskNumber($kyc->phone) }}</td>
                                <td>{{ maskNumber($kyc->phone_alt) }}</td>
                                <td>{{ $kyc->nominee }}</td>
                                <td>{{ $kyc->nomineeRelation->name }}</td>
                                <td>{{ $kyc->createdBy?->name }}</td>
                                <td>{{ $kyc->created_at?->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">
                No records found!
            </div>
        @endif
    </div>
</div>