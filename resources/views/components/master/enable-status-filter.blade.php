@switch($status)
    @case(1)
        <span class="badge bg-success">Enable</span>
        @break
    @default
        <span class="badge bg-danger">Disable</span>
@endswitch