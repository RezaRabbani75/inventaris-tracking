@extends('layouts.app')

@section('title', 'Ubah Peran Pengguna')

@push('style')
    <style>
        /* Memperhalus garis tepi form agar terlihat lebih modern dan elegan */
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
            <h1>Ubah Peran Pengguna</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dasbor</a></div>
                <div class="breadcrumb-item"><a href="{{ route('hakakses.index') }}">Hak Akses</a></div>
                <div class="breadcrumb-item">Ubah</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Ubah Peran untuk {{ $hakakses->name }}</h2>
            <p class="section-lead">
                Ubah peran dan tingkat akses untuk pengguna ini.
            </p>

            <div class="row">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-sm">
                        <form action="{{ route('hakakses.update', $hakakses->id) }}" method="POST">
                            @csrf
                            @method('PUT')
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
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control form-control-outline" value="{{ $hakakses->name }}" disabled>
                                    <small class="form-text text-muted">Nama pengguna tidak dapat diubah dari halaman ini.</small>
                                </div>

                                <div class="form-group">
                                    <label>Alamat Email</label>
                                    <input type="text" class="form-control form-control-outline" value="{{ $hakakses->email }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="role">Peran (Role) <span class="required-asterisk">*</span></label>
                                    @php($selectedRole = $hakakses->getRoleNames()->first() ?? 'user')
                                    <select name="role" id="role" class="form-control form-control-outline @error('role') is-invalid @enderror" required>
                                        <option value="superadmin" {{ $selectedRole === 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                        <option value="teacher" {{ $selectedRole === 'teacher' ? 'selected' : '' }}>Guru (Teacher)</option>
                                        <option value="student" {{ $selectedRole === 'student' ? 'selected' : '' }}>Siswa (Student)</option>
                                        <option value="technician" {{ $selectedRole === 'technician' ? 'selected' : '' }}>Teknisi (Technician)</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="nuptk-group" style="display: none;">
                                    <label for="nuptk">NUPTK <span class="required-asterisk">*</span></label>
                                    <input type="text" name="nuptk" id="nuptk" class="form-control form-control-outline @error('nuptk') is-invalid @enderror" value="{{ old('nuptk', optional($hakakses->teacherProfile)->nuptk) }}" placeholder="Masukkan NUPTK Guru">
                                    @error('nuptk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="nik-group" style="display: none;">
                                    <label for="nik">NIK (Nomor Induk Kependudukan) <span class="required-asterisk">*</span></label>
                                    <input type="text" name="nik" id="nik" class="form-control form-control-outline @error('nik') is-invalid @enderror" value="{{ old('nik', optional($hakakses->studentProfile)->nik) }}" placeholder="Masukkan NIK Siswa">
                                    @error('nik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="id_technician-group" style="display: none;">
                                    <label for="id_technician">ID Teknisi <span class="required-asterisk">*</span></label>
                                    <input type="text" name="id_technician" id="id_technician" class="form-control form-control-outline @error('id_technician') is-invalid @enderror" value="{{ old('id_technician', optional($hakakses->teknisiProfile)->id_teknisi) }}" placeholder="Masukkan ID Teknisi">
                                    @error('id_technician')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="card-footer text-right bg-whitesmoke">
                                <a href="{{ route('hakakses.index') }}" class="btn btn-secondary mr-2">Batal</a>
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
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const nuptkGroup = document.getElementById('nuptk-group');
            const nikGroup = document.getElementById('nik-group');
            const idTechnicianGroup = document.getElementById('id_technician-group');

            function toggleRoleSpecificFields() {
                const selectedRole = roleSelect.value;

                // Sembunyikan semua field dinamis terlebih dahulu
                nuptkGroup.style.display = 'none';
                nikGroup.style.display = 'none';
                idTechnicianGroup.style.display = 'none';

                // Hapus atribut 'required' dari semua field dinamis agar form dapat disubmit jika peran diubah
                document.getElementById('nuptk').removeAttribute('required');
                document.getElementById('nik').removeAttribute('required');
                document.getElementById('id_technician').removeAttribute('required');

                // Tampilkan field spesifik dan tambahkan 'required' berdasarkan peran yang dipilih
                if (selectedRole === 'teacher') {
                    nuptkGroup.style.display = 'block';
                    document.getElementById('nuptk').setAttribute('required', 'required');
                } else if (selectedRole === 'student') {
                    nikGroup.style.display = 'block';
                    document.getElementById('nik').setAttribute('required', 'required');
                } else if (selectedRole === 'technician') {
                    idTechnicianGroup.style.display = 'block';
                    document.getElementById('id_technician').setAttribute('required', 'required');
                }
            }

            // Pasang Event Listener agar form menyesuaikan diri saat peran diubah
            roleSelect.addEventListener('change', toggleRoleSpecificFields);

            // Panggil sekali di awal untuk mengatur tampilan awal form sesuai data yang tersimpan
            toggleRoleSpecificFields();
        });
    </script>
@endpush