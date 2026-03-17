{{-- Invoice status --}}
{{-- <span>{{ $status->name }}</span> --}}

@props(['status' => null, 'mode' => null])

@php
    switch ($status->id) {
        case 1:
            $class = 'success';
            $icon = 'check2-square';
            break;
        case 2:
            $class = 'danger';
            $icon = 'x-square';
            break;
        case 3:
            $class = 'primary';
            $icon = 'diamond-half';
            break;
        case 4:
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