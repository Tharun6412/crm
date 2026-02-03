{{-- Price groups list body --}}

<div class="table-responsive">
    <table class="table table-bordered table-primary">
        <thead class="table-primary">
            <tr>
                <th width="1%" nowrap>S No</th>
                <th>Code</th>
                <th>Description</th>
                <th>GA<x-master.ga-filter class="float-end"/></th>
                <th>Segment<x-master.segment-filter class="float-end"/></th>
                <th class="text-end">Price</th>
                <th>Effective From</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if ($price_groups->count() > 0)
                @foreach ($price_groups as $group)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $group->code }}</td>
                        <td>{{ $group->description }}</td>
                        <td>{{ $group->ga->name ?? '' }}</td>
                        <td>{{ $group->segment->name ?? '' }}</td>
                        <td class="text-end">{{ numberFormat($group->price, 2) }}</td>
                        <td>{{ $group->effective_from?->format('d-m-Y') }}</td>
                        <td>
                            <a href="{{ url('master/consumer/price-groups/' . $group->id) }}" class="link-canvas"><i class="bi bi-info-square"></i></a>
                            <a href="{{ url('master/consumer/price-groups/' . $group->id . '/edit') }}" class="link-modal"><i class="bi bi-pencil-square"></i></a>
                            <a href="{{ url('master/consumer/price-groups/' . $group->id) }}" class="ajax-link-delete text-danger"><i class="bi bi-x-square"></i></a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8">
                        <div class="alert alert-warning mb-0">No records found!</div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
<div>
    {{ $price_groups->links('utils.paginator', ['modDiv' => 'price-group-list']) }}
</div>
{{-- Scripts --}}
@include('scripts.link-modal')
@include('scripts.link-canvas')
@include('scripts.ajax-link-delete')