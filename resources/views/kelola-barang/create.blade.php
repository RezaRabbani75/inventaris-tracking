@extends('layouts.app')

@section('title', 'Tambah Data Barang')

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
            display: none;
        }
    </style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Barang Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ route('kelola-barang.index') }}">Kelola Barang</a></div>
                <div class="breadcrumb-item active">Tambah</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Form Input Inventaris</h2>
            <p class="section-lead">Pastikan kode barang unik dan data diisi dengan benar.</p>

            <div class="row">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm">
                        <form action="{{ route('kelola-barang.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kode_barang">Kode Barang <span class="required-asterisk">*</span></label>
                                            <input type="text" name="kode_barang" id="kode_barang" class="form-control form-control-outline @error('kode_barang') is-invalid @enderror" value="{{ old('kode_barang') }}" placeholder="Contoh: LAB-KOM-001" required>
                                            @error('kode_barang')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama_barang">Nama Perangkat <span class="required-asterisk">*</span></label>
                                            <input type="text" name="nama_barang" id="nama_barang" class="form-control form-control-outline @error('nama_barang') is-invalid @enderror" value="{{ old('nama_barang') }}" placeholder="Contoh: Monitor Samsung 24 Inch" required>
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
                                                <option value="" disabled selected>Pilih Kategori...</option>
                                                <option value="Komputer" {{ old('kategori') == 'Komputer' ? 'selected' : '' }}>Komputer & PC</option>
                                                <option value="Jaringan" {{ old('kategori') == 'Jaringan' ? 'selected' : '' }}>Perangkat Jaringan</option>
                                                <option value="Alat Praktikum" {{ old('kategori') == 'Alat Praktikum' ? 'selected' : '' }}>Alat Praktikum</option>
                                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                            </select>
                                            @error('kategori')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total_stok">Total Stok Fisik <span class="required-asterisk">*</span></label>
                                            <input type="number" name="total_stok" id="total_stok" class="form-control form-control-outline @error('total_stok') is-invalid @enderror" value="{{ old('total_stok') }}" min="1" required>
                                            <small class="form-text text-muted">Stok tersedia akan otomatis disamakan dengan total stok.</small>
                                            @error('total_stok')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" id="deskripsi" class="form-control form-control-outline @error('deskripsi') is-invalid @enderror" style="height: 100px;">{{ old('deskripsi') }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="foto">Foto Perangkat</label>
                                    <div class="mb-2">
                                        <div class="image-preview-container">
                                            <span id="preview-text" class="text-muted">Pratinjau Foto</span>
                                            <img id="image-preview" src="#" alt="Pratinjau">
                                        </div>
                                    </div>
                                    <input type="file" name="foto" id="foto" class="form-control-file @error('foto') is-invalid @enderror" accept="image/*" onchange="previewImage(this)">
                                    <small class="form-text text-muted">Maksimal ukuran file 2MB (Format: JPG, JPEG, PNG).</small>
                                    @error('foto')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="card-footer text-right bg-whitesmoke">
                                <a href="{{ route('kelola-barang.index') }}" class="btn btn-secondary mr-2 text-dark">Batal</a>
                                <button type="submit" class="btn btn-primary shadow-sm">
                                    <i class="fas fa-save mr-1"></i> Simpan Data
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
                previewText.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
            previewText.style.display = 'block';
        }
    }
</script>
@endpush