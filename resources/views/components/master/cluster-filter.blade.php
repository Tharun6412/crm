{{-- Proposal list GA filter --}}
<div {{ $attributes->merge(['class' => 'dropdown float-end']) }}>
    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-funnel{{ (request()->has('geo_area')) ? '-fill' : '' }}"></i>
        @isset(request()->cluster)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ sizeof(request()->get('cluster')) }}
        </span>
        @endisset
    </button>
    <ul class="dropdown-menu bg-light">
        <li class="dropdown-item">
            <input type="checkbox" class="form-check-input" id="cluster_all">
            <label for="cluster_all" class="form-check-label">All or clear</label>
        </li>
        @php
            $cluster_checked = (request()->has('cluster')) ? request()->get('cluster') : [];
        @endphp
        @foreach ($clusters as $item)
            <li class="dropdown-item">
                <input type="checkbox" class="form-check-input cluster_filter" name="cluster[{{ $item->id }}]" id="cluster_{{ $item->id }}" value="{{ $item->id }}" @checked(in_array($item->id, $cluster_checked))>
                <label for="cluster_{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
            </li>
        @endforeach
    </ul>
</div>
@include('scripts.checkall-filter', ['element' => 'cluster'])