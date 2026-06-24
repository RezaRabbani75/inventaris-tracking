@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('home') }}">Pinjam.in</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ url('home') }}">P.in</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dasboard</li>
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}">
                    <i class="fas fa-fire"></i><span>Beranda</span>
                </a>
            </li>

            <li class="menu-header">Inventaris Lab</li>
            
            @hasanyrole('teacher|student')
            <li class="{{ Request::is('katalog*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('katalog') }}">
                    <i class="fas fa-boxes"></i> <span>Katalog Barang</span>
                </a>
            </li>
            @endhasanyrole

            @role('superadmin')
            <li class="{{ Request::is('kelola-barang*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('kelola-barang') }}">
                    <i class="fas fa-dolly-flatbed"></i> <span>Kelola Data Barang</span>
                </a>
            </li>
            @endrole

            <li class="menu-header">Fitur Utama</li>

            @hasanyrole('teacher|student')
            <li class="{{ Request::is('peminjaman-saya*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('peminjaman-saya') }}">
                    <i class="fas fa-hand-holding"></i> <span>Peminjaman Saya</span>
                </a>
            </li>
            @endhasanyrole

            @role('superadmin')
            <li class="{{ Request::is('persetujuan-peminjaman*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('persetujuan-peminjaman') }}">
                    <i class="fas fa-clipboard-check"></i> <span>Persetujuan Peminjaman</span>
                </a>
            </li>
            @endrole

            <li class="menu-header">Maintenance & Laporan</li>

            @hasanyrole('teacher|student')
            <li class="{{ Request::is('lapor-kerusakan*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('lapor-kerusakan') }}">
                    <i class="fas fa-exclamation-triangle"></i> <span>Buat Laporan Kerusakan</span>
                </a>
            </li>
            @endhasanyrole

            @hasanyrole('superadmin|technician')
            <li class="{{ Request::is('kelola-perbaikan*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('kelola-perbaikan') }}">
                    <i class="fas fa-tools"></i> <span>Daftar & Status Perbaikan</span>
                </a>
            </li>
            @endhasanyrole

            @role('superadmin')
            <li class="menu-header">Laporan</li>
            <li class="{{ Request::is('statistik*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('statistik') }}">
                    <i class="fas fa-chart-pie"></i> <span>Statistik Peminjaman</span>
                </a>
            </li>
            @endrole

            <li class="menu-header">Fitur Umum</li>
            <li class="{{ Request::is('notifications*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell"></i> 
                    <span>Notifikasi</span>
                    <livewire:notification-badge />
                </a>
            </li>
            
            @role('superadmin')
            <li class="menu-header">Pengaturan Sistem</li>
            <li class="{{ Request::is('hakakses*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('hakakses') }}">
                    <i class="fas fa-users-cog"></i> <span>Kelola Pengguna</span>
                </a>
            </li>
            <li class="{{ Request::is('activity-logs*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('activity-logs.index') }}">
                    <i class="fas fa-history"></i> <span>Log Aktivitas</span>
                </a>
            </li>
            <li class="{{ Request::is('settings*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('settings.index') }}">
                    <i class="fas fa-cog"></i> <span>Pengaturan</span>
                </a>
            </li>
            @endrole

            <li class="menu-header">Akun Saya</li>
            <li class="{{ Request::is('profile/edit') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('profile.edit') }}">
                    <i class="far fa-user"></i> <span>Edit Profil</span>
                </a>
            </li>
            <li class="{{ Request::is('profile/change-password') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('profile.change-password') }}">
                    <i class="fas fa-key"></i> <span>Ubah Kata Sandi</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
@endauth