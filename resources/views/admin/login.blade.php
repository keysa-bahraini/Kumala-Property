<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kumala Property Management System</title>
    <meta name="description" content="Akses portal kontrol internal manajemen Kumala Property. Gatekeeper listing, CRM inquiry, dan audit closing transaksi.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Master CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        body {
            background-color: #F4F6F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            font-family: var(--font-body);
        }

        .login-card {
            background: #FFFFFF;
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(14, 34, 56, 0.08);
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            text-decoration: none;
        }

        .login-brand-icon {
            width: 44px;
            height: 44px;
            background: var(--primary-navy);
            color: #FFFFFF;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-brand-text {
            text-align: left;
        }
        .login-brand-title {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 1.15rem;
            color: var(--primary-navy);
            display: block;
            line-height: 1.1;
        }
        .login-brand-sub {
            font-size: 0.72rem;
            color: var(--accent-teal);
            font-weight: 700;
            letter-spacing: 0.8px;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: #F8FAFC;
            font-size: 0.95rem;
            color: var(--primary-navy);
            transition: var(--transition-fast);
        }

        .input-wrapper input:focus {
            border-color: var(--accent-teal);
            background: #FFFFFF;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 180, 136, 0.15);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #94A3B8;
            display: flex;
            align-items: center;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #94A3B8;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }
        .toggle-password:hover {
            color: var(--primary-navy);
        }

        .demo-box {
            background: #F1F5F9;
            border: 1px dashed var(--border-color);
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.82rem;
            color: #475569;
        }

        .btn-quick-fill {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary-navy);
            cursor: pointer;
            transition: var(--transition-fast);
        }
        .btn-quick-fill:hover {
            background: #E2E8F0;
        }

        .security-badge {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border-light);
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.76rem;
            color: #64748B;
            line-height: 1.45;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- Brand Header -->
        <div class="login-header">
            <a href="{{ route('home') }}" class="login-brand" title="Kembali ke Beranda Publik">
                <div class="login-brand-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div class="login-brand-text">
                    <span class="login-brand-title">KUMALA PROPERTY</span>
                    <span class="login-brand-sub">ADMINISTRATIVE GATEWAY</span>
                </div>
            </a>

            <div>
                <span class="badge-tag badge-tag-navy" style="margin-bottom: 8px;">PORTAL KONTROL OPERASIONAL</span>
                <h1 style="font-size: 1.5rem; color: var(--primary-navy); margin-bottom: 6px;">Masuk ke Portal Internal</h1>
                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5;">
                    Akses khusus personel manajemen, kurator properti, dan tim private concierge Kumala Property.
                </p>
            </div>
        </div>

        @if(session('success'))
        <div style="background: #E6FBF5; border: 1px solid #A7F3D0; color: #065F46; padding: 10px 14px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if($errors->any())
        <div style="background: #FFF1F2; border: 1px solid #FECDD3; color: #9F1239; padding: 10px 14px; border-radius: 8px; font-size: 0.82rem; margin-bottom: 18px;">
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Demo Credentials Helper -->
        <div class="demo-box">
            <div>
                <strong style="display: block; color: var(--primary-navy);">Kredensial Demo Admin</strong>
                <span>admin@kumalaproperty.com • kata sandi bebas</span>
            </div>
            <button type="button" class="btn-quick-fill" onclick="quickFillCredentials()">Isi Cepat</button>
        </div>

        <!-- Login Form -->
        <form action="{{ route('admin.authenticate') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="admin-email">Alamat Email / ID Personel *</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <input type="email" id="admin-email" name="email" value="admin@kumalaproperty.com" placeholder="nama@kumalaproperty.com" required>
                </div>
            </div>

            <div class="input-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label for="admin-password" style="margin-bottom: 0;">Kata Sandi Resmi *</label>
                    <a href="javascript:void(0)" onclick="alert('Silakan hubungi IT Security Hotline Kumala Property di ext. 104 atau gunakan password demo apapun.')" style="font-size: 0.75rem; color: var(--accent-teal-dark); font-weight: 600;">Bantuan Akses?</a>
                </div>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input type="password" id="admin-password" name="password" value="kumala2026" placeholder="Masukkan kata sandi..." required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" title="Tampilkan / Sembunyikan Kata Sandi">
                        <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; font-size: 0.85rem; color: #475569;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="remember" checked style="accent-color: #00B488;">
                    <span>Ingat saya di perangkat ini</span>
                </label>
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; padding: 13px; font-size: 0.95rem; border-radius: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>Masuk ke Portal Kontrol</span>
            </button>
        </form>

        <!-- Security Protocol Info -->
        <div class="security-badge">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" style="flex-shrink: 0; margin-top: 1px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <div>
                <strong style="display: block; color: var(--primary-navy);">Protokol Keamanan Enkripsi Enterprise v2.4</strong>
                <span>Setiap upaya otentikasi dicatat dalam Audit Trail Log demi kepatuhan ISO/IEC 27001 dan proteksi privasi portofolio HNWIs.</span>
            </div>
        </div>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('home') }}" style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted); display: inline-flex; align-items: center; gap: 6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                <span>Kembali ke Website Publik</span>
            </a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('admin-password');
            const eye = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                eye.innerHTML = `<path d="m15 18-.722-3.25"/><path d="M2 8a10.645 10.645 0 0 0 20 0"/><path d="m20 15-1.726-2.05"/><path d="m4 15 1.726-2.05"/><path d="m9 18 .722-3.25"/>`;
            } else {
                input.type = 'password';
                eye.innerHTML = `<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>`;
            }
        }

        function quickFillCredentials() {
            document.getElementById('admin-email').value = 'admin@kumalaproperty.com';
            document.getElementById('admin-password').value = 'kumala2026';
        }
    </script>
</body>
</html>
