@extends('layouts.app')

@section('title', 'Tinjau Peminjaman')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('persetujuan-peminjaman.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Detail Pengajuan Peminjaman</h1>
    </div>

    <div class="section-body">
        <div class="row">
            {{-- Panel Kiri: Informasi Peminjaman --}}
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4>Informasi Peminjam & Barang</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="35%">Nama Peminjam</th>
                                <td>: {{ $peminjaman->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Perangkat Diminta</th>
                                <td>: <strong>{{ $peminjaman->barang->nama_barang }}</strong></td>
                            </tr>
                            <tr>
                                <th>Sisa Stok di Lab</th>
                                <td>: <span class="badge badge-secondary text-dark">{{ $peminjaman->barang->stok_tersedia }} Unit</span></td>
                            </tr>
                            <tr>
                                <th>Jumlah Diajukan</th>
                                <td>: <span class="text-danger font-weight-bold">{{ $peminjaman->jumlah }} Unit</span></td>
                            </tr>
                            <tr>
                                <th>Tujuan Penggunaan</th>
                                <td>: {{ $peminjaman->tujuan_pinjam }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Mulai Pinjam</th>
                                <td>: {{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Rencana Dikembalikan</th>
                                <td>: {{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Status Saat Ini</th>
                                <td>: 
                                    @if($peminjaman->status == 'menunggu')
                                        <span class="badge badge-secondary text-dark">Menunggu</span>
                                    @elseif($peminjaman->status == 'disetujui')
                                        <span class="badge badge-info text-dark">Disetujui</span>
                                    @elseif($peminjaman->status == 'dipinjam')
                                        <span class="badge badge-primary text-dark">Sedang Dipinjam</span>
                                    @elseif($peminjaman->status == 'dikembalikan')
                                        <span class="badge badge-success text-dark">Dikembalikan</span>
                                    @elseif($peminjaman->status == 'ditolak')
                                        <span class="badge badge-danger text-dark">Ditolak</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Form Eksekusi Tindakan --}}
            <div class="col-md-5">
                <div class="card shadow-sm border-top-primary">
                    <div class="card-header">
                        <h4>Tindakan Admin</h4>
                    </div>
                    <div class="card-body">
                        {{-- Menangkap error jika stok tidak cukup saat disetujui --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('persetujuan-peminjaman.update', $peminjaman->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="status">Ubah Status Pengajuan <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control selectric" required>
                                    <option value="menunggu" {{ $peminjaman->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="disetujui" {{ $peminjaman->status == 'disetujui' ? 'selected' : '' }}>Setujui Pengajuan</option>
                                    <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Barang Sedang Dibawa</option>
                                    <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Barang Telah Dikembalikan (Selesai)</option>
                                    <option value="ditolak" {{ $peminjaman->status == 'ditolak' ? 'selected' : '' }}>Tolak Pengajuan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pesan_admin">Pesan Tambahan (Opsional)</label>
                                <textarea name="pesan_admin" id="pesan_admin" class="form-control" style="height: 100px" placeholder="Contoh: Pengajuan ditolak karena perangkat sedang dalam perbaikan, atau Setujui dengan syarat pengembalian jam 15.00">{{ old('pesan_admin', $peminjaman->pesan_admin) }}</textarea>
                                <small class="text-muted">Wajib diisi jika kamu menolak pengajuan ini.</small>
                            </div>

                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" onclick="return confirm('Apakah kamu yakin ingin mengubah status pengajuan ini?')">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan Status
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection