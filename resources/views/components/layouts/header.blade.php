{{-- Header Componet --}}
<div class="navbar-custom">
    <div class="topbar container-fluid">
        <div class="d-flex align-items-center gap-lg-2 gap-1">
            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="bi bi-list"></i>
            </button>
            <span style="width: 173px;">
                <a href="{{ url('/') }}" title="Home">
                    <img src="{{ asset('img/logo.png') }}" alt="logo" class="img-fluid">
                </a>
            </span>
            <!-- Horizontal Menu Toggle Button -->
            <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>
        {{-- User_Menu Component --}}
        <x-layouts.user-menu/>
    </div>
</div>