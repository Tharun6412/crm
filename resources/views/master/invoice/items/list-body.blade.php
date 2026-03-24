{{-- Invoice list body --}}

{{-- Search form --}}
<div class="d-flex justify-content-between align-items-center mb-2">
    <div class="d-flex gap-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}">
        </div>
        <div class="form-control">
            <span>Type</span>
            <x-master.InvItemTypeFilter class="float-end" />
        </div>
        <div class="col-auto"><button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button></div>
        <div class="col-auto"><a href="{{ url('master/invoice/items') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a></div>
        <div class="col-auto mt-1"><span class="fw-bold">({{ $items->total() }})</span> Records found</div>
    </div>
    <div>
        <a href="{{ url('master/invoice/items/create') }}" class="btn btn-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a>
    </div>
</div>
@if ($items->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white table-striped align-middle">
            <thead class="table-success">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Type</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>HSN</th>
                    <th class="text-end">Bsic</th>
                    <th nowrap class="text-end">Tax %</th>
                    <th class="text-end">Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->type->name ?? '' }}</td>
                        <td>{{ $item->code }}</td>
                        <td class="text-wrap" width="25%">{{ $item->name }}</td>
                        <td>{{ $item->hsn }}</td>
                        <td class="text-end">{{ numberFormat($item->basic, 2) }}</td>
                        <td class="text-end">{{ numberFormat($item->tax_value, 2) }}</td>
                        <td class="text-end">{{ numberFormat($item->price, 2) }}</td>
                        <td nowrap>
                            <a href="{{ url('master/invoice/items/' . $item->id) }}" class="link-canvas fs-sm btn btn-outline-info btn-sm">
                                <i class="bi bi-chevron-right"></i>&nbsp;View
                            </a>
                            <a href="{{ url('master/invoice/items/' . $item->id . '/edit') }}" class="link-modal fs-sm btn btn-outline-info btn-sm">
                                <i class="bi bi-pencil-square"></i>&nbsp;Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $items->links('utils.paginator', ['modDiv' => 'inv-items-list']) }}
    </div>
@else
    <x-layouts.callout-info>No records found!</x-layouts.callout-info>
@endif
{{-- Scripts --}}
@include('scripts.link-modal')
@include('scripts.link-canvas')