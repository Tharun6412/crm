{{-- spot stage status --}}

@props(['stage' => null, 'mode' => null, 'type' => null])

@php
    switch ($stage->parent->id) {
        case 1:
            $class = 'danger';
            $icon = 'binoculars';
            break;
        case 2:
            $class = 'warning';
            $icon = 'lightbulb';
            break;
        case 3:
            $class = 'primary';
            $icon = 'flag';
            break;
        case 4:
            $class = 'info';
            $icon = 'coin';
            break;
        case 5:
            $class = 'success';
            $icon = 'check2-square';
            break;            
        case 6:
            $class = 'secondary';
            $icon = 'rocket-takeoff';
            break;
        default:
            $class = 'secondary';
            $icon = 'fan';
            break;
    }
@endphp
@if ($type == 1)    
    <span class="btn btn-sm btn-outline-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}"><i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $stage->parent->name ?? '' }}</span>
@endif
@if ($type == 2)    
    <span class="btn btn-sm btn-outline-{{ $class }} {{ ($mode =='full') ? 'w-100' : '' }}"><i class="bi bi-{{ $icon }}"></i>&nbsp;{{ $stage->name ?? '' }}</span>
@endif
