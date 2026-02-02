{{-- Profile view --}}

@extends('layouts.layout')

@section('title', 'User Profile')
@section('page-title', 'User Profile')
    
@section('page-content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="border p-3">
        <div class="row align-items-center">
            <div class="col-sm-4 text-center">
                <span class="text-light" style="font-size: 254px; line-height:0;">
                    <i class="bi bi-fingerprint"></i>
                </span>
                <h3 class="mt-3">{{ $user->first_name . ' ' . $user->last_name }}</h3>
                <h5>
                    @isset($user->role->name)
                        {{ $user->role->name }}
                    @endisset
                </h5>
            </div>
            <div class="col-sm-8">
                <div class="">
                    <dl class="d-flex">
                        <dt>Emp Id:</dt>
                        <dd class="ps-2 mb-0">{{ $user->emp_id }}</dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>Name:</dt>
                        <dd class="ps-2 mb-0">{{ $user->first_name . ' ' . $user->last_name }}</dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>Role:</dt>
                        <dd class="ps-2 mb-0">
                            {{ $user->roles->pluck('name') }}
                        </dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>Geo area:</dt>
                        <dd class="ps-2 mb-0">
                            {{ $user->ga->pluck('name') }}
                        </dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>Department:</dt>
                        <dd class="ps-2 mb-0">
                            @isset($user->department->name)
                                {{ $user->department->name }}
                            @endisset
                        </dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>Email:</dt>
                        <dd class="ps-2 mb-0">{{ $user->email }}</dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>Mobile:</dt>
                        <dd class="ps-2 mb-0">{{ $user->mobile }}</dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>Gender:</dt>
                        <dd class="ps-2 mb-0">{{ !empty($user->gender) ? (($user->gender == 1) ? 'Male' : 'Female') : '-' }}</dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>DOB:</dt>
                        <dd class="ps-2 mb-0">
                            @isset($user->dob)
                            {{ $user->dob->format('d M Y') ?? '' }}
                            @endisset
                        </dd class="ps-2 mb-0">
                    </dl>
                    
                    <dl class="d-flex">
                        <dt>Status:</dt>
                        <dd class="ps-2 mb-0">
                            <x-admin.user-status :status="$user->status"/>
                        </dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt>User from:</dt>
                        <dd class="ps-2 mb-0">{{ $user->created_at->format('d M Y') }}</dd class="ps-2 mb-0">
                    </dl>
                    <dl class="d-flex">
                        <dt></dt>
                        <dd class="ps-2 mb-0">
                            <a href="{{ url('profile/edit') }}" class="btn btn-primary"><i class="bi bi-pencil"></i>&nbsp;Edit profile</a>
                        </dd class="ps-2 mb-0">
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection