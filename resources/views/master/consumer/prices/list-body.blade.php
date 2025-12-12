{{-- Consumer price body --}}

{{-- Search form --}}
<div class="d-flex justify-content-between">
    <div class="row gx-1 mb-1">
        <div class="col-auto">
            <input type="text" name="key" class="form-control form-control-sm" placeholder="Search..." value="{{ request()->key }}">
        </div>
        <div class="col-auto">
            <div class="d-flex justify-content-between">
                <span>Segments</span>
                <x-master.segment-filter/>
            </div>
        </div>
        <div class="col-auto"><button type="submit" class="btn btn-success btn-sm"><i class="bi bi-search"></i></button></div>
        <div class="col-auto"><a href="{{ url('master/consumer/prices') }}" class="btn btn-warning btn-sm"><i class="bi bi-arrow-clockwise"></i></a></div>
        <div class="col-auto">({{ $gas_prices->total() }}) Records found</div>
    </div>
    <div>
        <a href="{{ url('master/consumer/prices/create') }}" class="btn btn-sm btn-success link-modal">
            <i class="bi bi-plus-lg"></i>&nbsp;Create
        </a>
    </div>
</div>
@if ($gas_prices->count() > 0)
    <div class="table-responsive">
        <table class="table table-bordered table-primary">
            <thead class="table-primary">
                <tr>
                    <th width="1%" nowrap>S No</th>
                    <th>GA<x-master.ga-filter class="float-end"/></th>
                    <th>District</th>
                    <th class="text-end">Basic Price</th>
                    <th class="text-end">VAT%</th>
                    <th class="text-end">RSP</th>
                    <th>Affective From</th>
                    <th>Affective To</th>
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
                        <td>{{ $price->effective_from }}</td>
                        <td>{{ $price->effective_to }}</td>
                        <td>
                            drop down
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $gas_prices->links('utils.paginator', ['modDiv' => 'cns-price-list']) }}
    </div>
@else
    <div class="alert alert-info">No reocrds found!</div>
@endif

{{-- Scripts --}}
@include('scripts.link-modal')