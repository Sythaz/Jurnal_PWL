<div class="sidebar">
    <!-- SidebarSearch Form -->
    <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @if($isOwner)
                <li class="nav-header">Data Pengguna</li>
                <li class="nav-item">
                    <a href="{{ url('/user') }}" class="nav-link {{ $activeMenu == 'user' ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>Data User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/level') }}" class="nav-link {{ $activeMenu == 'level' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tag"></i>
                        <p>Level</p>
                    </a>
                </li>
            @endif
            <li class="nav-header">Kegiatan</li>
            <li class="nav-item">
                <a href="{{ url('/kegiatan') }}" class="nav-link {{ $activeMenu == 'kegiatan' ? 'active' : '' }}">
                    <i class="nav-icon far fa-list-alt"></i>
                    <p>Kegiatan</p>
                </a>
            </li>
        </ul>

        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"
            style="position: absolute; bottom: 0;">
            <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Log out</p>
                </a>
            </li>
        </ul>
    </nav>
</div>
