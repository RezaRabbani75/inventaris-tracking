@extends('layouts.app')
    
@section('title', 'Persetujuan Peminjaman')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Kelola Persetujuan Peminjaman</h1>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                <h4 class="text-primary mb-0">Daftar Seluruh Pengajuan Perangkat</h4>
                
                <form action="{{ route('persetujuan-peminjaman.index') }}" method="GET" class="form-inline" onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerHTML='<i class=\'fas fa-spinner fa-spin\'></i> Memproses...';">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-filter"></i></span>
                        </div>
                        <select name="peran" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Peran</option>
                            <option value="teacher" {{ request('peran') == 'teacher' ? 'selected' : '' }}>Hanya Guru</option>
                            <option value="student" {{ request('peran') == 'student' ? 'selected' : '' }}>Hanya Siswa</option>
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Peminjam</th>
                                <th>Peran</th>
                                <th>Barang</th>
                                <th class="text-center">Jumlah</th>
                                <th>Tgl Pinjam</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjamans as $key => $peminjaman)
                                <tr>
                                    <td class="text-center align-middle">{{ $key + 1 }}</td>
                                    
                                    <td class="align-middle"><strong>{{ $peminjaman->user->name }}</strong></td>
                                    
                                    <td class="align-middle">
                                        @if($peminjaman->user->hasRole('teacher'))
                                            <span class="badge badge-info">Guru</span>
                                        @elseif($peminjaman->user->hasRole('student'))
                                            <span class="badge badge-primary">Siswa</span>
                                        @else
                                            <span class="badge badge-light">Lainnya</span>
                                        @endif
                                    </td>
                                    
                                    <td class="align-middle">{{ $peminjaman->barang->nama_barang }}</td>
                                    
                                    <td class="text-center align-middle">{{ $peminjaman->jumlah }}</td>
                                
                                    <td class="align-middle">{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</td>
                                    
                                    <td class="text-center align-middle">
                                        @if($peminjaman->status == 'menunggu')
                                            <span class="badge badge-warning text-dark">Menunggu</span>
                                        @elseif($peminjaman->status == 'disetujui')
                                            <span class="badge badge-info">Disetujui</span>
                                        @elseif($peminjaman->status == 'dipinjam')
                                            <span class="badge badge-primary">Dipinjam</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge badge-success">Dikembalikan</span>
                                        @elseif($peminjaman->status == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center align-middle">
                                        <a href="{{ route('persetujuan-peminjaman.show', $peminjaman->id) }}" class="btn btn-sm btn-info shadow-sm">
                                            <i class="fas fa-eye"></i> Tinjau
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center p-5">
                                        <div class="empty-state" data-height="200">
                                            <div class="empty-state-icon bg-primary mb-3">
                                                <i class="fas fa-inbox"></i>
                                            </div>
                                            <h2>Tidak ada data pengajuan</h2>
                                            <p class="lead">Belum ada Siswa atau Guru yang mengajukan peminjaman perangkat.</p>
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