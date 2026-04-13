<div>
    @if ($otp_details)
        <div class="table-responsive" style="min-height: 500px;">
            <table class="table table-bordered table-hover bg-white">
                <thead class="table-success">
                    <tr>
                        <th width="1%" nowrap>S.No</th>
                        <th>Mobile</th>
                        <th>OTP</th>
                        <th>Generated Date</th>
                        <th>Expired Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                    @endphp
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $otp_details->identifier }}</td>
                        <td>{{ $otp_details->otp_no ?? '' }}</td>
                        <td>{{ $otp_details->created_at->format('d-m-Y H:i') }}</td>
                        <td>{{ $otp_details->expires_at->format('d-m-Y H:i') }}</td>
                        <td>
                            @if ($otp_details->is_used == 0)
                                <span>Otp not used</span>
                            @else
                                <span>Otp used</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-info">
            No record found
        </div>
    @endif
</div>