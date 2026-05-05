{{-- User notifications component --}}
<div class="dropdown-center">
    <a href="#" class="nav-link no-caret" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
        <i class="bi bi-bell"></i>
        @if (Auth::user()->unReadNotifications->count() > 0)
            <span class="position-absolute top--1 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
        @endif
    </a>
    <div class="dropdown-menu p-1 border-0">
        @if (Auth::user()->unReadNotifications->count() > 0)
            @foreach (Auth::user()->unReadNotifications->slice(0, 10) as $notification)
                <div class="dropdown-item p-2 mb-1 border">
                    <a href="{{ url('notifications/' . $notification->id) }}" class="notf-read p-2 rounded-circle bg-secondary-subtle me-2 text-dark" title="Mark as read"><i class="bi bi-bell-fill fs-5"></i></a>
                    {{ $notification->data['message'] ?? '' }}
                </div>
            @endforeach
            <div class="dropdown-item p-2 mb-1 border text-center">
                <a href="{{ url('notifications') }}" class="text-dark">All notifications</a>
            </div>
        @else
        <div class="dropdown-item p-2 mb-1 border">
            <span class="p-2 rounded-circle bg-secondary-subtle me-2"><i class="bi bi-bell-slash fs-5"></i></span>No new notifications!
        </div>
        @endif
    </div>
</div>
<script type="module">
    $(function(){
        $('.notf-read').click(function(e){
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                type: 'PUT',
                data: {'_token': "{{ csrf_token() }}"},
                success: function(response){
                    // 
                }
            });
            $(this).parent('.dropdown-item').remove();
        });
    });
</script>