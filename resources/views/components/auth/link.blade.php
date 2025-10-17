{{-- Link Component --}}

@props([
    'href' => '#',
    'target' => '_self',
    'active' => false,
    'action' => false,
])
@php
    // Get user actions for the module from ModuleAccess middleware
    $user_actions = request('user_module_actions') ?? [];
@endphp
@if (isSuperAdmin() OR isAdmin() OR in_array($action, $user_actions))
    <a 
        href="{{ $href }}" 
        target="{{ $target }}"
        {{ $attributes->merge() }}
    >
        {{ $slot }}
    </a>
@endif