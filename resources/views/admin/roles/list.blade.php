{{-- Roles --}}

@extends('layouts.layout')

@section('title', 'Roles')

@section('page-title', 'Roles')

@section('page-content')    
    @php
        $sno = 1;
    @endphp
    <div class="mb-2">
        ({{ $roles->count() }}) Records found
        {{-- Authenticated link --}}
        <x-auth.link href="{{ url('admin/roles/create') }}" class="btn btn-success btn-sm link-modal float-end mb-2" action="add">
            <i class="bi bi-plus-lg"></i>&nbsp;Add role
        </x-auth.link>
    </div>
    <table class="table table-bordered table-hover">
        <thead>
            <tr class="bg-light">
                <th width="1%" nowrap>S.No</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $sno++ }}</td>
                    <td>{{ $role->name }}</td>
                    <td>
                        <span class="btn btn-outline-{{ ($role->status == 1) ? 'success' : 'warning' }} btn-sm">
                            {{ ($role->status == 1) ? 'Enabled' : 'Disabled' }}
                        </span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item link-canvas" href="{{ url('admin/roles/' . $role->id) }}"><i class="bi bi-lightning"></i>&nbsp;View</a></li>
                                <li>
                                    {{-- Authenticated link --}}
                                    <x-auth.link class="dropdown-item link-modal" href="{{ url('admin/roles/' . $role->id . '/edit') }}" action="edit">
                                        <i class="bi bi-pencil"></i>&nbsp;Edit
                                    </x-auth.link>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('scripts.link-modal')
    @include('scripts.link-canvas')
@endsection