{{-- PNGRB Application Details --}}
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">
                PNGRB Application Details - {{ $application->applicationNumber }}
            </h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h4>Consumer Details</h4>
            <table class="table table-bordered fs-sm">
                <tr>
                    <td class="bg-light text-end" width="35%">Application Number</td>
                    <td>{{ $application->applicationNumber }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">EKYC Status</td>
                    <td>{{ $application->ekycStatus }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Serviceability Status</td>
                    <td>{{ $application->serviceabilityStatus }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Name</td>
                    <td>{{ $application->name }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Date Of Birth</td>
                    <td>{{ dateFormat($application->dob) }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Mobile</td>
                    <td>{{ $application->mobileNumber }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Whatsapp</td>
                    <td>{{ $application->whatsapp }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Email</td>
                    <td>{{ $application->email }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Address</td>
                    <td>{{ $application->houseNo }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">District</td>
                    <td>{{ $application->district }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">State</td>
                    <td>{{ $application->state }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Pincode</td>
                    <td>{{ $application->pincode }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Lat, Long</td>
                    <td>
                        <a href="https://www.google.com/maps/place/{{ $application->latitude .', '. $application->longitude }}" target="_blank">
                            {{ $application->latitude }}, {{ $application->longitude }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Status</td>
                    <td>{{ $application->status }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Created Date</td>
                    <td>{{ $application->created_at?->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Response Code</td>
                    <td>{{ $application->response_code }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Response Message</td>
                    <td>{{ $application->response_message }}</td>
                </tr>
            </table>
            <h4>Status Information</h4>
            <table class="table table-bordered">
                <tr>
                    <td class="bg-light text-end" width="35%">Update Status</td>
                    <td>{{ $application->applicationStatus }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Remarks</td>
                    <td>{{ $application->statusRemarks }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Documents</td>
                    <td>{{ $application->reviewedDocuments }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Update By</td>
                    <td>{{ $application->updatedBy }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Update At</td>
                    <td>{{ $application->updatedAt }}</td>
                </tr>
                <tr>
                    <td class="bg-light text-end">Response Code</td>
                    <td>{{ $application->update_response_code }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
        </div>
    </div>
</div>