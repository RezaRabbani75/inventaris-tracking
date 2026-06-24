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
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dasboard</a></div>
                <div class="breadcrumb-item">Hak Akses</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Kelola Peran Pengguna</h2>
            <p class="section-lead">
                Atur peran dan kelola hak akses untuk setiap pengguna di dalam sistem.
            </p>

            <!-- Menampilkan Pesan Sukses -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <!-- Menampilkan Pesan Error -->
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
                        <div class="card-header">
                            <h4>Daftar Pengguna</h4>
                            <div class="card-header-action d-flex align-items-center">
                                <a href="{{ route('hakakses.create') }}" class="btn btn-primary mr-3 shadow-sm">
                                    <i class="fas fa-plus"></i> Tambah Pengguna
                                </a>
                                <form method="GET" action="{{ route('hakakses.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari nama atau email..." name="search" value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <!-- Menambahkan class table-hover agar baris menyala saat dilewati kursor -->
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
                                                    <!-- Memberikan warna badge yang berbeda berdasarkan peran -->
                                                    <span class="badge badge-{{ $user->hasRole('superadmin') ? 'danger' : ($user->hasRole('admin') ? 'warning' : 'primary') }}">
                                                        {{ ucfirst($roleName) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('hakakses.edit', $user->id) }}" class="btn btn-sm btn-info mr-1" data-toggle="tooltip" title="Ubah Data">
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
                                            <!-- Tampilan ketika data kosong (Empty State) -->
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
                        
                        <!-- Tempat untuk Paginasi (jika menggunakan method paginate() di Controller) -->
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