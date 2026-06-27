@extends('layouts.app')

@section('title', 'Ubah Data Barang')

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
        .image-preview-container {
            width: 100%;
            max-width: 250px;
            height: 250px;
            border: 2px dashed #ced4da;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .image-preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Ubah Data Barang</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('kelola-barang.index') }}">Kelola Barang</a></div>
                <div class="breadcrumb-item active">Ubah</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Perbarui Inventaris: {{ $barang->nama_barang }}</h2>
            <p class="section-lead">Ubah informasi perangkat Lab di bawah ini.</p>

            <div class="row">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm">
                        <form action="{{ route('kelola-barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode_barang">Kode Barang <span class="required-asterisk">*</span></label>
                                            <input type="text" name="kode_barang" id="kode_barang" class="form-control form-control-outline @error('kode_barang') is-invalid @enderror" value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                                            @error('kode_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Perangkat <span class="required-asterisk">*</span></label>
                                            <input type="text" name="nama_barang" id="nama_barang" class="form-control form-control-outline @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                                            @error('nama_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kategori">Kategori <span class="required-asterisk">*</span></label>
                                            <select name="kategori" id="kategori" class="form-control form-control-outline @error('kategori') is-invalid @enderror" required>
                                                <option value="Komputer" {{ (old('kategori', $barang->kategori) == 'Komputer') ? 'selected' : '' }}>Komputer & PC</option>
                                                <option value="Jaringan" {{ (old('kategori', $barang->kategori) == 'Jaringan') ? 'selected' : '' }}>Perangkat Jaringan</option>
                                                <option value="Alat Praktikum" {{ (old('kategori', $barang->kategori) == 'Alat Praktikum') ? 'selected' : '' }}>Alat Praktikum</option>
                                                <option value="Lainnya" {{ (old('kategori', $barang->kategori) == 'Lainnya') ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('kategori')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_stok">Total Stok Fisik <span class="required-asterisk">*</span></label>
                                            <input type="number" name="total_stok" id="total_stok" class="form-control form-control-outline @error('total_stok') is-invalid @enderror" value="{{ old('total_stok', $barang->total_stok) }}" min="1" required>
                                            <small class="form-text text-warning"><i class="fas fa-exclamation-triangle"></i> Mengubah total stok mungkin akan mempengaruhi perhitungan stok tersedia.</small>
                                            @error('total_stok')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" id="deskripsi" class="form-control form-control-outline @error('deskripsi') is-invalid @enderror" style="height: 100px;">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="foto">Foto Perangkat (Opsional)</label>
                                    <div class="mb-2">
                                        <div class="image-preview-container">
                                            @if($barang->foto)
                                                <img id="image-preview" src="{{ asset('img/barang/' . $barang->foto) }}" alt="Pratinjau Foto Lama" style="display: block;">
                                                <span id="preview-text" class="text-muted" style="display: none;">Pratinjau Foto Baru</span>
                                            @else
                                                <img id="image-preview" src="#" alt="Pratinjau" style="display: none;">
                                                <span id="preview-text" class="text-muted">Belum ada foto</span>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="file" name="foto" id="foto" class="form-control-file @error('foto') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                                    <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                                    @error('foto')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="card-footer text-right bg-whitesmoke">
                                <a href="{{ route('kelola-barang.index') }}" class="btn btn-secondary mr-2">Batal</a>
                                <button type="submit" class="btn btn-primary shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
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

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const previewText = document.getElementById('preview-text');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if(previewText) previewText.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush