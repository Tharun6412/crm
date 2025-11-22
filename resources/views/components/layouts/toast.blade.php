{{-- Toast messages --}}
@props(['position' => 'top-0 end-0', 'zindex' => 'z-index: 1080;'])

@if (session()->has('toasts'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        @foreach(session('toasts') as $t)
            @php
                $type = $t['type'] ?? 'info';
                $title = $t['title'] ?? '';
                $body = $t['body'] ?? '';
                $delay = isset($t['delay']) ? (int)$t['delay'] : 5000;
                // map types to header classes or icons if you like
            @endphp

            <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true"
                    data-bs-autohide="true" data-bs-delay="{{ $delay }}">
                <div class="toast-header">
                    <strong class="me-auto text-{{ $type}}">
                        @if($type === 'success') <i class="bi-check2-square"></i>
                        @elseif($type === 'warning') <i class="bi-exclamation-triangle"></i>
                        @elseif($type === 'danger') <i class="bi-x-square"></i>
                        @else <i class="bi-info-square"></i>
                        @endif
                        &nbsp; {{ $title }}
                    </strong>
                    <small class="text-muted"></small>
                    <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {!! \Illuminate\Support\Str::limit(e($body), 1000) !!}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Initialize toasts --}}
    @once
        @push('scripts')
            <script type="module">
            $(function () {
                // Query the toast elements rendered by this component
                const toastElList = Array.from(document.querySelectorAll('.toast'));
                
                toastElList.forEach(function (toastEl) {
                    // Initialize each toast (Bootstrap 5)
                    const toast = new bootstrap.Toast(toastEl);
                    toast.show();
                });
            });
            </script>
        @endpush
    @endonce
@endif
