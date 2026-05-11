@php
    use \App\Enums\GeyserStatus;
@endphp
{{-- Geysers List --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}"/>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('consumers/geysers') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            <span class="fw-semibold">({{ $geysers->total() }})</span> Records found
        </div>
    </div>
    <div>
        <a href="{{ url('consumers/geysers/createSearch') }}" class="btn btn-outline-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Add Geyser</a>
    </div>
</div>

<!--  Geysers List -->
<div class="table-responsive">
<table class="table table-bordered table-hover table-striped bg-white align-middle">
    <thead class="table-success">
        <tr>
            <th>S.No</th>
            <th>CRN</th>
            <th>Name</th>
            <th>GA<x-master.ga-filter class="float-end" /></th>
            <th>District
                @if (request()->has('geo_area'))
                    <x-master.district-filter class="float-end"/>
                @endif
            </th>
            <th nowrap>Geyser Code</th>
            <th>Invoice No</th>
            <th npwrap>Amount</th>
            <th nowrap>Balance Amount</th>  
            <th nowrap>Invoice Status 
                @php $inv_status = [1 => 'Paid', 2 => 'Not-Paid', 3 => 'Partial Paid']; @endphp
                <x-admin.status-filter name="status_id" :data="$inv_status" class="float-end" />
            </th>
            <th nowrap>Geyser Status<x-geyser.status-filter class="float-end" /></th>
            <th nowrap>Created Date</th>
            <th nowrap>Created By</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @if($geysers->count() > 0 )
            @foreach ($geysers as $geyser)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><a href="{{ url('consumers/'.$geyser->consumer_id) }}" target="_blank">{{ $geyser->consumer->crn ?? '' }}</a></td>
                    <td>{{ $geyser->consumer->name ?? ''}}</td>
                    <td>{{ $geyser->consumer->ga->name ?? '' }}</td>
                    <td>{{ $geyser->consumer->district->name ?? ''}}</td>
                    <td ><a href="{{ url('consumers/geysers/' . $geyser->id) }}" class="link-modal">{{ $geyser->code ?? '' }}</a></td>
                    <td>{{ $geyser->invoice->invoice_number ?? '' }}</td>
                    <td>{{ numberFormat($geyser->invoice->payable_amount ?? 0,2 )}}</td>
                    <td>{{ numberFormat($geyser->invoice->balance_amount ?? 0,2 )}}</td>
                    <td><x-invoice.status :status="$geyser->invoice->status"/></td>
                    <td><x-geyser.status-change :status="$geyser->status"/></td>
                    <td>{{ dateFormat($geyser->created_at) }}</td>
                    <td>{{ $geyser->createdBy->name ?? '' }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item link-modal" href="{{ url('consumers/geysers/' . $geyser->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;View</a></li> 
                                @if ($geyser->status_id == GeyserStatus::REGISTER->value)
                                    <li><a class="dropdown-item link-modal" href="{{ url('consumers/geysers/execute/'.$geyser->id) }}" action="close"><i class="bi bi-chevron-right"></i>&nbsp;Execute</a></li>
                                @endif
                                @if ($geyser->status_id == GeyserStatus::EXECUTE->value) 
                                    <li><a class="dropdown-item link-modal" href="{{ url('consumers/geysers/active/'.$geyser->id) }}" action="close"><i class="bi bi-chevron-right"></i>&nbsp;Active</a></li>
                                @endif
                                @if ($geyser->status_id == GeyserStatus::ACTIVE->value) 
                                    <li><a class="dropdown-item link-modal" href="{{ url('consumers/geysers/disconnect/'.$geyser->id) }}" action="close"><i class="bi bi-chevron-right"></i>&nbsp;Disconnect</a></li>
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12">No Records Found</td>
            </tr>
        @endif
    </tbody>
</table>
</div>
<div>
    {{ $geysers->links('utils.paginator', ['modDiv' => 'geysers-list']) }}
</div>
@include('scripts.link-modal')
