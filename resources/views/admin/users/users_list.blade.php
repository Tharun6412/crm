{{-- Admin Users list --}}

@extends('layouts.layout')

@section('title', 'Users')

@section('page-title', 'User Administration')

@section('page-content')
    <div id="users-list" class="current-page-reload">
        @include('admin.users.users_list_body')
    </div>
@endsection