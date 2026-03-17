{{-- GA address --}}
@if ($address)
    {{ $address->line1 }},<br>
    {{ $address->line2 }},<br>
    {{ $address->city }}, {{ $address->district->name ?? '' }},<br>
    {{ $address->state->name ?? '' }} - {{ $address->pincode }}.
@endif