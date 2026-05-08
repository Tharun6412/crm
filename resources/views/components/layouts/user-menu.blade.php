<ul class="topbar-menu d-flex align-items-center gap-3">
    <li class="dropdown border-end border-light-subtle pe-3 d-none d-sm-inline-block">
        <a href="javascript:void(0)" onclick="changeFontSize('-')" style="font-size: 12px;">A</a>
        <a href="javascript:void(0)" onclick="changeFontSize('a')" style="font-size: 16px;">A</a>
        <a href="javascript:void(0)" onclick="changeFontSize('+')" style="font-size: 18px;">A</a>
    </li>
    {{-- <li class="d-none d-sm-inline-block">
        <div class="nav-link" id="light-dark-mode" data-bs-toggle="tooltip" data-bs-placement="left" aria-label="Theme Mode" data-bs-original-title="Theme Mode">
            <i class="bi bi-brightness-high font-22"></i>
        </div>
    </li> --}}
    {{-- <li>Welcome! Guest</li> --}}
    @auth
        <li>
            <x-layouts.notifications/>
        </li>
        <li class="dropdown">
            <a class="nav-link dropdown-toggle nav-user px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <span class="d-lg-flex align-items-center gap-1 d-none">
                    <div class="fw-semibold">{{ Auth::user()->first_name . ' ' . substr(Auth::user()->last_name, 0, 1) }}</div>
                    <div>({{ Auth::user()->emp_id }})</div>
                </span>
                <span class="account-user-avatar">
                    <img src="{{ asset('img/profile.png') }}" alt="profile-image" class="rounded-circle" width="18">
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                <a href="{{ url('profile') }}" class="dropdown-item">
                    <i class="bi bi-fingerprint me-1"></i>
                    <span>My Account</span>
                </a>
                <a href="{{ url('changePassword') }}" class="dropdown-item">
                    <i class="bi bi-person-lock me-1"></i>
                    <span>Change Password</span>
                </a>
                <form action="{{ url('logout') }}" method="post" class="m-0">
                    @csrf
                    <a class="dropdown-item" href="{{ url('logout') }}" onclick="javascript:event.preventDefault(); this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        <span>Logout</span>
                    </a>
                </form>
            </div>
        </li>
    @endauth
</ul>