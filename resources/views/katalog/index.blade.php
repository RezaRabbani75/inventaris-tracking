@extends('layouts.app')

@section('title', 'Katalog Perangkat Lab')

@push('style')
    <style>
        .card-product {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }
        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1) !important;
        }
        .product-image-wrapper {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background-color: #f8f9fa;
            position: relative;
        }
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .badge-category {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
    </style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Katalog Perangkat Lab</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item">Katalog</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Cari & Pinjam Perangkat</h2>
            <p class="section-lead">Pilih perangkat Lab yang tersedia di bawah ini untuk menunjang kegiatan belajar mengajar atau praktikum.</p>

            <div class="row mb-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <form action="{{ url('katalog') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama perangkat atau kategori..." value="{{ $search }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                @forelse($barangs as $barang)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex align-items-stretch">
                        <div class="card card-product w-100 shadow-sm border-0 mb-4">
                            <div class="product-image-wrapper">
                                <span class="badge badge-primary badge-category">{{ $barang->kategori }}</span>
                                @if($barang->foto)
                                    <img src="{{ asset('img/barang/' . $barang->foto) }}" alt="{{ $barang->nama_barang }}" class="product-image">
                                @else
                                    <div class="w-100 h-100 d-flex flex-column justify-content-center align-items-center text-muted">
                                        <i class="fas fa-box fa-3x mb-2"></i>
                                        <small>Tidak ada foto</small>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <span class="text-muted style-code font-weight-bold" style="font-size: 0.75rem;">{{ $barang->kode_barang }}</span>
                                <h5 class="card-title mt-1 mb-2 text-dark" style="font-size: 1rem; line-height: 1.4; height: 42px; overflow: hidden;">
                                    {{ $barang->nama_barang }}
                                </h5>
                                
                                <div class="mt-auto border-top pt-2">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted" style="font-size: 0.85rem;">Stok Tersedia:</span>
                                        @if($barang->stok_tersedia > 0)
                                            <span class="badge badge-success font-weight-bold">{{ $barang->stok_tersedia }} unit</span>
                                        @else
                                            <span class="badge badge-danger font-weight-bold">Habis</span>
                                        @endif
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ route('katalog.show', $barang->id) }}" class="btn btn-sm btn-outline-secondary btn-block">Detail</a>
                                        </div>
                                        <div class="col-6">
                                            @if($barang->stok_tersedia > 0)
                                                <a href="{{ route('peminjaman-saya.create', ['barang_id' => $barang->id]) }}" class="btn btn-sm btn-primary btn-block shadow-sm">
                                                    <i class="fas fa-hand-holding mr-1"></i> Pinjam
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-danger btn-block" disabled>Habis</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-boxes fa-4x mb-3" style="color: #ced4da;"></i>
                        <h5>Perangkat Lab tidak ditemukan</h5>
                        <p>Belum ada data barang atau keyword pencarian Anda tidak cocok.</p>
                        @if($search)
                            <a href="{{ url('katalog') }}" class="btn btn-sm btn-secondary mt-2">Kembali ke Katalog</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection