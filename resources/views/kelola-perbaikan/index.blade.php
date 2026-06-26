@extends('layouts.app')

@section('title', 'Kelola Perbaikan & Kerusakan')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Manajemen Kerusakan & Perbaikan</h1>
    </div>

    <div class="section-body">
        {{-- Penanganan Notifikasi --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h4 class="text-primary">Daftar Antrean Perbaikan Inventaris</h4>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Pelapor</th>
                                <th>Barang (Jumlah)</th>
                                <th>Deskripsi Kerusakan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="20%">Aksi Sistem</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporans as $key => $laporan)
                                <tr>
                                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                                    <td class="align-middle">{{ $laporan->user->name }}</td>
                                    <td class="align-middle">
                                        <strong>{{ $laporan->barang->nama_barang }}</strong><br>
                                        <small class="text-danger">{{ $laporan->jumlah_rusak }} unit</small>
                                    </td>
                                    <td class="align-middle">{{ \Illuminate\Support\Str::limit($laporan->deskripsi_kerusakan, 100) }}</td>
                                    <td class="text-center align-middle">
                                        @if($laporan->status == 'menunggu_tinjauan')
                                            <span class="badge badge-warning text-dark">Menunggu Tinjauan</span>
                                        @elseif($laporan->status == 'sedang_diperbaiki')
                                            <span class="badge badge-info text-dark">Sedang Diperbaiki</span>
                                        @elseif($laporan->status == 'selesai')
                                            <span class="badge badge-success text-dark">Selesai</span>
                                        @elseif($laporan->status == 'rusak_total')
                                            <span class="badge badge-danger text-dark">Rusak Total</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        
                                        {{-- LOGIKA AKSES TOMBOL BERDASARKAN ROLE --}}
                                        
                                        @if($laporan->status == 'menunggu_tinjauan')
                                            @role('superadmin')
                                                <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalUpdate{{ $laporan->id }}">
                                                    <i class="fas fa-check-circle"></i> Setujui Perbaikan
                                                </button>
                                            @else
                                                <span class="text-muted small">Menunggu Admin</span>
                                            @endrole
                                            
                                        @elseif($laporan->status == 'sedang_diperbaiki')
                                            @role('technician')
                                                <button class="btn btn-sm btn-warning shadow-sm text-dark" data-toggle="modal" data-target="#modalUpdate{{ $laporan->id }}">
                                                    <i class="fas fa-tools"></i> Update Hasil
                                                </button>
                                            @else
                                                <span class="text-muted small">Dikerjakan Teknisi</span>
                                            @endrole
                                            
                                        @else
                                            <span class="text-muted small"><i class="fas fa-lock"></i> Terkunci</span>
                                        @endif

                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-5 text-muted">
                                        <i class="fas fa-clipboard-check fa-3x mb-3"></i>
                                        <h5>Tidak ada antrean laporan kerusakan.</h5>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@foreach($laporans as $laporan)
    @if(($laporan->status == 'menunggu_tinjauan' && auth()->user()->hasRole('superadmin')) || ($laporan->status == 'sedang_diperbaiki' && auth()->user()->hasRole('technician')))
    <div class="modal fade" id="modalUpdate{{ $laporan->id }}" tabindex="-1" role="dialog" aria-labelledby="modalUpdateLabel{{ $laporan->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="modalUpdateLabel{{ $laporan->id }}">Tindak Lanjut Perbaikan</h5>
                    <button type="button" class="close ml-auto border-0 bg-transparent" data-dismiss="modal" aria-label="Close" style="font-size: 1.5rem; outline: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kelola-perbaikan.update', $laporan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group text-left">
                            <label>Ubah Status Sistem</label>
                            <select name="status" class="form-control selectric" required>
                                @role('superadmin')
                                    <option value="sedang_diperbaiki">Setujui & Serahkan ke Teknisi</option>
                                @endrole
                                @role('technician')
                                    <option value="selesai">Berhasil Diperbaiki (Kembalikan ke Rak)</option>
                                    <option value="rusak_total">Rusak Total / Afkir (Hapus dari Aset)</option>
                                @endrole
                            </select>
                        </div>
                        <div class="form-group text-left">
                            <label>Catatan / Solusi Tindakan (Opsional)</label>
                            <textarea name="catatan_petugas" class="form-control" style="height: 80px" placeholder="Masukkan catatan teknis di sini...">{{ $laporan->catatan_petugas }}</textarea>
                        </div>
                        <div class="alert alert-warning text-dark text-left">
                            <i class="fas fa-exclamation-triangle"></i> Peringatan: Perubahan status ini akan otomatis mengubah jumlah stok fisik barang di database.
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary text-dark" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endforeach
    
</section>
@endsection