<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Masuk — Pinjam.in</title>
  <link rel="icon" type="image/png" href="{{ asset('img/logo/logo-beta.png') }}">

  <!-- Google Fonts: Poppins & Nunito -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap 5.3 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

  <style>
    :root {
      --primary-color: #6c63ff;
      --secondary-color: #3f3da1;
      --accent-color: #2b2a82;
      --light-bg: #f9f9f9;
      --text-color: #333;
    }

    body {
      font-family: 'Poppins', 'Nunito', sans-serif;
      color: var(--text-color);
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;

      background:
        linear-gradient(135deg, rgba(108, 99, 255, 0.12), rgba(63, 61, 161, 0.12)),
        url("{{ asset('img/background-login/background-login.png') }}") center / cover no-repeat;
      background-color: #eeedf8;
    }

    .login-container {
      background: #fff;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
      width: 100%;
      max-width: 450px;
    }

    .login-header h2 {
      font-weight: 700;
      color: var(--secondary-color);
      margin-bottom: 20px;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.25rem rgba(108, 99, 255, 0.25);
    }

    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      padding: 12px;
      font-weight: 600;
      transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .btn-link {
      color: var(--primary-color);
      text-decoration: none;
    }

    .btn-link:hover {
      color: var(--secondary-color);
    }

    /* Password toggle */
    .password-wrapper {
      position: relative;
    }

    .password-wrapper .form-control {
      padding-right: 2.75rem; /* make room for the eye icon */
    }

    .password-toggle {
      position: absolute;
      top: 50%;
      right: 0.75rem;
      transform: translateY(-50%);
      background: none;
      border: none;
      padding: 0;
      cursor: pointer;
      color: #aaa;
      line-height: 1;
      display: flex;
      align-items: center;
    }

    .password-toggle:hover {
      color: var(--primary-color);
    }

    /* Shift the toggle up slightly when there is a validation error message below the field */
    .password-wrapper .form-control.is-invalid ~ .password-toggle {
      top: calc(50% - 0.6rem);
    }
  </style>
</head>
<body>

  <div class="login-container animate__animated animate__fadeIn">
    <div class="login-header text-center">
      <h2>Login</h2>
    </div>

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>
        <input
          id="email"
          type="email"
          class="form-control @error('email') is-invalid @enderror"
          name="email"
          value="{{ old('email') }}"
          required
          autocomplete="email"
          autofocus
        >
        @error('email')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <div class="password-wrapper">
          <input
            id="password"
            type="password"
            class="form-control @error('password') is-invalid @enderror"
            name="password"
            required
            autocomplete="current-password"
          >
          <button
            type="button"
            class="password-toggle"
            id="togglePassword"
            aria-label="Tampilkan/sembunyikan password"
          >
            <i class="fas fa-eye" id="toggleIcon"></i>
          </button>
        </div>
        @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
        @enderror
      </div>

      <div class="mb-3 form-check">
        <input
          class="form-check-input"
          type="checkbox"
          name="remember"
          id="remember"
          {{ old('remember') ? 'checked' : '' }}
        >
        <label class="form-check-label" for="remember">
          {{ __('Ingatkan Saya') }}
        </label>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary">
          {{ __('Masuk') }}
        </button>
      </div>

      <div class="text-center mt-3">
        @if (Route::has('password.request'))
          <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Lupa Password?') }}
          </a>
        @endif
      </div>
    </form>
  </div>

  <!-- JS Libraries -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const toggleBtn  = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    toggleBtn.addEventListener('click', function () {
      const isPassword = passwordField.type === 'password';
      passwordField.type = isPassword ? 'text' : 'password';
      toggleIcon.classList.toggle('fa-eye',      !isPassword);
      toggleIcon.classList.toggle('fa-eye-slash', isPassword);
    });
  </script>
</body>
</html>