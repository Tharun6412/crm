{{-- User details show view --}}
<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">User - {{ $user->emp_id }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="border p-3">
                <div class="row align-items-center">
                    <div class="col-sm-6 text-center">
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
                    <div class="col-sm-6">
                        <div class="">
                            <dl class="d-flex">
                                <dt>Emp Id:</dt>
                                <dd class="ps-2 mb-0">{{ $user->emp_id }}</dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Name:</dt>
                                <dd class="ps-2 mb-0">{{ $user->first_name . ' ' . $user->last_name }}</dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Role:</dt>
                                <dd class="ps-2 mb-0">
                                        {{ $user->roles->pluck('name') }}
                                </dd>
                            </dl>                            
                            <dl class="d-flex">
                                <dt>Cluster:</dt>
                                <dd class="ps-2 mb-0">
                                    @isset($user->ga->cluster)
                                        {{ $user->ga->cluster->name }}
                                    @endisset
                                </dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Geo area:</dt>
                                <dd class="ps-2 mb-0">
                                    {{ $user->ga->pluck("name") }}
                                </dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Department:</dt>
                                <dd class="ps-2 mb-0">
                                    @isset($user->department->name)
                                        {{ $user->department->name }}
                                    @endisset
                                </dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Email:</dt>
                                <dd class="ps-2 mb-0">{{ $user->email }}</dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Mobile:</dt>
                                <dd class="ps-2 mb-0">{{ $user->mobile }}</dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Gender:</dt>
                                <dd class="ps-2 mb-0">{{ !empty($user->gender) ? (($user->gender == 1) ? 'Male' : 'Female') : '-' }}</dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>DOB:</dt>
                                <dd class="ps-2 mb-0">
                                    @empty(!$user->dob)
                                        {{ $user->dob->format('d M Y') }}
                                    @endempty
                                </dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Cluster Restriction:</dt>
                                <dd class="ps-2 mb-0">{{ ($user->cluster_restriction) ? 'True' : 'False' }}</dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>GA Restriction:</dt>
                                <dd class="ps-2 mb-0">{{ ($user->ga_restriction) ? 'True' : 'False' }}</dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>Status:</dt>
                                <dd class="ps-2 mb-0">
                                    <span class="badge bg-{{ ($user->status == 1) ? 'success' : 'danger' }}">
                                        {{ !empty($user->status) ? (($user->status == 1) ? 'Active' : 'Inactive') : 'Inactive' }}
                                    </span>
                                </dd>
                            </dl>
                            <dl class="d-flex">
                                <dt>User from:</dt>
                                <dd class="ps-2 mb-0">
                                    @empty(!$user->created_at)
                                        {{ $user->created_at->format('d M Y') }}
                                    @endempty
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i>&nbsp;Close</button>
        </div>
    </div>
</div>