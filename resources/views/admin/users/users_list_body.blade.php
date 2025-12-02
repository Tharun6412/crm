{{-- Users list body --}}
{{-- Search form --}}
<form id="users-search-form" action="{{ url('admin/users') }}" method="GET">
    <div class="row g-2 align-items-center pb-2">
        <div class="col-auto">
            <div class="input-group">
                <input type="text" name="search_key" id="search_key" class="form-control form-control-sm" placeholder="search here..." value="{{ request()->get('search_key') }}">
                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
            </div>
        </div>
        <div class="col-auto">
            <a href="{{ url('admin/users') }}" class="btn btn-sm btn-warning ajax-link"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
        <div class="col-auto">
            ({{ $users->total() }}) Records found
        </div>
    </div>

@php
    // Parameters for sorting and pagination
    $sort_by = (request()->get('sortBy')) ? request()->get('sortBy') : 'emp_id';
    $sort_order = (request()->get('sortOr') == 'asc') ? 'desc' : 'asc';
    $sort_icon = ($sort_order == 'asc') ? 'bi-caret-down-fill' : 'bi-caret-up-fill';
@endphp

{{-- Display --}}
@if ($users->total() > 0)
    <div class="table-responsive" style="min-height: 500px;">
        <table class="table table-bordered page-sort">
            <thead>
                <tr class="bg-light">
                    <th width="1%" nowrap>S No</th>
                    <th nowrap>
                        <a href="{{ $users->appends(['sortBy' => 'emp_id', 'sortOr' => $sort_order])->url($users->currentPage()) }}">
                            Employee Id
                            @if ($sort_by == 'emp_id')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ $users->appends(['sortBy' => 'first_name', 'sortOr' => $sort_order])->url($users->currentPage()) }}">
                            Name
                            @if ($sort_by == 'first_name')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ $users->appends(['sortBy' => 'email', 'sortOr' => $sort_order])->url($users->currentPage()) }}">
                            Email
                            @if ($sort_by == 'email')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="{{ $users->appends(['sortBy' => 'mobile', 'sortOr' => $sort_order])->url($users->currentPage()) }}">
                            Mobile
                            @if ($sort_by == 'mobile')
                                <i class="bi {{ $sort_icon }}"></i>
                            @endif
                        </a>
                    </th>
                    <th>Geo Area<x-master.ga-filter class="float-end"/></th>
                    <th>Department<x-master.department-filter/></th>
                    <th>Role<x-admin.role-filter/></th>
                    <th>Access</th>
                    <th width="80" nowrap>Status<x-admin.status-filter name="status" :data="[1 => 'Active', 0 => 'Inactive']"/></th>
                    <th>Actions</th>
                </thead>
                </tr>
            <tbody>
                @php
                    $i = (($users->currentPage() - 1) * $users->perPage()) + 1;
                @endphp
                @foreach ($users as $user)
                <tr>
                    <td>{{ $i++ }}</td>
                    <td><a class="dropdown-item link-modal" href="{{ url('admin/users/' . $user->id) }}">{{ $user->emp_id }}</a></td>
                    <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->mobile }}</td>
                    <td>
                        @if ($user->ga->count() > 0)
                            @foreach ($user->ga as $ga)
                                @if ($loop->iteration == 1)
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ $ga->code . '-' . $ga->name }}
                                        </button>
                                        <ul class="dropdown-menu">                
                                @else
                                    <li class="dropdown-item"><i class="bi bi-check2-square"></i>&nbsp;{{ $ga->code . '-' . $ga->name }}</li>
                                @endif
                            @endforeach
                                </ul>
                            </div>
                        @endif
                    </td>
                    <td>
                        @isset($user->department)
                            {{ $user->department->name }}
                        @endisset
                    </td>
                    <td>
                        @if ($user->roles->count() > 0)
                            @foreach ($user->roles as $role)
                                @if ($loop->iteration == 1)
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            {{ $role->name }}
                                        </button>
                                        <ul class="dropdown-menu">                
                                @else
                                    <li class="dropdown-item"><i class="bi bi-check2-square"></i>&nbsp;{{ $role->name }}</li>
                                @endif
                            @endforeach
                                </ul>
                            </div>
                        @endif
                    </td>
                    <td>
                        @if ($user->ga_restriction)
                            GA
                        @elseif($user->cluster_restriction)
                            Cluster
                        @else
                            Full
                        @endif
                    </td>
                    <td>
                        <span class="badge text-bg-{{ ($user->status == 1) ? 'success' : 'danger' }}">
                            {{ ($user->status == 1) ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item link-modal" href="{{ url('admin/users/' . $user->id) }}">
                                        <i class="bi bi-info-circle"></i>&nbsp;View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item link-modal" href="{{ url('admin/users/' . $user->id . '/edit') }}">
                                        <i class="bi bi-pencil"></i>&nbsp;Edit
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item reset-pwd" href="{{ url('admin/users/reset/' . $user->id) }}">
                                        <i class="bi bi-key"></i>&nbsp;Reset Password
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item link-modal" href="{{ url('admin/users/status/' . $user->id) }}">
                                        @if ($user->status == 1)
                                            <i class="bi bi-ban"></i>&nbsp;Inactive
                                        @else
                                            <i class="bi bi-check-circle"></i>&nbsp;Activate
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @php
        // Reset pagination parameters for paginator
        $users->appends(['sortBy' => $sort_by, 'sortOr' => request()->get('sortOr')]);
    @endphp
    {{ $users->links('utils.paginator', ['modDiv' => 'users-list']) }}
@else
    <x-layouts.callout-info message="No users found."/>
@endif
</form>
{{-- Dynamic scripts --}}
@include('scripts.ajax-form-search', ['form' => 'users'])
@include('scripts.ajax-link', ['div' => 'users-list'])
@include('scripts.link-modal')
<script type="module">
$(function(){
    // Reset pwd
    $(".reset-pwd").click(function(e){
        e.preventDefault();
        if(confirm('You want to reset password for this user?')) {
            $.post($(this).attr('href'), {'_token' : "{{ csrf_token() }}"}, function(response){
                alert(response.msg);
            });
        }
    });
});
</script>