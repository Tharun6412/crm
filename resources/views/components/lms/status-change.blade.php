{{-- Lead status --}}
{{-- <span>{{ $status->name }}</span> --}}

@props(['status' => null, 'mode' => null])

@php
    switch ($status->id) {
        case 4:
            $class = 'primary';
            $icon = 'person-square';
            break;
        case 5:
            $class = 'secondary';
            $icon = 'diamond-half';
            break;
        case 6:
            $class = 'info';
            $icon = 'check2-square';
            break;
        case 7:
            $class = 'success'; 
            $icon = 'check-circle';
            break;
        case 8:
            $class = 'warning'; 
            $icon = 'exclamation-triangle';
            break;
        case 9:
            $class = 'danger'; 
            $icon = 'x-circle';
            break;

        case 10:
            $class = 'dark'; 
            $icon = 'slash-circle';
            break;

        default:
            $class = 'secondary';
            $icon = 'fan';
            break;
        
    }
@endphp
<span class="btn btn-sm btn-outline-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}"><i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $status->name ?? '' }}</span>