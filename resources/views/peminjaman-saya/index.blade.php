@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Riwayat Peminjaman Saya</h1>
        <div class="section-header-breadcrumb">
            <a href="{{ route('katalog.index') }}" class="btn btn-primary btn-icon icon-left shadow-sm">
                <i class="fas fa-plus"></i> Ajukan Peminjaman Baru
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
        
        {{-- Notifikasi Error (Penting untuk batas 3 kali pembatalan) --}}
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible show fade" role="alert">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header border-bottom">
                <h4 class="text-primary">Daftar Pengajuan Perangkat</h4>
            </div>
            
            <div class="card-body p-0"> 
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Nama Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th>Tujuan / Keperluan</th>
                                <th>Tgl Pinjam</th>
                                <th>Rencana Kembali</th>
                                <th class="text-center">Status</th>
                                <th>Pesan Admin</th>
                                <th class="text-center">Aksi</th> {{-- Kolom Baru --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjamans as $key => $peminjaman)
                                <tr>
                                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                                    <td class="align-middle"><strong>{{ $peminjaman->barang->nama_barang }}</strong></td>
                                    <td class="text-center align-middle">{{ $peminjaman->jumlah }} unit</td>
                                    <td class="align-middle">{{ $peminjaman->tujuan_pinjam }}</td>
                                    <td class="align-middle">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</td>
                                    <td class="align-middle">{{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</td>
                                    <td class="text-center align-middle">
                                        @if($peminjaman->status == 'menunggu')
                                            <span class="badge badge-warning text-dark">Menunggu</span>
                                        @elseif($peminjaman->status == 'disetujui')
                                            <span class="badge badge-info text-dark">Disetujui</span>
                                        @elseif($peminjaman->status == 'dipinjam')
                                            <span class="badge badge-primary text-dark">Dipinjam</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge badge-success text-dark">Dikembalikan</span>
                                        @elseif($peminjaman->status == 'ditolak')
                                            <span class="badge badge-danger text-dark">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $peminjaman->pesan_admin ?? '-' }}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{-- Tombol Batal Hanya Muncul Jika Status Menunggu --}}
                                        @if($peminjaman->status == 'menunggu')
                                            <form action="{{ route('peminjaman-saya.batalkan', $peminjaman->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Yakin ingin membatalkan pengajuan ini?')">
                                                    Batalkan
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted"><i class="fas fa-ban"></i></span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center p-5"> {{-- Colspan diubah ke 9 --}}
                                        <div class="empty-state" data-height="250">
                                            <div class="empty-state-icon bg-primary mb-3">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <h2>Belum ada riwayat pengajuan</h2>
                                            <p class="lead">
                                                Kamu belum meminjam perangkat apapun. Yuk, mulai ajukan peminjaman dari katalog!
                                            </p>
                                            <a href="{{ route('katalog.index') }}" class="btn btn-primary mt-4">
                                                Lihat Katalog
                                            </a>
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