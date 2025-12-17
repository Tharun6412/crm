{{-- Complaint categories list --}}

@extends('layouts.layout')

@section('page-title', 'Complaint Categories')

@section('title', 'Categories')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('master') }}">Master</a></li>
@endsection

@section('page-content')
    <div>
        <div class="d-flex justify-content-between">
            <h3>Categories</h3>
            <a href="{{ url('master/complaint/categories/create?parent_id=0') }}" class="btn btn-success link-modal"><i class="bi bi-plus-lg"></i>&nbsp;Create Main Category</a>
        </div>
        @if ($categories->isNotEmpty())
            <ul class="tree">
                @foreach ($categories as $category)
                    {{-- Main categories --}}
                    <li>
                        @if ($category->children->isNotEmpty())
                            <span class="toggle"><i class="bi bi-chevron-down"></i></span>
                            <span class="badge rounded-pill text-bg-secondary">{{ $category->children->count() }}</span>
                        @else
                            <span><i class="bi bi-dot"></i></span>
                        @endif
                        <span>{{ $category->name }}</span>
                        {{-- Actions --}}
                        <x-common.status :status="$category->status"/>
                        <a href="{{ url('/master/complaint/categories/' . $category->id) }}" class="btn btn-outline-info btn-sm link-canvas">
                            <i class="bi bi-chevron-right"></i>&nbsp;view
                        </a>
                        <a href="{{ url('/master/complaint/categories/' . $category->id . '/edit') }}" class="btn btn-outline-info btn-sm link-modal">
                            <i class="bi bi-pencil"></i>&nbsp;Edit
                        </a>
                        <a href="{{ url('/master/complaint/categories/create?parent_id=' . $category->id) }}" class="btn btn-outline-primary btn-sm link-modal">
                            <i class="bi bi-plus"></i>Add Sub Category
                        </a>
                        {{-- Subcategories --}}
                        @if ($category->children->isNotEmpty())
                            <ul>
                                @foreach ($category->children as $sub_category)
                                <li>
                                    @if ($sub_category->children->isNotEmpty())
                                        <span class="toggle"><i class="bi bi-chevron-down"></i></span>
                                    @else
                                        <span><i class="bi bi-dot"></i></span>
                                    @endif
                                    <span>{{ $sub_category->name }}</span>
                                    {{-- Actions --}}
                                    <x-common.status :status="$sub_category->status"/>
                                    <a href="{{ url('/master/complaint/categories/' . $sub_category->id) }}" class="btn btn-outline-info btn-sm link-canvas">
                                        <i class="bi bi-chevron-right"></i>&nbsp;view
                                    </a>
                                    <a href="{{ url('/master/complaint/categories/' . $sub_category->id . '/edit') }}" class="btn btn-outline-info btn-sm link-modal">
                                        <i class="bi bi-pencil"></i>&nbsp;Edit
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
                {{-- Recursive display with view --}}
                {{-- @include('admin.modules.module-item', ['child_modules' => $modules]) --}}
            </ul>
        @endif    
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('admin.modules.module-tree-script')
    @include('scripts.link-modal')
    @include('scripts.link-canvas')
@endpush