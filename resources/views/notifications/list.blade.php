{{-- Notifications --}}

@extends('layouts.layout')

@section('title', 'Notifications')
    
@section('page-title', 'Notifications')

@section('page-content')
    {{--  --}}
    @if (Auth::user()->notifications->count() > 0)
        <div class="row mb-3">
            <div class="col-sm-6">
                ({{ Auth::user()->notifications->count() }}) Notifications found
            </div>
            <div class="col-sm-6 text-end">
                <a href="{{ url('notifications/all') }}" id="read-noti-all"  class="btn btn-outline-primary btn-sm {{ (Auth::user()->unReadNotifications->count() > 0) ? '' : 'disabled' }}"><i class="bi bi-app-indicator"></i>&nbsp;Read all</a>
                <a href="{{ url('notifications/all') }}" id="delete-noti-all"  class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i>&nbsp;Delete all</a>
            </div>
        </div>
        @foreach (Auth::user()->notifications as $notification)
            <div class="p-2 mb-1 border {{ ($notification->read_at) ? 'text-secondary' : 'bg-light' }}">
                <span class="p-2 rounded-circle bg-secondary-subtle me-2 text-dark"><i class="bi bi-app{{ ($notification->read_at) ? '' : '-indicator' }} fs-5"></i></span>
                <strong>[{{ $notification->created_at->format('d M Y H:i') }}]</strong> {{ $notification->data['message'] ?? '' }}
                <a href="{{ url('user/exports') }}">Go to Exports</a>
            </div>
        @endforeach
    @else
        <div class="p-2 mb-1 border">
            <span class="p-2 rounded-circle bg-secondary-subtle me-2"><i class="bi bi-bell-slash fs-5"></i></span>No notifications found.
        </div>
    @endif
    <script type="module">
        $(function(){
            // Read all
            $('#read-noti-all').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'PUT',
                    data: {'_token': "{{ csrf_token() }}"},
                    success: function(response){
                        // 
                    }
                });
            });
            // Delete all
            $('#delete-noti-all').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('href'),
                    type: 'DELETE',
                    data: {'_token': "{{ csrf_token() }}"},
                    success: function(response){
                        // 
                    }
                });
            });
        });
    </script>
@endsection