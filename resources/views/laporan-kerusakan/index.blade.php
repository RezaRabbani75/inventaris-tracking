@extends('layouts.app')

@section('title', 'Laporkan Kerusakan Perangkat')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Riwayat Laporan Kerusakan</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('laporan-kerusakan.create') }}" class="btn btn-primary btn-icon icon-left shadow-sm">
                <i class="fas fa-tools"></i> Laporkan Kerusakan Baru
            </a>
        </div>
    </div>

    <div class="section-body">
        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade" role="alert">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h4 class="text-primary">Daftar Perangkat Rusak yang Dilaporkan</h4>
            </div>
            
            <div class="card-body p-0"> 
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Jumlah Rusak</th>
                                <th>Status Barang</th>
                                <th>Tanggal Lapor</th>
                                <th class="text-center">Status</th>
                                <th>Catatan Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporans as $key => $laporan)
                                <tr>
                                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                                    <td class="align-middle"><strong>{{ $laporan->barang->nama_barang }}</strong></td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-secondary py-1 px-2 text-black">{{ $laporan->jumlah_rusak }} unit</span>
                                    </td>
                                    <td class="align-middle">
                                        @if($laporan->peminjaman_id)
                                            <span class="text-info"><i class="fas fa-bookmark mr-1"></i> Transaksi Pinjam #{{ $laporan->peminjaman_id }}</span>
                                        @else
                                            <span class="text-center"><i class="mr-1 text-black"></i> Sudah Dikembalikan kepada Admin di Rak Lab</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $laporan->created_at->format('d M Y, H:i') }} WIB</td>
                                    <td class="text-center align-middle">
                                        @if($laporan->status == 'menunggu_tinjauan')
                                            <span class="badge badge-warning text-dark">Menunggu Tinjauan</span>
                                        @elseif($laporan->status == 'sedang_diperbaiki')
                                            <span class="badge badge-info">Sedang Diperbaiki</span>
                                        @elseif($laporan->status == 'selesai')
                                            <span class="badge badge-success">Selesai Perbaikan</span>
                                        @elseif($laporan->status == 'rusak_total')
                                            <span class="badge badge-danger">Rusak Total / Afkir</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-muted">{{ $laporan->catatan_petugas ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center p-5">
                                        <div class="empty-state" data-height="250">
                                            <div class="empty-state-icon bg-primary mb-3">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                            <h2>Semua Perangkat Aman</h2>
                                            <p class="lead">
                                                Tidak ada laporan kerusakan aktif yang kamu kirimkan.
                                            </p>
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
</section>
@endsection