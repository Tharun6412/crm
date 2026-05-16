{{-- Display GA address in Invoice --}}
@if ($address)
    @if ($display == 1)
        {{ $address->line1 }},
        {{ $address->line2 }},
        {{ $address->city }}, {{ $address->district->name ?? '' }},
        {{ $address->state->name ?? '' }}.
    @else
        {{ $address->line1 }},<br>
        {{ $address->line2 }},<br>
        {{ $address->city }}, {{ $address->district->name ?? '' }},<br>
        {{ $address->state->name ?? '' }} - {{ $address->pincode }}.
    @endif
@endif