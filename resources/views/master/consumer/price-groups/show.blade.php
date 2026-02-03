{{-- Price group details --}}
<div class="offcanvas-header border-bottom">
    <h4 class="offcanvas-title" id="offcanvasRightLabel">View Price Group - {{ $price_group->code }}</h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td class="bg-light">Code</td>
                    <td>{{ $price_group->code }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Description</td>
                    <td>{{ $price_group->description }}</td>
                </tr>
                <tr>
                    <td class="bg-light">GA</td>
                    <td>{{ $price_group->ga->name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Segmentg</td>
                    <td>{{ $price_group->segment->name ?? '' }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Basic</td>
                    <td>{{ numberFormat($price_group->basic, 2) }}</td>
                </tr>
                <tr>
                    <td class="bg-light">VAT</td>
                    <td>{{ numberFormat($price_group->vat, 2) }}%</td>
                </tr>
                <tr>
                    <td class="bg-light">Price</td>
                    <td>{{ numberFormat($price_group->price, 2) }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Effective from</td>
                    <td>{{ $price_group->effective_from?->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Created@</td>
                    <td>{{ $price_group->created_at?->format('d-m-Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="bg-light">Updated@</td>
                    <td>{{ $price_group->updated_at?->format('d-m-Y H:i') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @if ($price_group->history->count() > 0)
        <h4>History</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-info">
                <thead class="table-info">
                    <tr>
                        <th>Updated@</th>
                        <th>Price</th>
                        <th>Effective From</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($price_group->history as $item)
                        <tr>
                            <td>{{ $item->created_at?->format('d-m-Y') }}</td>
                            <td>{{ numberFormat($item->price, 2) }}</td>
                            <td>{{ $item->effective_from?->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>