{{-- Common status display --}}

<span class="badge text-bg-{{ ($status == 1) ? 'success' : 'danger' }}">{{ ($status == 1) ? 'Enable' : 'Disable' }}</span>