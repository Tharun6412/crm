{{-- Prepaid Consumers list body --}}
{{-- Search form --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <div class="input-group">
                <span class="input-group-text" id="search-key">Search</span>
                <input type="text" name="key" id="search-key" class="form-control" value="{{ request()->key }}">
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button>
        </div>
        <div class="col-auto">
            <a href="{{ url('consumers/prepaid') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto mt-2">
            <strong>({{ $consumers->total() }})</strong> Records Found
        </div>
    </div>
    {{-- <div>
        <a href="{{ url('consumers/filters') }}" class="btn btn-warning btn-sm link-modal">
            <i class="bi bi-funnel"></i>
        </a>
        <a href="{{ url('consumers/consumerExport') }}?{{ http_build_query(request()->all()) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-file-earmark-excel"></i>&nbsp;Export
        </a>
    </div> --}}
</div>
{{-- Consumers list --}}
<div class="table-responsive" style="min-height: 500px;">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-success">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>CRN</th>
                <th>Name</th>
                <th nowrap>
                    <div class="d-flex flex-row gap-2">
                        <div>Segment</div>
                        <div><x-master.segmentFilter class="float-end" /></div>
                    </div>
                </th>
                <th>Status<x-consumer.statusFilter class="float-end" /></th>
                <th>GA<x-master.gaFilter class="float-end" /></th>
                <th>District</th>
                <th>Scheme</th>
                <th nowrap>Price Group</th>
                <th nowrap>
                    <div class="d-flex flex-row gap-2">
                        <div>Added Date</div>
                        <div><x-master.date-filter class="float-end" /></div>
                    </div>
                </th>
                <th nowrap>Meter Serial No</th>
                <th nowrap>HES Status</th>
                <th nowrap>
                    <div class="d-flex flex-row gap-2">
                        <div>HES Date</div>
                        <div><x-master.date-filter class="float-end" /></div>
                    </div>
                </th>
                <th width="2%" nowrap>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($consumers->count() > 0)
                @foreach ($consumers as $consumer)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td nowrap>
                            <i class="bi bi-{{ ($consumer->connection_type_id == 1) ? 'speedometer2' : 'wifi'}}"></i>
                            <x-auth.link href="{{ url('consumers/' . $consumer->id) }}">
                                {{ $consumer->crn ?? $consumer->t_crn }}
                            </x-auth.link>
                        </td>
                        <td nowrap>{{ $consumer->name }}</td>
                        <td nowrap>{{ $consumer->segment->name }}</td>
                        <td nowrap>
                            <x-consumer.status :status="$consumer->status" mode='full' />
                        </td>
                        <td nowrap>{{ $consumer->ga->name }}</td>
                        <td nowrap>{{ $consumer->district->name }}</td>
                        <td>
                            @if ($consumer->scheme?->scheme?->id != null)
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="popover"  data-bs-trigger="hover focus" data-bs-placement="top"  data-bs-content="{{ $consumer->scheme?->scheme?->name }}">{{ $consumer->scheme?->scheme?->code }}</button>
                            @endif
                        </td>
                        <td>{{ $consumer->priceGroup?->code }}</td>
                        <td>{{ dateFormat($consumer->created_at) }}</td>
                        <td>{{ $consumer?->activeMeter?->meter_serial_no }}</td>
                        <td>
                            <x-consumer.hes-status :status="$consumer->prepaidDate?->hes_status"/>
                        </td>
                        <td>{{ dateFormat($consumer->prepaidData?->hes_date) }}</td>
                        <td>
                            @include('consumers.consumers.prepaid-actions')
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="14">
                        <x-layouts.callout-info>No records found!</x->
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div>
    {{ $consumers->links('utils.paginator', ['modDiv' => 'prepaid-consumers-list']) }}
</div>
@include('scripts.bs-popover')
@include('scripts.link-modal')