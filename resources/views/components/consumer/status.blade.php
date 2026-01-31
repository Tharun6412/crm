{{-- Consumer status display --}}

@props(['status' => null, 'mode' => null])

@php
    switch ($status->id) {
        case 1:
            $class = 'yellow text-dark';
            $icon = 'person-check';
            break;
        case 2:
            $class = 'primary';
            $icon = 'file-text';
            break;
        case 3:
            $class = 'info';
            $icon = 'check-all';
            break;
        case 4:
            $class = 'dark';
            $icon = 'gear-wide';
            break;
        case 5:
            $class = 'purple';
            $icon = 'link-45deg';
            break;
        case 6:
            $class = 'success';
            $icon = 'check2-square';
            break;
        case 7:
            $class = 'warning';
            $icon = 'ban';
            break;
        case 8:
            $class = 'danger';
            $icon = 'x-circle';
            break;

        default:
            $class = 'secondary';
            $icon = 'fan';
            break;
    }
@endphp
<span class="badge text-bg-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}"><i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $status->name ?? '' }}</span>