@extends('layouts.app')

@section('title', 'Statistik Laporan Peminjaman')

@section('content')

    <div class="section-body">
        
        <div class="card shadow-sm mb-4">
            <div class="card-body py-4">
                <h2 class="text-dark font-weight-bold mb-2" style="font-size: 1.5rem; letter-spacing: -0.5px;">
                    Ringkasan & Filter Laporan
                </h2>
                <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.6;">
                    Halaman ini menampilkan grafik statistik rangkuman data serta rekapan seluruh berkas transaksi peminjaman lab.
                </p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ url('statistik') }}" method="GET" class="row align-items-end">
                    <div class="form-group col-md-4 mb-0">
                        <label class="font-weight-bold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="form-group col-md-4 mb-0">
                        <label class="font-weight-bold">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="form-group col-md-4 mb-0 mt-3 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-block px-4">
                            <i class="fas fa-filter mr-1"></i> Filter Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Pengajuan</h4>
                        </div>
                        <div class="card-body">
                            {{ $statistik['total_pengajuan'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Menunggu / Dipinjam</h4>
                        </div>
                        <div class="card-body">
                            {{ $statistik['menunggu'] + $statistik['dipinjam'] }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Selesai Dikembalikan</h4>
                        </div>
                        <div class="card-body">
                            {{ $statistik['dikembalikan'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4>Top 5 Barang Sering Dipinjam</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="chartBarang"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4>Top 5 User Paling Aktif Meminjam</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="chartUser"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-header">
                <h4>Detail Transaksi Berdasarkan Filter</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-md">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Peminjam</th>
                                <th>Perangkat/Barang</th>
                                <th>Jumlah</th>
                                <th>Tanggal Pinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($peminjamans->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        Tidak ditemukan transaksi pada rentang tanggal tersebut.
                                    </td>
                                </tr>
                            @else
                                @foreach($peminjamans as $index => $pinjam)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pinjam->user->name ?? 'User Terhapus' }}</td>
                                        <td>{{ $pinjam->barang->nama_barang ?? 'Barang Terhapus' }}</td>
                                        <td>{{ $pinjam->jumlah }} Unit</td>
                                        <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->translatedFormat('d F Y') }}</td>
                                        <td>
                                            @if($pinjam->status == 'menunggu')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @elseif($pinjam->status == 'disetujui')
                                                <span class="badge badge-info">Disetujui</span>
                                            @elseif($pinjam->status == 'dipinjam')
                                                <span class="badge badge-primary">Dipinjam</span>
                                            @elseif($pinjam->status == 'dikembalikan')
                                                <span class="badge badge-success">Dikembalikan</span>
                                            @else
                                                <span class="badge badge-danger">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Grafik Barang yang Sering Dipinjam
        var ctxBarang = document.getElementById("chartBarang").getContext('2d');
        var chartBarang = new Chart(ctxBarang, {
            type: 'bar', // Gunakan 'pie' atau 'doughnut' jika ingin bentuk lingkaran
            data: {
                labels: {!! json_encode($chartBarangLabels ?? ['Belum Ada Data']) !!},
                datasets: [{
                    label: 'Total Dipinjam (Unit)',
                    data: {!! json_encode($chartBarangData ?? [0]) !!},
                    backgroundColor: 'rgba(103, 119, 239, 0.7)', // Warna Primary Stisla
                    borderColor: 'rgba(103, 119, 239, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // 2. Grafik User yang Sering Meminjam
        var ctxUser = document.getElementById("chartUser").getContext('2d');
        var chartUser = new Chart(ctxUser, {
            type: 'bar', 
            data: {
                labels: {!! json_encode($chartUserLabels ?? ['Belum Ada Data']) !!},
                datasets: [{
                    label: 'Frekuensi Meminjam',
                    data: {!! json_encode($chartUserData ?? [0]) !!},
                    backgroundColor: 'rgba(71, 195, 99, 0.7)', // Warna Success Stisla
                    borderColor: 'rgba(71, 195, 99, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    });
</script>
@endpush