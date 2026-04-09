{{-- Complaint status --}}
@props(['status' => null, 'mode' => null])

@php
    $statusId = $status->id ?? null;
    switch ($statusId) {
        case 1:
            $class = 'secondary';
            $icon = 'check2-square';
            break;
        case 2:
            $class = 'info';
            $icon = 'check2';
            break;
        case 3:
            $class = 'primary';
            $icon = 'diamond-half';
            break;
        case 4:
            $class = 'warning';
            $icon = 'search';
            break;
        case 5:
            $class = 'success';
            $icon = 'check-circle';
            break;
        case 6:
            $class = 'danger';
            $icon = 'x-circle-fill';
            break;
        default:
            $class = 'secondary';
            $icon = 'fan';
            break;
    }
@endphp
<span class="badge text-bg-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}"><i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $status->name ?? '' }}</span>