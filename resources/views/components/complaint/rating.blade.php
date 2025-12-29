{{-- Rating stars --}}

@for ($i = 1; $i <= $rating; $i++)
    <i class="bi bi-star-fill text-warning"></i>
@endfor
{{-- Empty stars --}}
@for ($j = $i; $i <= 5; $i++)
    <i class="bi bi-star-fill text-light"></i>
@endfor