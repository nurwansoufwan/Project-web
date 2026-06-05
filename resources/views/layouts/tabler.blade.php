<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Sewa Alat Camping</title>
    <!-- Tabler Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
    <!-- Tabler Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .navbar-brand-image {
            height: 2.2rem;
        }

        /* Campfire CSS Animation */
        .camp-animation-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        .campfire {
            position: relative;
            width: 80px;
            height: 80px;
        }
        .flame {
            position: absolute;
            bottom: 12px;
            left: 50%;
            width: 38px;
            height: 38px;
            border-radius: 50% 0 50% 50%;
            transform: rotate(-45deg) translateX(-50%);
            transform-origin: 50% 100%;
            animation: floatFlame 1.2s ease-in-out infinite alternate;
        }
        .flame.red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            width: 42px;
            height: 42px;
            animation-delay: 0s;
            opacity: 0.8;
            margin-left: -2px;
        }
        .flame.orange {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            width: 32px;
            height: 32px;
            animation-delay: 0.15s;
            opacity: 0.9;
            margin-left: 2px;
        }
        .flame.yellow {
            background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%);
            width: 22px;
            height: 22px;
            animation-delay: 0.3s;
            margin-left: 6px;
        }
        .flame.white {
            background: #ffffff;
            width: 12px;
            height: 12px;
            animation-delay: 0.45s;
            bottom: 16px;
            margin-left: 10px;
        }
        .wood {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 55px;
            height: 10px;
            background-color: #78350f;
            border-radius: 5px;
            z-index: 10;
        }
        .wood::before {
            content: '';
            position: absolute;
            left: 8px;
            top: -6px;
            width: 38px;
            height: 10px;
            background-color: #451a03;
            border-radius: 5px;
            transform: rotate(15deg);
        }
        .wood::after {
            content: '';
            position: absolute;
            left: 8px;
            top: -6px;
            width: 38px;
            height: 10px;
            background-color: #451a03;
            border-radius: 5px;
            transform: rotate(-15deg);
        }

        @keyframes floatFlame {
            0% {
                transform: translateX(-50%) rotate(-45deg) scale(0.9);
            }
            100% {
                transform: translateX(-50%) rotate(-42deg) scale(1.1) translateY(-6px);
            }
        }

        /* Custom SweetAlert Style to match Tabler */
        .swal2-popup {
            border-radius: 16px !important;
            padding: 1.5rem !important;
            font-family: 'Outfit', sans-serif !important;
        }

        /* Premium Alert Styling with slide-down animation */
        .premium-alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            animation: slideDownAlert 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            margin-bottom: 24px;
        }
        
        .premium-alert-danger {
            border-left: 5px solid #ef4444 !important;
            background: linear-gradient(90deg, rgba(254, 242, 242, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%) !important;
        }

        .premium-alert-warning {
            border-left: 5px solid #ef4444 !important; /* Made RED as requested */
            background: linear-gradient(90deg, rgba(254, 242, 242, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%) !important;
        }

        .alert-icon-pulse {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(239, 68, 68, 0.12);
            color: #ef4444;
            animation: pulseIcon 2s infinite;
        }

        @keyframes slideDownAlert {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseIcon {
            0% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.45);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="page">
        <!-- Horizontal Navbar -->
        <header class="navbar navbar-expand-md d-print-none navbar-light">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-bootstrap me-md-3">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none text-dark">
                        <span class="avatar bg-primary text-white rounded me-2">
                            <i class="ti ti-tent"></i>
                        </span>
                        <span class="fw-bold fs-3">CampBilaBola</span>
                    </a>
                </h1>
                
                <div class="navbar-nav flex-row order-md-last">
                    <!-- User Profile Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm bg-blue-lt"><i class="ti ti-user"></i></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="mt-1 small text-secondary">Administrator</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="#" class="dropdown-item"><i class="ti ti-settings me-2"></i> Pengaturan</a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger w-100 border-0 bg-transparent text-start">
                                    <i class="ti ti-logout me-2"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            <!-- Dashboard -->
                            <li class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-home fs-3"></i></span>
                                    <span class="nav-link-title">Home</span>
                                </a>
                            </li>
                            <!-- Master Data Dropdown -->
                            <li class="nav-item dropdown {{ Route::is('equipment.*') || Route::is('customers.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-database fs-3"></i></span>
                                    <span class="nav-link-title">Master Data</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item {{ Route::is('equipment.*') ? 'active' : '' }}" href="{{ route('equipment.index') }}">
                                                <i class="ti ti-tent me-2 fs-4"></i> Alat Camping
                                            </a>
                                            <a class="dropdown-item {{ Route::is('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                                                <i class="ti ti-users me-2 fs-4"></i> Pelanggan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- Transaksi Dropdown -->
                            <li class="nav-item dropdown {{ Route::is('rentals.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-receipt fs-3"></i></span>
                                    <span class="nav-link-title">Transaksi</span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item {{ Route::is('rentals.create') ? 'active' : '' }}" href="{{ route('rentals.create') }}">
                                                <i class="ti ti-plus me-2 fs-4"></i> Sewa Baru
                                            </a>
                                            <a class="dropdown-item {{ Route::is('rentals.index') && !Route::is('rentals.create') ? 'active' : '' }}" href="{{ route('rentals.index') }}">
                                                <i class="ti ti-history me-2 fs-4"></i> Daftar Transaksi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="page-wrapper">
            <!-- Page Header -->
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            @yield('page-pretitle')
                            <h2 class="page-title">
                                @yield('page-title', 'Dashboard')
                            </h2>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            @yield('page-actions')
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Body -->
            <div class="page-body">
                <div class="container-xl">
                    <!-- Session Alert -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <div><i class="ti ti-circle-check fs-3 me-2"></i></div>
                                <div>{{ session('success') }}</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <div><i class="ti ti-alert-circle fs-3 me-2"></i></div>
                                <div>{{ session('error') }}</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer footer-transparent d-print-none">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="#" class="link-secondary">Dokumentasi</a></li>
                                <li class="list-inline-item"><a href="#" class="link-secondary">Bantuan</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; 2026
                                    <a href="." class="link-secondary">CampBilaBola</a>.
                                    All rights reserved.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert2 form/link confirmation handler
            document.addEventListener('click', function(e) {
                const confirmBtn = e.target.closest('.confirm-action');
                if (confirmBtn) {
                    e.preventDefault();
                    const title = confirmBtn.dataset.confirmTitle || 'Konfirmasi Tindakan';
                    const message = confirmBtn.dataset.confirmMessage || 'Apakah Anda yakin ingin memproses ini?';
                    const confirmText = confirmBtn.dataset.confirmButtonText || 'Ya, Proses';
                    const cancelText = confirmBtn.dataset.cancelButtonText || 'Batal';
                    const form = confirmBtn.closest('form');
                    const href = confirmBtn.getAttribute('href');

                    Swal.fire({
                        html: `
                            <div class="camp-animation-wrapper">
                                <div class="campfire">
                                    <div class="wood"></div>
                                    <div class="flame red"></div>
                                    <div class="flame orange"></div>
                                    <div class="flame yellow"></div>
                                    <div class="flame white"></div>
                                </div>
                            </div>
                            <h3 class="fw-bold mb-2 text-dark">${title}</h3>
                            <p class="text-secondary mb-0">${message}</p>
                        `,
                        showCancelButton: true,
                        confirmButtonText: confirmText,
                        cancelButtonText: cancelText,
                        customClass: {
                            confirmButton: 'btn btn-primary px-4 mx-2',
                            cancelButton: 'btn btn-link link-secondary mx-2'
                        },
                        buttonsStyling: false,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (form) {
                                // Add a hidden field to submit trigger so backend controller is processed
                                const hiddenSubmit = document.createElement('input');
                                hiddenSubmit.type = 'hidden';
                                hiddenSubmit.name = confirmBtn.name;
                                hiddenSubmit.value = confirmBtn.value;
                                form.appendChild(hiddenSubmit);
                                form.submit();
                            } else if (href && href !== '#') {
                                window.location.href = href;
                            }
                        }
                    });
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
