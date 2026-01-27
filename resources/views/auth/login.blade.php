@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-wrapper">
        <!-- Logo & Heading -->
        <div class="login-header mb-4">
            <div class="logo-section text-center mb-3">
                <div class="logo-circle">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <h1 class="login-title">TDI Service</h1>
            <p class="login-subtitle">Sistem Presensi Peserta Magang</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <div class="card-header-custom">
                <h4 class="mb-0">
                    <i class="fas fa-sign-in-alt me-2"></i> Masuk ke Akun Anda
                </h4>
            </div>

            <div class="card-body-custom">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group-custom mb-4">
                        <label for="email" class="form-label-custom">Email Address</label>
                        <div class="input-group-custom">
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input
                                id="email"
                                type="email"
                                class="form-control-custom @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="your@email.com"
                                required
                                autocomplete="email"
                                autofocus>
                        </div>
                        @error('email')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group-custom mb-4">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="input-group-custom">
                            <span class="input-icon">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                id="password"
                                type="password"
                                class="form-control-custom @error('password') is-invalid @enderror"
                                name="password"
                                placeholder="Masukkan password Anda"
                                required
                                autocomplete="current-password">
                        </div>
                        @error('password')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="form-group-custom mb-4">
                        <div class="form-check-custom">
                            <input
                                class="form-check-input-custom"
                                type="checkbox"
                                name="remember"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label-custom" for="remember">
                                Ingat saya
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-login-custom w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i> Masuk
                    </button>

                    <!-- Divider -->
                    <div class="divider-custom mb-3">
                        <span>atau</span>
                    </div>

                    <!-- Links -->
                    <div class="links-section">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link-custom">
                                <i class="fas fa-question-circle me-1"></i> Lupa Password?
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <div class="register-link mt-2">
                                Belum punya akun?
                                <a href="{{ route('register') }}" class="link-custom-primary">
                                    Daftar di sini
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="login-footer">
            <p class="text-muted small text-center">
                <i class="fas fa-shield-alt me-1"></i> Data Anda aman bersama kami
            </p>
        </div>
    </div>
</div>

<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-wrapper {
        width: 100%;
        max-width: 450px;
    }

    .login-header {
        text-align: center;
    }

    .logo-circle {
        width: 80px;
        height: 80px;
        background: #0d6efd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 40px;
        color: white;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }

    .login-title {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .login-subtitle {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
    }

    .login-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header-custom {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 24px;
        color: #212529;
    }

    .card-header-custom h4 {
        font-size: 18px;
        font-weight: 600;
    }

    .card-body-custom {
        padding: 32px;
    }

    .form-group-custom {
        margin-bottom: 20px;
    }

    .form-label-custom {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #495057;
    }

    .input-group-custom {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon {
        position: absolute;
        left: 14px;
        color: #adb5bd;
        font-size: 16px;
        z-index: 10;
    }

    .form-control-custom {
        width: 100%;
        padding: 12px 14px 12px 40px;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #ffffff;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        background: #ffffff;
    }

    .form-control-custom.is-invalid {
        border-color: #dc3545;
    }

    .form-control-custom.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
    }

    .form-check-custom {
        display: flex;
        align-items: center;
    }

    .form-check-input-custom {
        width: 18px;
        height: 18px;
        margin-right: 8px;
        cursor: pointer;
        border: 1.5px solid #dee2e6;
        border-radius: 4px;
        appearance: none;
        background: white;
        transition: all 0.3s ease;
    }

    .form-check-input-custom:hover {
        border-color: #0d6efd;
    }

    .form-check-input-custom:checked {
        background: #0d6efd;
        border-color: #0d6efd;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 8l4 4 8-8'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100% 100%;
    }

    .form-check-label-custom {
        margin-bottom: 0;
        cursor: pointer;
        font-size: 14px;
        color: #495057;
    }

    .error-message {
        font-size: 13px;
        color: #dc3545;
        display: flex;
        align-items: center;
    }

    .btn-login-custom {
        background: #0d6efd;
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-login-custom:hover {
        background: #0b5ed7;
        color: white;
        text-decoration: none;
    }

    .btn-login-custom:active {
        background: #0a58ca;
    }

    .divider-custom {
        display: flex;
        align-items: center;
        margin: 24px 0;
    }

    .divider-custom::before,
    .divider-custom::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e9ecef;
    }

    .divider-custom span {
        padding: 0 12px;
        color: #adb5bd;
        font-size: 13px;
    }

    .links-section {
        text-align: center;
    }

    .link-custom {
        display: inline-block;
        color: #6c757d;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.3s ease;
    }

    .link-custom:hover {
        color: #0d6efd;
        text-decoration: none;
    }

    .link-custom-primary {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .link-custom-primary:hover {
        color: #0b5ed7;
        text-decoration: none;
    }

    .register-link {
        font-size: 14px;
        color: #6c757d;
    }

    .login-footer {
        margin-top: 20px;
    }

    @media (max-width: 576px) {
        .login-wrapper {
            max-width: 100%;
        }

        .card-body-custom {
            padding: 24px;
        }

        .login-title {
            font-size: 24px;
        }

        .logo-circle {
            width: 70px;
            height: 70px;
            font-size: 35px;
        }
    }
</style>
@endsection
