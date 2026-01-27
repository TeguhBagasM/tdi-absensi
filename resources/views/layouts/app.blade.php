<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('page-title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            min-height: 100vh;
            transition: all 0.3s;
        }
        .main-content.with-sidebar {
            margin-left: 250px;
        }
        @media (max-width: 768px) {
            .main-content.with-sidebar {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div id="app" class="d-flex">
        @auth
            @include('partials.sidebar')
        @endauth

        <div class="main-content flex-grow-1 {{ auth()->check() ? 'with-sidebar' : '' }}">
            @auth
                @include('partials.topbar')
            @else
                <!-- Guest Navbar -->
                <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm mb-4">
                    <div class="container">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <i class="fas fa-building"></i> {{ config('app.name', 'Laravel') }}
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto">
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">
                                            <i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}
                                        </a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">
                                            <i class="fas fa-user-plus me-1"></i> {{ __('Register') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </nav>
            @endauth

            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Initialize DataTables
        $(document).ready(function() {
            $('.datatable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                pageLength: 10,
                ordering: true,
                responsive: true
            });
        });

        // SweetAlert for delete confirmations
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('form[data-confirm-delete]');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: form.dataset.confirmDelete || 'Yakin ingin menghapus data ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Show success/error messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            // Show validation errors
            @if($errors->any())
                let errorMessages = '';
                const errors = {!! json_encode($errors->messages()) !!};
                Object.keys(errors).forEach(field => {
                    errorMessages += '<strong>' + field + ':</strong><br>';
                    errors[field].forEach(message => {
                        errorMessages += '• ' + message + '<br>';
                    });
                });

                Swal.fire({
                    icon: 'error',
                    title: '
                    !',
                    html: errorMessages,
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Clear all form inputs setelah error ditampilkan
                    document.querySelectorAll('form').forEach(form => {
                        // Reset input values, tapi jangan reset hidden fields
                        form.querySelectorAll('input[type="text"], input[type="email"], textarea, select').forEach(input => {
                            // Skip jika field adalah hidden field atau CSRF token
                            if (input.type !== 'hidden' && input.name !== '_token' && input.name !== '_method') {
                                input.value = '';
                                // Clear error styling
                                input.classList.remove('is-invalid');
                            }
                        });
                    });
                });
            @endif
        });
    </script>
</body>
</html>
