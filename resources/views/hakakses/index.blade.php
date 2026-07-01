@extends('layouts.app')

@section('title', 'Hak Akses')

@push('style')
    <!-- CSS Libraries -->
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .table td, .table th {
            vertical-align: middle; 
        }
        .empty-state {
            padding: 40px;
            text-align: center;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ced4da;
        }
    </style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Hak Akses</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Hak Akses</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Kelola Peran Pengguna</h2>
            <p class="section-lead">
                Atur peran dan kelola hak akses untuk setiap pengguna di dalam sistem.
            </p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h4 class="text-primary mb-2 mb-md-0">Daftar Pengguna</h4>

                            <div class="d-flex align-items-center flex-wrap" style="gap: 0.75rem;">

                                <a href="{{ route('hakakses.create') }}" class="btn btn-primary shadow-sm rounded-pill px-3">
                                    <i class="fas fa-plus mr-1"></i> Tambah Pengguna
                                </a>

                                <form action="{{ route('hakakses.index') }}" method="GET" class="d-flex align-items-center" style="gap: 0.75rem;">

                                    <select name="role" class="custom-select shadow-sm rounded-pill" style="width: auto; min-width: 120px;" onchange="this.form.submit()">
                                        <option value="">Semua Peran</option>
                                        <option value="superadmin" {{ request('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                        <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Guru</option>
                                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Siswa</option>
                                        <option value="technician" {{ request('role') == 'technician' ? 'selected' : '' }}>Teknisi</option>
                                    </select>

                                    <div class="input-group shadow-sm rounded-pill overflow-hidden" style="width: auto;">
                                        <input type="text" name="search" class="form-control border-0" placeholder="Cari berdasarkan nama atau email..." value="{{ request('search') }}" style="min-width: 250px;">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th>Nama Lengkap</th>
                                            <th>Alamat Email</th>
                                            <th class="text-center">Peran</th>
                                            <th class="text-center" width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($hakakses as $index => $user)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td class="font-weight-bold">{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td class="text-center">
                                                    @php($roleName = $user->getRoleNames()->first() ?? 'Pengguna Biasa')
                                                    <span @if($user->hasRole('superadmin'))
                                                        <span class="badge badge-success">{{ ucfirst($roleName) }}</span>
                                                            @elseif($user->hasRole('technician'))
                                                                <span class="badge badge-primary">{{ ucfirst($roleName) }}</span>
                                                            @else
                                                                <span class="badge badge-info">{{ ucfirst($roleName) }}</span>
                                                            @endif
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('hakakses.edit', $user->id) }}" class="btn btn-sm btn-primary mr-1" data-toggle="tooltip" title="Ubah Data">
                                                        <i class="fas fa-edit"></i> Ubah
                                                    </a>
                                                    <form action="{{ route('hakakses.delete', $user->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')" data-toggle="tooltip" title="Hapus Data">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="empty-state">
                                                        <i class="fas fa-folder-open"></i>
                                                        <h6>Belum ada data pengguna</h6>
                                                        <p>Silakan tambahkan pengguna baru terlebih dahulu atau sesuaikan kata kunci pencarian Anda.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        @if(method_exists($hakakses, 'links'))
                        <div class="card-footer bg-whitesmoke text-center">
                            {{ $hakakses->withQueryString()->links() }}
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush