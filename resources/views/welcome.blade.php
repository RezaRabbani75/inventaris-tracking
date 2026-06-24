<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Pinjam.in — SMK Pelita Nusantara</title>

  <link rel="icon" type="image/png" href="{{ asset('img/logo/logo-beta.png') }}">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

  <style>
    :root {
      /* Disesuaikan dengan aksen warna biru pada gedung di foto */
      --primary-color: #1a73e8; 
      --secondary-color: #0d47a1;
      --accent-color: #002171;
      --light-bg: #f4f7f6;
      --text-color: #333;
    }
    body {
      font-family: 'Poppins', 'Nunito', sans-serif;
      background: var(--light-bg);
      color: var(--text-color);
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }
    /* HERO SECTION */
    .hero {
      min-height: 100vh;
      /* Menambahkan gradient overlay gelap agar teks putih terbaca jelas dengan background terang */
      background: linear-gradient(rgba(0, 32, 74, 0.6), rgba(0, 0, 0, 0.6)), url('/img/background-welcome/background-3.png') no-repeat center center;
      background-size: cover;
      background-attachment: fixed; 
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 0 20px;
      color: #ffffff;
    }
    .hero h1 {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
      letter-spacing: 1px;
    }
    .hero p {
      font-size: 1.25rem;
      margin-bottom: 40px;
      max-width: 700px;
      text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.5);
      line-height: 1.6;
    }
    .btn-cta {
      background-color: var(--primary-color);
      color: #fff;
      padding: 14px 45px;
      border: none;
      border-radius: 50px;
      font-weight: 600;
      font-size: 1.1rem;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(26, 115, 232, 0.4);
    }
    .btn-cta:hover {
      background-color: var(--secondary-color);
      color: #fff;
      transform: translateY(-3px); /* Efek melayang sedikit */
      box-shadow: 0 6px 20px rgba(13, 71, 161, 0.5);
    }
    /* SECTION STYLE */
    .section {
      padding: 90px 20px;
    }
    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 50px;
      color: var(--accent-color);
      position: relative;
    }
    .section-title::after {
      content: '';
      width: 80px;
      height: 4px;
      background-color: var(--primary-color);
      display: block;
      margin: 15px auto 0;
      border-radius: 2px;
    }
    /* FEATURE CARDS */
    .feature-card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background-color: #fff;
      height: 100%;
    }
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    .feature-card i {
      font-size: 3rem;
      color: var(--primary-color);
      margin-bottom: 20px;
      transition: transform 0.3s ease;
    }
    .feature-card:hover i {
      transform: scale(1.1); /* Ikon membesar sedikit saat di-hover */
    }
    .feature-card .card-title {
      font-weight: 600;
      color: var(--accent-color);
    }
    .feature-card .card-text {
      color: #666;
      font-size: 0.95rem;
    }
    /* FOOTER */
    footer {
      background: var(--accent-color);
      color: #fff;
      text-align: center;
      padding: 25px 20px;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }
  </style>
</head>
<body>

  <section class="hero">
    <h1 class="animate__animated animate__fadeInDown">Inventory Tracking<br>SMK Pelita Nusantara</h1>
    <p class="animate__animated animate__fadeInUp animate__delay-1s">
      Aplikasi pelacakan persediaan yang modern dan elegan untuk mengelola barang-barang Anda dengan mudah dan aman. Peminjaman dan Pengembalian dalam satu klik.
    </p>
    <a href="{{ route('login') }}" class="btn btn-cta animate__animated animate__zoomIn animate__delay-2s">Mulai Sekarang</a>
  </section>

  <section class="section" id="features">
    <div class="container">
      <h2 class="section-title text-center" data-aos="fade-up">Fitur Unggulan</h2>
      <div class="row g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="card feature-card p-4 text-center">
            <i class="fas fa-calendar-check"></i>
            <h5 class="card-title mt-3">Peminjaman Mudah</h5>
            <p class="card-text">
              Pengalaman meminjam dan mengembalikan barang dan perangkat yang cepat hanya dalam satu klik saja.
            </p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="card feature-card p-4 text-center">
            <i class="fas fa-boxes"></i>
            <h5 class="card-title mt-3">Pelacakan Barang</h5>
            <p class="card-text">
              Menampilkan jumlah barang tersedia, dipinjam, rusak, maupun hilang secara <em>real-time</em>.
            </p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
          <div class="card feature-card p-4 text-center">
            <i class="fas fa-user-tie"></i>
            <h5 class="card-title mt-3">Administrasi & Operasional</h5>
            <p class="card-text">
              Kelola tingkat persediaan Anda dan dapatkan notifikasi otomatis ketika stok barang hampir habis.
            </p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
          <div class="card feature-card p-4 text-center">
            <i class="fas fa-clipboard-list"></i>
            <h5 class="card-title mt-3">Log Transaksi</h5>
            <p class="card-text">
              Mencatat setiap aktivitas: siapa yang meminjam, kapan dikembalikan, serta transparansi pelaporan kerusakan.
            </p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
          <div class="card feature-card p-4 text-center">
            <i class="fas fa-chart-pie"></i>
            <h5 class="card-title mt-3">Statistik Penggunaan</h5>
            <p class="card-text">
              Pantau statistik peminjaman bulanan, barang terpopuler, dan distribusi kondisi untuk analisis sekolah.
            </p>
          </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
          <div class="card feature-card p-4 text-center">
            <i class="fas fa-file-export"></i>
            <h5 class="card-title mt-3">Export Laporan</h5>
            <p class="card-text">
              Memudahkan pembuatan laporan resmi untuk keperluan sekolah atau audit dengan format Excel & PDF.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <p class="mb-0">&copy; {{ date('Y') }} Inventory Tracking - SMK Pelita Nusantara. All Rights Reserved.</p>
    </div>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({
      once: true,
      duration: 800,
      offset: 100, /* Animasi mulai sedikit lebih awal sebelum elemen terlihat penuh */
    });
  </script>
</body>
</html>