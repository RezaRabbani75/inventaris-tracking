@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@push('style')
    <style>
        .form-control-outline {
            border: 1px solid #ced4da;
            box-shadow: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control-outline:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.25);
        }
        .required-asterisk {
            color: #fc544b;
            margin-left: 3px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Pengguna</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('hakakses.index') }}">Hak Akses</a></div>
                    <div class="breadcrumb-item">Tambah Pengguna</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Tambah Pengguna Baru</h2>
                <p class="section-lead">
                    Isi formulir di bawah ini untuk menambahkan akun pengguna baru ke dalam sistem.
                </p>

                <div class="row">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="card shadow-sm">
                            <form method="POST" action="{{ route('hakakses.store') }}">
                                @csrf
                                <div class="card-header">
                                    <h4>Detail Pengguna</h4>
                                </div>
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-circle mr-2" style="font-size: 1.2rem;"></i>
                                                <strong>Terdapat kesalahan:</strong>
                                            </div>
                                            <ul class="mb-0 mt-2">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="name">Nama Lengkap <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control form-control-outline @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap pengguna" required autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="email">Alamat Email <span class="required-asterisk">*</span></label>
                                        <input type="email" class="form-control form-control-outline @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="johnndoe@gmail.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="password">Kata Sandi <span class="required-asterisk">*</span></label>
                                        <input type="password" class="form-control form-control-outline @error('password') is-invalid @enderror" id="password" name="password" placeholder="Buat kata sandi untuk pengguna" required>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="role">Peran (Role) <span class="required-asterisk">*</span></label>
                                        <select class="form-control form-control-outline @error('role') is-invalid @enderror" id="role" name="role" required>
                                            <option value="">-- Pilih Peran --</option>
                                            <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Guru (Teacher)</option>
                                            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Siswa (Student)</option>
                                            <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>Teknisi (Technician)</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" id="nik_field" style="display: none;">
                                        <label for="nik">NIK (Nomor Induk Kependudukan) <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control form-control-outline @error('nik') is-invalid @enderror" id="nik" name="nik" value="{{ old('nik') }}" placeholder="Masukkan NIK Siswa">
                                        @error('nik')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" id="nuptk_field" style="display: none;">
                                        <label for="nuptk">NUPTK <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control form-control-outline @error('nuptk') is-invalid @enderror" id="nuptk" name="nuptk" value="{{ old('nuptk') }}" placeholder="Masukkan NUPTK Guru">
                                        @error('nuptk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group" id="id_technician_field" style="display: none;">
                                        <label for="id_technician">ID Teknisi <span class="required-asterisk">*</span></label>
                                        <input type="text" class="form-control form-control-outline @error('id_technician') is-invalid @enderror" id="id_technician" name="id_technician" value="{{ old('id_technician') }}" placeholder="Masukkan ID Teknisi">
                                        @error('id_technician')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                </div>
                                <div class="card-footer text-right bg-whitesmoke">
                                    <a href="{{ route('hakakses.index') }}" class="btn btn-secondary mr-2">Batal</a>
                                    <button type="submit" class="btn btn-primary shadow-sm"><i class="fas fa-save mr-1"></i> Simpan Pengguna</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const nikField = document.getElementById('nik_field');
            const nuptkField = document.getElementById('nuptk_field');
            const technicianIdField = document.getElementById('id_technician_field');

            const nikInput = document.getElementById('nik');
            const nuptkInput = document.getElementById('nuptk');
            const technicianInput = document.getElementById('id_technician');

            function toggleFields() {
                nikField.style.display = 'none';
                nuptkField.style.display = 'none';
                technicianIdField.style.display = 'none';

                nikInput.removeAttribute('required');
                nuptkInput.removeAttribute('required');
                technicianInput.removeAttribute('required');

                const selectedRole = roleSelect.value;

                if (selectedRole === 'teacher') {
                    nuptkField.style.display = 'block';
                    nuptkInput.setAttribute('required', 'required');
                    nikInput.value = '';
                    technicianInput.value = '';
                } else if (selectedRole === 'student') {
                    nikField.style.display = 'block';
                    nikInput.setAttribute('required', 'required');
                    nuptkInput.value = '';
                    technicianInput.value = '';
                } else if (selectedRole === 'technician') {
                    technicianIdField.style.display = 'block';
                    technicianInput.setAttribute('required', 'required');
                    nikInput.value = '';
                    nuptkInput.value = '';
                }
            }

            roleSelect.addEventListener('change', toggleFields);

            toggleFields();
        });
    </script>
    @endpush
@endsection