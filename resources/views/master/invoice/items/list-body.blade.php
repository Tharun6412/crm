{{-- Invoice list body --}}

{{-- Search form --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control form-control-sm" placeholder="Search..." value="{{ request()->key }}">
        </div>
        <div class="col-auto">
            <div class="d-flex justify-content-between">
                <span>Type</span>
                <x-master.InvItemTypeFilter />
            </div>
        </div>
        <div class="col-auto"><button type="submit" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button></div>
        <div class="col-auto"><a href="{{ url('master/invoice/items') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a></div>
        <div class="col-auto">({{ $items->total() }}) Records found</div>
    </div>
    <div>
        <a href="{{ url('master/invoice/items/create') }}" class="btn btn-sm btn-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a>
    </div>
</div>
@if ($items->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-primary">
            <thead class="table-primary">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>Type</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>HSN</th>
                    <th class="text-end">Bsic</th>
                    <th class="text-end">Tax %</th>
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
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->hsn }}</td>
                        <td class="text-end">{{ numberFormat($item->basic, 2) }}</td>
                        <td class="text-end">{{ numberFormat($item->tax_value, 2) }}</td>
                        <td class="text-end">{{ numberFormat($item->price, 2) }}</td>
                        <td>
                            <a href="{{ url('master/invoice/items/' . $item->id) }}" class="link-canvas fs-sm">
                                <i class="bi bi-chevron-right"></i>&nbsp;View
                            </a>
                            <a href="{{ url('master/invoice/items/' . $item->id . '/edit') }}" class="link-modal fs-sm">
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