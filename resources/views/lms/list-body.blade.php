@php
    use \App\Enums\LeadStatus;
@endphp
<div class="d-flex justify-content-between mb-1">
    <div class="row gx-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('lms/leads') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            <span class="fw-semibold">({{ $leads->total() }})</span> Records found
        </div>
    </div>
</div>
<div class="mt-2 table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>S.No</th>
                <th>Code</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Ga<x-master.ga-filter class="float-end"/></th>
                <th>District
                    @if (request()->has('geo_area'))
                        <x-master.district-filter class="float-end"/>
                    @endif
                </th>
                <th nowrap>Charge Area
                    @if (request()->has('district'))
                        <x-master.charge-area-filter class="float-end"/>
                    @endif
                </th>
                <th>Area
                    @if (request()->has('ca'))
                        <x-master.area-filter class="float-end"/>
                    @endif
                </th>
                <th>Medium</th>
                <th nowrap>Status<x-lms.status-filter :status="$status" class="float-end"/></th>
                <th nowrap>Sub Status<x-lms.substatus-filter :status="$status" class="float-end"/></th>   
                <th nowrap>Created Date</th>
                <th nowrap>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if($leads->count() > 0)
            @foreach ($leads as $lead)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td ><a href="{{ url('lms/leads/show/' . $lead->id) }}" class="link-modal">{{ $lead->code ?? '' }}</a></td>
                    <td>{{ $lead->name }}</td>
                    <td>{{ $lead->mobile}}</td>
                    <td>{{ $lead->ga->name ??'' }}</td>
                    <td>{{ $lead->district->name ?? '' }}</td>
                    <td>{{ $lead->ca->name ?? ''}}</td>
                    <td>{{ $lead->area->name ?? ''}}</td>
                    <td>{{ $lead->leadChannel->name ?? ''}}</td>
                    <td nowrap><x-lms.parent-status-change :status="$lead->status?->parent" /></td>
                    <td nowrap><x-lms.status-change :status="$lead->status" /></td>
                    <td>{{ dateFormat($lead->created_at) }}</td>
                    <td>{{ $lead->createdBy->name }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item link-modal" href="{{ url('lms/leads/show/'.$lead->id) }}">
                                        <i class="bi bi-chevron-right"></i> View
                                    </a>
                                </li>
                                <li><a class="dropdown-item link-modal" href="{{ url('lms/leads/statusChange/'.$lead->id) }}"><i class="bi bi-pencil-square"></i> Update Status</a></li>
                            </ul>

                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="13">No Records Found</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<div>
    {{ $leads->links('utils.paginator', ['modDiv' => 'lms-list']) }}
</div>
@include('scripts.link-modal')