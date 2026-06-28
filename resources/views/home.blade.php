@extends('layouts.app')

@section('title', 'Beranda')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dasboard</h1>
        </div>

        <div class="section-body">
            <h2 class="section-title">Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}!</h2>
            <p class="section-lead">Ini adalah halaman dasboard utama Inventory Lab. Semua status dan kondisi barang bisa dilihat di sini.</p>

            <div class="row">
                
                <!-- TAMPILAN KHUSUS SUPERADMIN -->
                @role('superadmin')
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 shadow-sm">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Perangkat</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['total_barang'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 shadow-sm">
                        <div class="card-icon bg-info">
                            <i class="fas fa-hand-holding"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Sedang Dipinjam</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['sedang_dipinjam'] }}
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
                                <h4>Tersedia</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['perangkat_tersedia'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 shadow-sm">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-tools"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Dalam Perbaikan</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['sedang_diperbaiki'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1 shadow-sm">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Rusak Total</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['perangkat_afkir'] }}
                            </div>
                        </div>
                    </div>
                </div>
                @endrole

                <!-- TAMPILAN KHUSUS GURU & SISWA -->
                @hasanyrole('teacher|student')
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card card-statistic-1 shadow-sm">
                        <div class="card-icon bg-info">
                            <i class="fas fa-hand-holding"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pinjaman Aktif Saya</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['pinjaman_aktif'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card card-statistic-1 shadow-sm">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Laporan Kerusakan Saya</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['laporan_dibuat'] }}
                            </div>
                        </div>
                    </div>
                </div>
                @endhasanyrole

                <!-- TAMPILAN KHUSUS TEKNISI -->
                @role('technician')
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card card-statistic-1 shadow-sm">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-wrench"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Antrean Perbaikan</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['tugas_perbaikan'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card card-statistic-1 shadow-sm">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Selesai Hari Ini</h4>
                            </div>
                            <div class="card-body">
                                {{ $data['selesai_hari_ini'] }}
                            </div>
                        </div>
                    </div>
                </div>
                @endrole

            </div>
        </div>
    </section>
</div>
@endsection