<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sewa Alat Camping</title>
    <!-- Tabler Core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
    <!-- Tabler Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }
        .auth-container {
            min-height: 100vh;
            display: flex;
        }
        .auth-form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background-color: #ffffff;
            z-index: 2;
        }
        .auth-image-side {
            flex: 1.1;
            position: relative;
            background: url('/images/auth-bg.png') no-repeat center center;
            background-size: cover;
            display: none;
        }
        @media (min-width: 992px) {
            .auth-image-side {
                display: block;
            }
        }
        .auth-card {
            width: 100%;
            max-width: 420px;
        }
        .input-icon-group {
            position: relative;
        }
        .input-icon-group .form-control {
            padding-right: 2.5rem;
            background-color: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 12px;
            height: 48px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }
        .input-icon-group .form-control:focus {
            background-color: #ffffff;
            border-color: #206bc4;
            box-shadow: 0 0 0 4px rgba(32, 107, 196, 0.1);
        }
        .input-icon-group .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
        }
        .btn-auth-primary {
            height: 48px;
            border-radius: 12px;
            font-weight: 500;
            background-color: #206bc4;
            color: #ffffff;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-auth-primary:hover {
            background-color: #1a569d;
            transform: translateY(-1px);
        }
        .btn-auth-secondary {
            height: 48px;
            border-radius: 12px;
            font-weight: 500;
            background-color: #f1f5f9;
            color: #475569;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-auth-secondary:hover {
            background-color: #e2e8f0;
        }
        .wave-divider {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -1px;
            width: 120px;
            z-index: 10;
            fill: #ffffff;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Left Side: Form -->
        <div class="auth-form-side">
            <div class="auth-card">
                <div class="mb-4">
                    <span class="text-uppercase text-muted fw-bold fs-6 tracking-wider" style="letter-spacing: 0.1em;">Selamat Datang</span>
                    <h1 class="fw-bold text-dark mt-1 mb-2" style="font-size: 2.2rem;">Masuk Akun.</h1>
                    <p class="text-secondary">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-medium text-decoration-none">Daftar di sini</a></p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div><i class="ti ti-circle-check fs-3 me-2"></i></div>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <div><i class="ti ti-alert-circle fs-3 me-2"></i></div>
                            <div>
                                <ul class="mb-0 ps-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium">Alamat Email</label>
                        <div class="input-icon-group">
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                            <i class="ti ti-mail input-icon fs-3"></i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label text-secondary fw-medium mb-0">Password</label>
                        </div>
                        <div class="input-icon-group">
                            <input type="password" name="password" id="password-field" class="form-control" placeholder="••••••••" required>
                            <i class="ti ti-lock input-icon fs-3" style="cursor: pointer; pointer-events: auto;" onclick="togglePassword()"></i>
                        </div>
                    </div>

                    <div class="mb-4 d-flex justify-content-between align-items-center">
                        <label class="form-check mb-0">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember-me">
                            <span class="form-check-label text-secondary fs-5">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-auth-primary d-flex align-items-center justify-content-center">
                            Masuk <i class="ti ti-arrow-right ms-2 fs-3"></i>
                        </button>
                        <a href="/" class="btn btn-auth-secondary d-flex align-items-center justify-content-center">
                            Kembali ke Beranda
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side: Beautiful Landscape Image with Wave Divider -->
        <div class="auth-image-side">
            <!-- SVG Wave Divider to make the boundary organic and premium -->
            <svg class="wave-divider" viewBox="0 0 100 100" preserveAspectRatio="none">
                <!-- SVG path representing a custom wavy divider -->
                <path d="M100 0 C40 15, 60 50, 20 80 L100 100 Z" />
            </svg>
            <div class="position-absolute bottom-0 start-0 p-5 text-white z-3 m-3">
                <div class="d-flex align-items-center mb-2">
                    <span class="avatar avatar-md bg-white text-primary rounded-circle me-3 shadow-lg">
                        <i class="ti ti-tent fs-2"></i>
                    </span>
                    <h2 class="fw-bold mb-0 text-white shadow-text" style="font-size: 1.8rem; text-shadow: 0 2px 4px rgba(0,0,0,0.4);">CampBilaBola.</h2>
                </div>
                <p class="fs-4 text-white-50 mb-0 shadow-text" style="text-shadow: 0 1px 3px rgba(0,0,0,0.5);">Penyewaan Alat Camping Premium & Terlengkap</p>
            </div>
        </div>
    </div>

    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
    <script>
        function togglePassword() {
            const pwdField = document.getElementById('password-field');
            const icon = event.target;
            if (pwdField.type === 'password') {
                pwdField.type = 'text';
                icon.classList.remove('ti-lock');
                icon.classList.add('ti-lock-open');
            } else {
                pwdField.type = 'password';
                icon.classList.remove('ti-lock-open');
                icon.classList.add('ti-lock');
            }
        }
    </script>
</body>
</html>
