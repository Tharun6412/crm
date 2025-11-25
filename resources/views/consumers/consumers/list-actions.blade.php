{{-- Consumers list actions --}}
<div class="dropdown">
    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ url('consumers/' . $consumer->id) }}"><i class="bi bi-chevron-right"></i>&nbsp;view</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi bi-chevron-right"></i>&nbsp;Action2</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi bi-chevron-right"></i>&nbsp;Action3</a></li>
    </ul>
</div>