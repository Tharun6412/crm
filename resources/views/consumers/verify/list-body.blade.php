
<div class="d-flex justify-content-between mt-2">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <div class="input-group">
                <span class="input-group-text" id="search-key">Search</span>
                <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}" placeholder="Search CRN">
            </div>
        </div>
        <div class="col-auto">
            <x-consumer.verify-steps-filter />
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('reports/consumer/verify') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-1">
           <span class="fw-semibold">({{ numberFormat($verification->total()) }})</span> Records found
        </div>
    </div>
    <div class="d-flex align-items-center">
        <a href="{{ url('consumers/verify/verificationExport'). '?' . http_build_query(request()->all()) }}" class="btn btn-outline-primary"><i class="bi bi-filetype-csv"></i>&nbsp;Export</a>
    </div>
</div>
<div class="table-responsive mt-2">
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>CRN No</th>
                <th>Name</th>
                <th>GA
                    <x-master.ga-filter class="float-end" />
                </th>
                <th>District
                    @if (request()->has('geo_area'))
                        <x-master.district-filter class="float-end"/>
                    @endif
                </th>
                <th>Charge Area
                    @if (request()->has('district'))
                        <x-master.charge-area-filter class="float-end"/>
                    @endif
                </th>
                <th>Status
                    @php
                        $verify_status = [1 => 'Verified Success', 0 =>'Verified Issue'];
                    @endphp
                    <x-admin.status-filter name="status" :data="$verify_status" class="float-end" />
                </th>
                <th>Verified By</th>
                <th>Verified Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($verification->count() > 0)
                @foreach($verification as $key => $verify)
                {{-- @php
                    if($verify->status == 0){
                        $status = 'Verified Issue';
                    }else{
                        $status = 'Verified Success';
                    }
                @endphp --}}
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><a href="{{ url('consumers/'.$verify->consumer_id) }}" target="_blank">{{ $verify->consumer->crn ?? '' }}</a></td>
                        <td>{{ $verify->consumer->name ?? '' }}</td>
                        <td>{{ $verify->consumer->ga->name ?? '' }}</td>
                        <td>{{ $verify->consumer->district->name ?? '' }}</td>
                        <td>{{ $verify->consumer->ca->name ?? '' }}</td>
                        <td>
                            <span class="badge {{ $verify->status == 1 ? 'bg-success' : 'bg-danger' }}">
                            {!! $verify->status == 1 ? '<i class="bi bi-check-circle">&nbsp;</i>Verified Success' : '<i class="bi bi-gear">&nbsp;</i>Verified Issue' !!}
                            </span>
                            {{-- {{ $status }} --}}
                        </td>
                        <td>{{ $verify->createdBy->name ?? '' }}</td>
                        <td>{{ dateFormat($verify->created_at ?? '') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item link-modal" href="{{ url('consumers/verify/show/'.$verify->id) }}"><i class="bi bi-eye-fill"></i>&nbsp;View</a></li>
                                    @if($verify->status == 0)
                                        <li><a class="dropdown-item link-modal" href="{{ url('consumers/verify/editStatus/'.$verify->id) }}"><i class="bi bi-pencil-square"></i>&nbsp;Edit Verification</a></li>
                                    @endif                                
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No Records Found</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div>
    {{ $verification->links('utils.paginator', ['modDiv' => 'verification-list']) }}
</div>
@include('scripts.link-modal')
