{{-- Consumer status display --}}

@props([
    'status' => null,
    'mode' => null,
    'created_at' => null
])

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
<div class="border-start border-3 border-{{ $class }} h-100 position-relative">
    <div class="position-absolute top-0 start-0 translate-middle badge rounded-pill text-bg-{{ $class }} p-2">
        <i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $created_at }}
    </div>
</div>