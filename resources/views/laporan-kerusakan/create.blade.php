@extends('layouts.app')

@section('title', 'Buat Laporan Kerusakan Perangkat')

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <a href="{{ route('laporan-kerusakan.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
        </div>
        <h1>Buat Laporan Kerusakan Perangkat</h1>
    </div>

    <div class="section-body">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header border-bottom">
                        <h4>Formulir Detail Kerusakan Perangkat</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporan-kerusakan.store') }}" method="POST">
                            @csrf

                            {{-- SKENARIO A: Jika laporan berdasarkan transaksi peminjaman --}}
                            @if($peminjaman)
                                <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">
                                <input type="hidden" name="barang_id" value="{{ $peminjaman->barang_id }}">

                                <div class="form-group">
                                    <label>Perangkat Terkait (Dari Peminjaman)</label>
                                    <input type="text" class="form-control" value="{{ $peminjaman->barang->nama_barang }}" disabled>
                                    <small class="text-muted">Terkait dengan kode transaksi peminjaman #{{ $peminjaman->id }}</small>
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_rusak">Jumlah yang Rusak <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_rusak" id="jumlah_rusak" 
                                           class="form-control @error('jumlah_rusak') is-invalid @enderror" 
                                           value="{{ old('jumlah_rusak', 1) }}" min="1" max="{{ $peminjaman->jumlah }}" required>
                                    <small class="text-muted">Maksimal jumlah rusak: {{ $peminjaman->jumlah }} unit (sesuai jumlah yang kamu pinjam).</small>
                                    @error('jumlah_rusak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            {{-- SKENARIO B: Jika laporan acak langsung dari rak lab --}}
                            @else
                                <div class="form-group">
                                    <label for="barang_id">Pilih Perangkat di Rak Lab <span class="text-danger">*</span></label>
                                    <select name="barang_id" id="barang_id" class="form-control selectric @error('barang_id') is-invalid @enderror" required>
                                        <option value="" disabled selected>-- Pilih Perangkat --</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                                {{ $barang->nama_barang }} (Sisa Stok Tersedia: {{ $barang->stok_tersedia }} unit)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('barang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="jumlah_rusak">Jumlah yang Rusak <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_rusak" id="jumlah_rusak" 
                                           class="form-control @error('jumlah_rusak') is-invalid @enderror" 
                                           value="{{ old('jumlah_rusak', 1) }}" min="1" required>
                                    <small class="text-muted">Pastikan jumlah yang dimasukkan tidak melebihi stok fisik di rak lab.</small>
                                    @error('jumlah_rusak')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="deskripsi_kerusakan">Deskripsi Kronologi & Detail Kerusakan <span class="text-danger">*</span></label>
                                <textarea name="deskripsi_kerusakan" id="deskripsi_kerusakan" 
                                          class="form-control @error('deskripsi_kerusakan') is-invalid @enderror" 
                                          style="height: 120px" placeholder="Contoh: Port LAN nomor 3 mati setelah digunakan praktikum, atau Layar bergaris saat dinyalakan." required>{{ old('deskripsi_kerusakan') }}</textarea>
                                @error('deskripsi_kerusakan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0 text-right">
                                <a href="{{ route('laporan-kerusakan.index') }}" class="btn btn-secondary mr-2 text-black">Batal</a>
                                <button type="submit" class="btn btn-primary shadow-sm">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Laporan Kerusakan
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