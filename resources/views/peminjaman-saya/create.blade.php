@extends('layouts.app')

@section('title', 'Formulir Pengajuan Peminjaman')

@push('style')
    <style>
        .form-control-outline {
            border: 1px solid #ced4da;
            box-shadow: none;
            transition: border-color 0.3s ease;
        }
        .form-control-outline:focus {
            border-color: #6777ef;
        }
        .required-asterisk {
            color: #fc544b;
            margin-left: 3px;
        }
        .btn-submit-custom {
            color: #ffffff !important;
            font-weight: 600;
        }
        .item-summary-card {
            background-color: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        html[data-bs-theme="dark"] .item-summary-card {
            background-color: #2b2b40;
            border-color: #3f3f5a;
        }
    </style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Ajukan Peminjaman</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Dasbor</a></div>
                <div class="breadcrumb-item"><a href="{{ url('katalog') }}">Katalog</a></div>
                <div class="breadcrumb-item active">Ajukan Pinjam</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Formulir Peminjaman Perangkat</h2>
            <p class="section-lead">
                Harap isi detail peminjaman dengan benar. Pastikan rencana tanggal kembali sesuai dengan kebutuhan kegiatan Anda.
            </p>

            <div class="row">
                <div class="col-12 col-lg-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header">
                            <h4>Perangkat Terpilih</h4>
                        </div>
                        <div class="card-body text-center d-flex flex-column align-items-center">
                            <div class="item-summary-card p-3 w-100 mb-3">
                                @if($barang->foto)
                                    <img src="{{ asset('img/barang/' . $barang->foto) }}" alt="{{ $barang->nama_barang }}" class="img-fluid rounded mb-3 shadow-sm" style="max-height: 180px; object-fit: cover;">
                                @else
                                    <div class="w-100 d-flex flex-column justify-content-center align-items-center text-muted rounded mb-3" style="height: 180px;">
                                        <i class="fas fa-box-open fa-4x mb-2"></i>
                                    </div>
                                @endif
                                <h6 class="text-dark font-weight-bold mb-1">{{ $barang->nama_barang }}</h6>
                                <span class="badge badge-primary mb-3">{{ $barang->kode_barang }}</span>
                                <div class="border-top pt-2 mt-2 w-100">
                                    <span class="text-muted d-block" style="font-size: 0.85rem;">Stok Tersedia:</span>
                                    <span class="h5 text-success font-weight-bold">{{ $barang->stok_tersedia }} unit</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-8 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <form action="{{ route('peminjaman-saya.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="barang_id" value="{{ $barang->id }}">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="jumlah">Jumlah Pinjam <span class="required-asterisk">*</span></label>
                                            <input type="number" name="jumlah" id="jumlah" class="form-control form-control-outline @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', 1) }}" min="1" max="{{ $barang->stok_tersedia }}" required>
                                            <small class="form-text text-muted">Maksimal perangkat yang dapat Anda pinjam saat ini adalah <strong>{{ $barang->stok_tersedia }} unit</strong>.</small>
                                            @error('jumlah')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_pinjam">Tanggal Pinjam <span class="required-asterisk">*</span></label>
                                            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control form-control-outline @error('tanggal_pinjam') is-invalid @enderror" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                                            @error('tanggal_pinjam')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_kembali_rencana">Rencana Tanggal Kembali <span class="required-asterisk">*</span></label>
                                            <input type="date" name="tanggal_kembali_rencana" id="tanggal_kembali_rencana" class="form-control form-control-outline @error('tanggal_kembali_rencana') is-invalid @enderror" value="{{ old('tanggal_kembali_rencana', date('Y-m-d', strtotime('+1 day'))) }}" required>
                                            @error('tanggal_kembali_rencana')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tujuan_pinjam">Tujuan Peminjaman <span class="required-asterisk">*</span></label>
                                    <textarea name="tujuan_pinjam" id="tujuan_pinjam" class="form-control form-control-outline @error('tujuan_pinjam') is-invalid @enderror" style="height: 100px;" placeholder="Jelaskan kegiatan atau keperluan Anda menggunakan perangkat ini..." required>{{ old('tujuan_pinjam') }}</textarea>
                                    @error('tujuan_pinjam')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="card-footer text-right bg-whitesmoke">
                                <a href="{{ route('katalog.index') }}" class="btn btn-secondary mr-2 font-weight-bold text-dark">Batal</a>
                                <button type="submit" class="btn btn-primary btn-submit-custom shadow-sm">
                                    <i class="fas fa-paper-plane mr-1"></i> Ajukan Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection