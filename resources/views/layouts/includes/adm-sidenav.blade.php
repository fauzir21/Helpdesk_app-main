<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Account)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <div class="sidenav-menu-heading d-sm-none">Account</div>
                <!-- Sidenav Link (Alerts)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="bell"></i></div>
                    Alerts
                    <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>
                </a>
                <!-- Sidenav Link (Messages)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="mail"></i></div>
                    Messages
                    <span class="badge bg-success-soft text-success ms-auto">2 New!</span>
                </a>
                <!-- Sidenav Menu Heading (Core)-->
                <div class="sidenav-menu-heading">Dashboard</div>
                <!-- Sidenav Accordion (Dashboard)-->
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                    href="{{ route('dashboard') }}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Dashboard
                </a>

                {{-- jika tipe admin --}}
                @if (Auth::user()->tipe === 'admin')
                    <!-- Master-->
                    <div class="sidenav-menu-heading">Master</div>

                    {{-- tim kerja --}}
                    <a class="nav-link {{ request()->routeIs('tim-kerja.*') ? 'active' : '' }}"
                        href="{{ route('tim-kerja.index') }}">
                        <div class="nav-link-icon"><i data-feather="users"></i></div>
                        Tim Kerja
                    </a>
                    {{-- users --}}
                    <a class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}"
                        href="{{ route('user.index') }}">
                        <div class="nav-link-icon"><i data-feather="users"></i></div>
                        Users
                    </a>

                    <!-- Master Data -->
                    <div class="sidenav-menu-heading">Master Data</div>
                    {{-- Master Layanan --}}
                    <a class="nav-link {{ request()->routeIs('layanan.*') ? 'active' : '' }}"
                        href="{{ route('layanan.index') }}">
                        <div class="nav-link-icon"><i data-feather="server"></i></div>
                        Master Layanan
                    </a>
                    {{-- Master Persyaratan --}}
                    <a class="nav-link {{ request()->routeIs('persyaratan.*') ? 'active' : '' }}"
                        href="{{ route('persyaratan.index') }}">
                        <div class="nav-link-icon"><i data-feather="clipboard"></i></div>
                        Master Persyaratan
                    </a>
                    {{-- Master Survei --}}
                    <a class="nav-link {{ request()->routeIs('master-survei.*') ? 'active' : '' }}"
                        href="{{ route('master-survei.index') }}">
                        <div class="nav-link-icon"><i data-feather="smile"></i></div>
                        Master Survei
                    </a>
                    <!-- Sidenav Heading (UI Toolkit)-->
                    <div class="sidenav-menu-heading">Layanan</div>
                    {{-- Layanan Persyaratan --}}
                    <a class="nav-link {{ request()->routeIs('layanan-persyaratan.*') ? 'active' : '' }}"
                        href="{{ route('layanan-persyaratan.index') }}">
                        <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                        Layanan Persyaratan
                    </a>
                    <a class="nav-link {{ request()->routeIs('permohonan.*') && !request()->routeIs('permohonan.list') ? 'active' : '' }}"
                        href="{{ route('permohonan.index') }}">
                        <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                        Data Permohonan
                    </a>
                @endif

                @if (Auth::user()->tipe === 'users')
                    <div class="sidenav-menu-heading">Layanan</div>
                    <a class="nav-link {{ request()->routeIs('permohonan.*') && !request()->routeIs('permohonan.list') ? 'active' : '' }}"
                        href="{{ route('permohonan.index') }}">
                        <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                        Data Permohonan Layanan
                    </a>

                    <a class="nav-link {{ request()->routeIs('permohonan.list') ? 'active' : '' }}"
                        href="{{ route('permohonan.list') }}">
                        <div class="nav-link-icon"><i data-feather="send"></i></div>
                        Cek Permohonan Saya
                    </a>
                @endif

                @if (Auth::user()->tipe === 'helpdesk')
                    <div class="sidenav-menu-heading">Layanan</div>
                    <a class="nav-link {{ request()->routeIs('permohonan.*') && !request()->routeIs('permohonan.list') ? 'active' : '' }}"
                        href="{{ route('permohonan.index') }}">
                        <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                        Data Permohonan Layanan
                        @if (isset($pending_count) && $pending_count > 0)
                            <span class="badge bg-warning text-white ms-auto">{{ $pending_count }}</span>
                        @endif
                    </a>
                @endif

                @if (Auth::user()->tipe === 'pegawai')
                    <div class="sidenav-menu-heading">Layanan</div>
                    <a class="nav-link {{ request()->routeIs('permohonan.*') && !request()->routeIs('permohonan.list') ? 'active' : '' }}"
                        href="{{ route('permohonan.index') }}">
                        <div class="nav-link-icon"><i data-feather="file-text"></i></div>
                        Data Permohonan Layanan
                        @if (isset($pending_count) && $pending_count > 0)
                            <span class="badge bg-warning text-white ms-auto">{{ $pending_count }}</span>
                        @endif
                    </a>
                    <a class="nav-link {{ request()->routeIs('riwayat-pengerjaan.*') ? 'active' : '' }}"
                        href="{{ route('riwayat-pengerjaan.index') }}">
                        <div class="nav-link-icon"><i data-feather="check-square"></i></div>
                        Riwayat Penyelesaian
                    </a>
                @endif

                <!-- Sidenav Heading (Addons)-->
                <div class="sidenav-menu-heading"></div>
                <!-- Sidenav Link (Tables)-->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <div class="nav-link-icon"><i data-feather="log-out"></i></div>
                        Logout
                    </a>
                </form>
            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Login Sebagai:</div>
                <div class="sidenav-footer-title">{{ Auth::user()->name }}</div>
            </div>
        </div>
    </nav>
</div>
