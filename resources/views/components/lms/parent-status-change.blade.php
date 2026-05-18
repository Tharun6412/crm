{{-- Lead status --}}
{{-- <span>{{ $status->name }}</span> --}}

@props(['status' => null, 'mode' => null])

@php
    switch ($status?->id) {
        case 1:
            $class = 'primary';
            $icon = 'person-square';
            break;
        case 2:
            $class = 'success';
            $icon = 'diamond-half';
            break;
        case 3:
            $class = 'danger';
            $icon = 'fan';
            break;
       

        default:
            $class = 'secondary';
            $icon = 'fan';
            break;
        
    }
@endphp
<span class="btn btn-sm btn-outline-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}"><i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $status?->name ?? '' }}</span>