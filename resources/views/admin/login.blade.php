<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kumala Property Portal - Akses Masuk Admin</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Master CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #F3F7FA;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Radar concentric circles background */
        .radar-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 1000px;
            height: 1000px;
            pointer-events: none;
            z-index: 0;
        }
        .radar-circle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            border: 1px solid rgba(14, 30, 46, 0.04);
        }
        .radar-circle:nth-child(1) { width: 340px; height: 340px; }
        .radar-circle:nth-child(2) { width: 560px; height: 560px; }
        .radar-circle:nth-child(3) { width: 780px; height: 780px; }
        .radar-circle:nth-child(4) { width: 980px; height: 980px; }

        .login-card {
            position: relative;
            z-index: 10;
            background: #FFFFFF;
            width: 100%;
            max-width: 420px;
            border-radius: 14px;
            border: 1px solid #E2E8F0;
            box-shadow: 0 10px 30px rgba(12, 30, 50, 0.06);
            padding: 34px 30px;
            text-align: center;
        }

        .login-brand-icon {
            width: 40px;
            height: 40px;
            background: #EAF4F9;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #0E1E2E;
            margin-bottom: 14px;
        }

        .badge-restricted {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #EAF5FA;
            color: #0369A1;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 4px 12px;
            border-radius: 20px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }

        .login-title {
            font-size: 1.45rem;
            font-weight: 800;
            color: #0E1E2E;
            margin-bottom: 4px;
            letter-spacing: -0.01em;
        }

        .login-subtitle {
            font-size: 0.8rem;
            color: #64748B;
            margin-bottom: 24px;
            line-height: 1.4;
        }

        .input-group {
            margin-bottom: 16px;
            text-align: left;
        }

        .input-label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .input-label-row label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #0E1E2E;
        }

        .input-hint-right {
            font-size: 0.72rem;
            color: #94A3B8;
        }

        .input-link-right {
            font-size: 0.74rem;
            color: #0284C7;
            text-decoration: none;
            font-weight: 600;
        }
        .input-link-right:hover {
            text-decoration: underline;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper input {
            width: 100%;
            padding: 10px 14px 10px 38px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            background: #FFFFFF;
            font-size: 0.86rem;
            color: #0E1E2E;
            outline: none;
            transition: all 0.15s;
            font-family: inherit;
        }

        .input-wrapper input:focus {
            border-color: #00838F;
            box-shadow: 0 0 0 3px rgba(0, 131, 143, 0.12);
        }

        .input-icon-left {
            position: absolute;
            left: 12px;
            color: #94A3B8;
            display: flex;
        }

        .input-icon-right {
            position: absolute;
            right: 12px;
            color: #94A3B8;
            cursor: pointer;
            background: none;
            border: none;
            display: flex;
        }

        .input-helper {
            font-size: 0.72rem;
            color: #64748B;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-submit-login {
            width: 100%;
            background: #0E1E2E;
            color: #FFFFFF;
            border: none;
            padding: 11px 16px;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.15s;
            margin-top: 8px;
        }
        .btn-submit-login:hover {
            background: #172D45;
        }

        .login-footer-disclaimer {
            position: relative;
            z-index: 10;
            text-align: center;
            margin-top: 26px;
            max-width: 500px;
        }
        .login-footer-tag {
            font-size: 0.68rem;
            font-weight: 700;
            color: #475569;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .login-footer-text {
            font-size: 0.72rem;
            color: #94A3B8;
            margin-top: 4px;
            line-height: 1.4;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 24px 18px;
            }
            .login-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>

    <!-- Radar lines effect -->
    <div class="radar-bg">
        <div class="radar-circle"></div>
        <div class="radar-circle"></div>
        <div class="radar-circle"></div>
        <div class="radar-circle"></div>
    </div>

    <!-- Login Card -->
    <div class="login-card">
        <!-- Logo Icon -->
        <div class="login-brand-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
        </div>

        <div>
            <div class="badge-restricted">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>AREA TERBATAS: AKSES KHUSUS ADMIN & MANAJEMEN</span>
            </div>
        </div>

        <h1 class="login-title">Kumala Property Portal</h1>
        <p class="login-subtitle">Masuk ke Panel Kontrol Operasional & Pemasaran Properti</p>

        @if($errors->any())
        <div style="background: #FEE2E2; border: 1px solid #FCA5A5; color: #DC2626; padding: 8px 12px; border-radius: 6px; font-size: 0.78rem; margin-bottom: 16px; text-align: left;">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('admin.authenticate') }}" method="POST">
            @csrf

            <!-- Email -->
            <div class="input-group">
                <div class="input-label-row">
                    <label for="email">Alamat Email / ID Pengguna</label>
                    <span class="input-hint-right">Terdaftar</span>
                </div>
                <div class="input-wrapper">
                    <span class="input-icon-left">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    </span>
                    <input type="text" id="email" name="email" value="{{ old('email', 'admin@kumalaproperty.co.id') }}" required placeholder="admin@kumalaproperty.co.id">
                </div>
            </div>

            <!-- Password -->
            <div class="input-group">
                <div class="input-label-row">
                    <label for="password">Kata Sandi</label>
                    <a href="javascript:void(0)" class="input-link-right" onclick="alert('Hubungi Super Admin (admin@kumalaproperty.co.id) untuk reset kata sandi.')">Lupa kata sandi?</a>
                </div>
                <div class="input-wrapper">
                    <span class="input-icon-left">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input type="password" id="password" name="password" value="PropertySecure2024!" required placeholder="Minimal 8 karakter">
                    <button type="button" class="input-icon-right" onclick="togglePassVisibility()" title="Lihat password">
                        <svg id="eye-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <div class="input-helper">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                    <span>Minimal 8 karakter kombinasi huruf, angka, dan simbol.</span>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit-login">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                <span>Masuk ke Dashboard Admin</span>
            </button>
        </form>
    </div>

    <!-- Footer disclaimer -->
    <div class="login-footer-disclaimer">
        <div class="login-footer-tag">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>PROTOKOL KEAMANAN ASET ENTERPRISE</span>
        </div>
        <p class="login-footer-text">
            Kumala Property Management System v2.4 • Hanya untuk personel berwenang. Semua aktivitas akses dicatat secara otomatis dalam audit log sistem.
        </p>
    </div>

    <script>
        function togglePassVisibility() {
            const passInput = document.getElementById('password');
            if (passInput.type === 'password') {
                passInput.type = 'text';
            } else {
                passInput.type = 'password';
            }
        }
    </script>
</body>
</html>
