{{-- Consumer price body --}}

{{-- Search form --}}
<div class="d-flex justify-content-between mb-1">
    <div class="d-flex gap-1">
            <div class="col-auto">
                <input type="text" name="key" class="form-control" placeholder="Search..." value="{{ request()->key }}">
            </div>
            <div class="form-control">
                <span>Segments</span>
                <x-master.segment-filter class="float-end"/>
            </div>
        <div class="col-auto"><button type="submit" class="btn btn-success"><i class="bi bi-search"></i></button></div>
        <div class="col-auto"><a href="{{ url('master/consumer/prices') }}" class="btn btn-warning"><i class="bi bi-arrow-clockwise"></i></a></div>
        <div class="col-auto mt-1"><strong>({{ $gas_prices->total() }})</strong> Records found</div>
    </div>
    <div>
        <a href="{{ url('master/consumer/prices/create') }}" class="btn btn-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a>
    </div>
</div>
@if ($gas_prices->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-success">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>GA<x-master.ga-filter class="float-end"/></th>
                    <th>District</th>
                    <th class="text-end">Basic Price</th>
                    <th class="text-end">VAT%</th>
                    <th class="text-end">RSP</th>
                    <th>Effective From</th>
                    <th>Effective To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gas_prices as $price)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $price->district->ga->name ?? '' }}</td>
                        <td>{{ $price->district->name ?? '' }}</td>
                        <td class="text-end">{{ numberFormat($price->basic_price, 2) }}</td>
                        <td class="text-end">{{ numberFormat($price->tax_value, 2) }}</td>
                        <td class="text-end">{{ numberFormat($price->rsp, 2) }}</td>
                        <td>{{ $price->effective_from?->format('d-m-Y') }}</td>
                        <td>{{ $price->effective_to?->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ url('master/consumer/prices/' . $price->id) }}" class="link-canvas fs-sm btn btn-outline-info btn-sm"><i class="bi bi-chevron-right"></i>&nbsp;View</a>
                            <a href="{{ url('master/consumer/prices/' . $price->id . '/edit') }}" class="link-modal fs-sm btn btn-outline-info btn-sm"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-2">
        {{ $gas_prices->links('utils.paginator', ['modDiv' => 'cns-price-list']) }}
    </div>
@else
    <div class="alert alert-info">No reocrds found!</div>
@endif

{{-- Scripts --}}
@include('scripts.link-modal')
@include('scripts.link-canvas')