@extends('layouts.app')

@section('content')
<div class="register-container">
    <div class="register-wrapper">
        <!-- Logo & Heading -->
        <div class="register-header mb-4">
            <div class="logo-section text-center mb-3">
                <div class="logo-circle">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
            <h1 class="register-title">Daftar Akun</h1>
            <p class="register-subtitle">Bergabunglah sebagai Peserta Magang di TDI Service</p>
        </div>

        <!-- Register Card -->
        <div class="register-card">
            <div class="card-header-custom">
                <h4 class="mb-0">
                    <i class="fas fa-form-fields me-2"></i> Lengkapi Data Diri Anda
                </h4>
            </div>

            <div class="card-body-custom">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Full Name -->
                    <div class="form-group-custom mb-4">
                        <label for="full_name" class="form-label-custom">Nama Lengkap</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-user"></i></span>
                            <input
                                id="full_name"
                                type="text"
                                class="form-control-custom @error('full_name') is-invalid @enderror"
                                name="full_name"
                                value="{{ old('full_name') }}"
                                placeholder="Masukkan nama lengkap Anda"
                                required
                                autocomplete="name"
                                autofocus>
                        </div>
                        @error('full_name')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group-custom mb-4">
                        <label for="email" class="form-label-custom">Email Address</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-envelope"></i></span>
                            <input
                                id="email"
                                type="email"
                                class="form-control-custom @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="your@email.com"
                                required
                                autocomplete="email">
                        </div>
                        @error('email')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Student ID -->
                    <div class="form-group-custom mb-4">
                        <label for="student_id" class="form-label-custom">Student ID (NIM)</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-id-card"></i></span>
                            <input
                                id="student_id"
                                type="text"
                                class="form-control-custom @error('student_id') is-invalid @enderror"
                                name="student_id"
                                value="{{ old('student_id') }}"
                                placeholder="Masukkan NIM Anda"
                                required
                                autocomplete="off">
                        </div>
                        @error('student_id')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Campus -->
                    <div class="form-group-custom mb-4">
                        <label for="campus" class="form-label-custom">Kampus/Universitas</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-school"></i></span>
                            <input
                                id="campus"
                                type="text"
                                class="form-control-custom @error('campus') is-invalid @enderror"
                                name="campus"
                                value="{{ old('campus') }}"
                                placeholder="Nama kampus/universitas Anda"
                                required
                                autocomplete="organization">
                        </div>
                        @error('campus')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Division -->
                    <div class="form-group-custom mb-4">
                        <label for="division_id" class="form-label-custom">Division</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-sitemap"></i></span>
                            <select
                                id="division_id"
                                class="form-control-custom @error('division_id') is-invalid @enderror"
                                name="division_id"
                                required>
                                <option value="" disabled {{ old('division_id') ? '' : 'selected' }}>Pilih Division</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ old('division_id') == $division->id ? 'selected' : '' }}>
                                        {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('division_id')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Job Role -->
                    <div class="form-group-custom mb-4">
                        <label for="job_role_id" class="form-label-custom">Job Role</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-briefcase"></i></span>
                            <select
                                id="job_role_id"
                                class="form-control-custom @error('job_role_id') is-invalid @enderror"
                                name="job_role_id"
                                required>
                                <option value="" disabled {{ old('job_role_id') ? '' : 'selected' }}>Pilih Job Role</option>
                                @foreach($jobRoles as $jobRole)
                                    <option value="{{ $jobRole->id }}" {{ old('job_role_id') == $jobRole->id ? 'selected' : '' }}>
                                        {{ $jobRole->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('job_role_id')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group-custom mb-4">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input
                                id="password"
                                type="password"
                                class="form-control-custom @error('password') is-invalid @enderror"
                                name="password"
                                placeholder="Minimal 8 karakter"
                                required
                                autocomplete="new-password">
                        </div>
                        @error('password')
                            <div class="error-message mt-2">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group-custom mb-4">
                        <label for="password-confirm" class="form-label-custom">Konfirmasi Password</label>
                        <div class="input-group-custom">
                            <span class="input-icon"><i class="fas fa-lock"></i></span>
                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control-custom"
                                name="password_confirmation"
                                placeholder="Ulangi password Anda"
                                required
                                autocomplete="new-password">
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="form-group-custom mb-4">
                        <div class="form-check-custom">
                            <input
                                class="form-check-input-custom"
                                type="checkbox"
                                name="terms"
                                id="terms"
                                required>
                            <label class="form-check-label-custom" for="terms">
                                Saya setuju dengan Syarat & Ketentuan Layanan
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-register-custom w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i> Daftar Sekarang
                    </button>

                    <!-- Login Link -->
                    <div class="login-link text-center">
                        <p class="mb-0" style="font-size: 14px; color: #6c757d;">
                            Sudah punya akun?
                            <a href="{{ route('login') }}" class="link-custom-primary">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="register-footer">
            <p class="text-muted small text-center">
                <i class="fas fa-lock me-1"></i> Data Anda aman dan terenkripsi
            </p>
        </div>
    </div>
</div>

<style>
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        /* background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); */
        padding: 20px;
    }

    .register-wrapper {
        width: 100%;
        max-width: 520px;
    }

    .register-header {
        text-align: center;
    }

    .logo-circle {
        width: 80px;
        height: 80px;
        background: #198754;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 40px;
        color: white;
        box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
    }

    .register-title {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .register-subtitle {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
    }

    .register-card {
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
        max-height: 70vh;
        overflow-y: auto;
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
        appearance: none;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: #198754;
        box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.1);
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
        align-items: flex-start;
        gap: 8px;
    }

    .form-check-input-custom {
        width: 18px;
        height: 18px;
        min-width: 18px;
        margin-top: 2px;
        cursor: pointer;
        border: 1.5px solid #dee2e6;
        border-radius: 4px;
        appearance: none;
        background: white;
        transition: all 0.3s ease;
    }

    .form-check-input-custom:hover {
        border-color: #198754;
    }

    .form-check-input-custom:checked {
        background: #198754;
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 8l4 4 8-8'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 100% 100%;
    }

    .form-check-label-custom {
        margin-bottom: 0;
        cursor: pointer;
        font-size: 13px;
        color: #495057;
        line-height: 1.4;
    }

    .error-message {
        font-size: 13px;
        color: #dc3545;
        display: flex;
        align-items: center;
    }

    .btn-register-custom {
        background: #198754;
        color: white;
        border: none;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-register-custom:hover {
        background: #157347;
        color: white;
        text-decoration: none;
    }

    .btn-register-custom:active {
        background: #145c3b;
    }

    .link-custom-primary {
        color: #198754;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .link-custom-primary:hover {
        color: #157347;
        text-decoration: none;
    }

    .register-footer {
        margin-top: 20px;
    }

    @media (max-width: 576px) {
        .register-wrapper {
            max-width: 100%;
        }

        .card-body-custom {
            padding: 24px;
            max-height: 80vh;
        }

        .register-title {
            font-size: 24px;
        }

        .logo-circle {
            width: 70px;
            height: 70px;
            font-size: 35px;
        }
    }

    /* Scrollbar styling */
    .card-body-custom::-webkit-scrollbar {
        width: 6px;
    }

    .card-body-custom::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .card-body-custom::-webkit-scrollbar-thumb {
        background: #bbb;
        border-radius: 10px;
    }

    .card-body-custom::-webkit-scrollbar-thumb:hover {
        background: #888;
    }
</style>
@endsection
