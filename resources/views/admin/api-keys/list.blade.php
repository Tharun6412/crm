{{-- API KEYS --}}

@extends('layouts.layout')

@section('page-title', 'API Keys')

@section('title', 'API KEYS')

@section('page-content')
    <div>
        @if ($api_keys->count() > 0)
            <table class="table table-bordered table-primary">
                <thead class="table-primary">
                    <tr>
                        <th width="1%" nowrap>S No</th>
                        <th>Name</th>
                        <th>Key</th>
                        <th>Status</th>
                        <th>Expires At</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($api_keys as $key)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $key->name }}</td>
                            <td>{{ $key->key_original }}</td>
                            <td><x-common.status :status="$key->is_active" /></td>
                            <td>{{ $key->expires_at?->format('d-m-Y H:i') }}</td>
                            <td>{{ $key->created_at?->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ url('admin/api-keys/' . $key->id . '/edit') }}" class="link-modal">Edit</a>
                                <a href="{{ url('admin/api-keys/' . $key->id) }}" class="ajax-link-delete">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info mb-0">No records found!</div>
        @endif
    </div>
@endsection
{{-- Scripts --}}
@push('scripts')
    @include('scripts.link-modal')
    @include('scripts.ajax-link-delete')
@endpush