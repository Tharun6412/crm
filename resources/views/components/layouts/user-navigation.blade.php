{{-- Navigation --}}
<!-- Sidebar -->
<div class="leftside-menu menuitem-active">
    <!-- Sidebar -left -->
    <div class="h-100 show" id="leftside-menu-container" data-simplebar="init"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content"><div class="simplebar-content" style="padding: 0px;">
        
        <!-- Leftbar User -->
        <div class="leftbar-user"></div>
        {{-- Side navigation First level --}}
        <ul class="side-nav">
            {{-- <li class="side-nav-item menuitem-active">
                <a href="{{ url('/') }}" class="side-nav-link">
                    <i class="bi bi-house-door"></i>
                    <span>Dashboard</span>
                </a>
            </li> --}}
            {{-- @dd($modules) --}}
            @isset($modules)
                @foreach ($modules as $module)
                    <li class="side-nav-item">
                        {{-- Recursive view load --}}
                        @if ($module->children->isNotEmpty())
                            <a href="#nav{{ $module->id }}" class="side-nav-link collapsed" data-bs-toggle="collapse" aria-controls="nav{{ $module->id }}">
                                <i class="bi {{ $module->icon }}"></i>
                                <span>{{ $module->name }}</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="nav{{ $module->id }}">
                                {{-- Second level --}}
                                <ul class="side-nav-second-level">
                                    @include('components.layouts.user-navigation-item', ['child_modules' => $module->children])
                                </ul>
                            </div>
                        @else
                            <a href="{{ ($module->url) ? url($module->url) : '#' }}" class="side-nav-link">
                                <i class="bi {{ $module->icon }}"></i><span>{{ $module->name }}</span>
                            </a>
                        @endif
                    </li>
                @endforeach
            @endisset ($modules)
            {{-- @if (isAdmin() OR isSuperAdmin())
                <li class="side-nav-item">
                    <a href="{{ url('admin/modules') }}" class="side-nav-link">
                        <i class="bi bi-gear"></i>
                        <span>Module Administration</span>
                    </a>
                </li>
            @endif
            <li class="side-nav-item">
                <a href="{{ url('public') }}" class="side-nav-link">
                    <i class="bi bi-question-circle"></i>
                    <span>Help</span>
                </a>
            </li> --}}
        </ul>
        {{-- End side navigation --}}
        <div class="clearfix"></div>

    </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: auto;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="height: 407px; transform: translate3d(0px, 0px, 0px); display: block;"></div></div></div>
</div>