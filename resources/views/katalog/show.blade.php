@extends('layouts.app')

@section('title', 'Detail Perangkat Lab')

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Perangkat</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ url('katalog') }}">Katalog</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-5">
                    <div class="card shadow-sm border-0 overflow-hidden" style="border-radius: 12px;">
                        @if($barang->foto)
                            <img src="{{ asset('img/barang/' . $barang->foto) }}" alt="{{ $barang->nama_barang }}" class="w-100" style="object-fit: cover; height: 350px;">
                        @else
                            <div class="w-100 d-flex flex-column justify-content-center align-items-center bg-light text-muted" style="height: 350px;">
                                <i class="fas fa-box fa-5x mb-3"></i>
                                <span>Tidak ada foto perangkat</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-12 col-md-7">
                    <div class="card shadow-sm border-0 p-3" style="border-radius: 12px;">
                        <div class="card-body">
                            <span class="badge badge-primary mb-2">{{ $barang->kategori }}</span>
                            <h2 class="text-dark font-weight-bold mb-1">{{ $barang->nama_barang }}</h2>
                            <p class="text-muted mb-3">Kode Barang: <strong class="text-primary">{{ $barang->kode_barang }}</strong></p>
                            
                            <hr>
                            
                            <h5 class="text-dark mt-3">Status Inventaris Saat Ini:</h5>
                            <div class="row text-center my-3">
                                <div class="col-6 col-sm-4 mb-2">
                                    <div class="bg-light p-3 rounded shadow-sm">
                                        <small class="text-muted d-block font-weight-bold">Total Stok</small>
                                        <span class="h4 font-weight-bold text-dark">{{ $barang->total_stok }}</span>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 mb-2">
                                    <div class="p-3 rounded shadow-sm {{ $barang->stok_tersedia > 0 ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                        <small class="d-block font-weight-bold">Tersedia</small>
                                        <span class="h4 font-weight-bold">{{ $barang->stok_tersedia }}</span>
                                    </div>
                                </div>
                            </div>

                            <h5 class="text-dark mt-4">Deskripsi Perangkat:</h5>
                            <p class="text-secondary text-dark" style="line-height: 1.6;">
                                {!! nl2br(e($barang->deskripsi ?? 'Tidak ada deskripsi tambahan mengenai perangkat lab ini.')) !!}
                            </p>

                            <div class="mt-4 border-top pt-3 text-right">
                                <a href="{{ url('katalog') }}" class="btn btn-secondary mr-2 text-dark">Kembali</a>
                                @if($barang->stok_tersedia > 0)
                                    <a href="{{ route('peminjaman-saya.create', ['barang_id' => $barang->id]) }}" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-hand-holding mr-1"></i> Ajukan Peminjaman</a>
                                @else
                                    <button class="btn btn-danger px-4" disabled>Stok Habis</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection