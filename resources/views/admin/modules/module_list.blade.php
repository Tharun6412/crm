{{-- Modules list --}}

@extends('layouts.layout')

@section('page-title', 'Module Administration')

@section('title', 'Module Administration')

@section('page-content')
    <div>
        <h3>Modules Tree</h3>
        {{-- Modules display --}}
        @if ($modules->isNotEmpty())
            <ul class="tree">
                {{-- Recursive display with view --}}
                @include('admin.modules.module-item', ['child_modules' => $modules])
            </ul>
        @endif    
    </div>
    @include('admin.modules.module-tree-script')
    @include('scripts.link-modal')
@endsection