@extends('layouts.app')

@section('title', 'Kelola Data Barang')

@push('style')
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .table td, .table th {
            vertical-align: middle !important;
        }
        .img-barang {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .stock-info {
            font-size: 0.85rem;
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
            <h1>Kelola Data Barang</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Kelola Barang</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Daftar Inventaris Lab</h2>
            <p class="section-lead">
                Halaman ini digunakan untuk melihat, menambah, mengubah, dan menghapus data perangkat Lab.
            </p>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h4>Data Perangkat</h4>
                            <div class="card-header-action">
                                <a href="{{ route('kelola-barang.create') }}" class="btn btn-primary shadow-sm">
                                    <i class="fas fa-plus mr-1"></i> Tambah Barang Baru
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th width="10%">Foto</th>
                                            <th>Detail Barang</th>
                                            <th>Rincian Stok</th>
                                            <th class="text-center" width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($barangs as $index => $barang)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    @if($barang->foto)
                                                        <img src="{{ asset('img/barang/' . $barang->foto) }}" alt="{{ $barang->nama_barang }}" class="img-barang shadow-sm">
                                                    @else
                                                        <div class="img-barang d-flex justify-content-center align-items-center bg-light text-muted shadow-sm">
                                                            <i class="fas fa-box fa-2x"></i>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <strong>{{ $barang->nama_barang }}</strong><br>
                                                    <span class="text-warning" style="font-size: 0.85rem;">Kode: {{ $barang->kode_barang }}</span><br>
                                                    <span class="badge badge-info mt-1">{{ $barang->kategori }}</span>
                                                </td>
                                                <td>
                                                    <div class="stock-info">
                                                        <div><span class="text-dark font-weight-bold">Total:</span> {{ $barang->total_stok }} unit</div>
                                                        <div><span class="text-success font-weight-bold">Tersedia:</span> {{ $barang->stok_tersedia }} unit</div>
                                                        
                                                        @if($barang->stok_dipinjam > 0)
                                                            <div><span class="text-warning">Dipinjam:</span> {{ $barang->stok_dipinjam }} unit</div>
                                                        @endif
                                                        
                                                        @if($barang->stok_rusak > 0)
                                                            <div><span class="text-danger">Rusak:</span> {{ $barang->stok_rusak }} unit</div>
                                                        @endif

                                                        @if($barang->stok_diperbaiki > 0)
                                                            <div><span class="text-info">Diperbaiki:</span> {{ $barang->stok_diperbaiki }} unit</div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('kelola-barang.edit', $barang->id) }}" class="btn btn-sm btn-info mb-1" data-toggle="tooltip" title="Ubah Data">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('kelola-barang.destroy', $barang->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini? Semua riwayat terkait barang ini mungkin akan terpengaruh.')" data-toggle="tooltip" title="Hapus Data">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">
                                                    <div class="empty-state">
                                                        <i class="fas fa-box-open"></i>
                                                        <h6>Belum ada data barang perangkat Lab</h6>
                                                        <p>Klik tombol "Tambah Barang Baru" untuk memasukkan data inventaris pertama Anda.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush