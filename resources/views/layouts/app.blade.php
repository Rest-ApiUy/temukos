<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f7f1">
    <title>@yield('title', 'TemuKost · Layanan Pencarian Kos Personal')</title>
    
    <!-- Typography: Plus Jakarta Sans & Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink: #14251f;
            --muted: #5e7068;
            --green: #1c604b;
            --green-dark: #144737;
            --mint: #e8f1eb;
            --cream: #f7f7f1;
            --cream-card: #ffffff;
            --border: #e0e6df;
            --orange: #e26d3a;
            --orange-soft: #fcf1eb;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --shadow-subtle: 0 4px 20px rgba(20, 37, 31, 0.05);
            --shadow-elevated: 0 12px 36px rgba(20, 37, 31, 0.09);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--cream);
            color: var(--ink);
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            font-size: 15px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .brand-title {
            font-family: 'Manrope', 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            letter-spacing: -0.035em;
            color: var(--ink);
            line-height: 1.25;
        }

        a {
            color: inherit;
            text-decoration: none;
            transition: color 0.15s ease;
        }

        button, input, select, textarea {
            font: inherit;
        }

        button, a, input[type="submit"], input[type="checkbox"], input[type="radio"], select {
            cursor: pointer;
        }

        :focus-visible {
            outline: 2px solid var(--green);
            outline-offset: 3px;
        }

        .shell {
            width: min(1160px, calc(100% - 40px));
            margin: 0 auto;
        }

        /* Topbar Header */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(247, 247, 241, 0.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
        }

        .nav-inner {
            height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 800;
            font-family: 'Manrope', sans-serif;
            letter-spacing: -0.04em;
            color: var(--ink);
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--green);
            color: #ffffff;
            display: grid;
            place-items: center;
        }

        .brand-logo svg {
            width: 20px;
            height: 20px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            font-weight: 600;
            font-size: 14px;
            color: var(--muted);
        }

        .nav-links a:hover {
            color: var(--green);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Standard Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 14px;
            border: 1px solid transparent;
            transition: all 0.18s ease;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--green);
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: var(--green-dark);
            transform: translateY(-1px);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--ink);
            border-color: var(--border);
        }

        .btn-outline:hover {
            background-color: #f0f3ed;
            border-color: #cbd5cb;
        }

        .btn-sm {
            padding: 8px 14px;
            font-size: 13px;
            border-radius: var(--radius-sm);
        }

        .btn-block {
            width: 100%;
        }

        /* Flash Message Toasts */
        .alert-box {
            padding: 14px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .alert-success {
            background-color: var(--mint);
            color: var(--green-dark);
            border: 1px solid #c9ded0;
        }

        .alert-error {
            background-color: #fde8e8;
            color: #9b1c1c;
            border: 1px solid #f8b4b4;
        }

        /* Standard Cards & Panels */
        .card {
            background: var(--cream-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 28px;
            box-shadow: var(--shadow-subtle);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-success {
            background-color: var(--mint);
            color: var(--green);
        }

        .badge-warning {
            background-color: #fef08a;
            color: #854d0e;
        }

        .badge-neutral {
            background-color: #e5e7eb;
            color: #374151;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border);
            background: #ffffff;
            color: var(--ink);
            transition: border-color 0.15s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(28, 96, 75, 0.12);
        }

        .form-hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: 5px;
        }

        /* Footer */
        .site-footer {
            margin-top: 80px;
            padding: 40px 0;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            .footer-inner {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <header class="topbar">
        <div class="shell nav-inner">
            <a href="{{ route('home') }}" class="brand-link">
                <span class="brand-logo" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 11 9-8 9 8v9a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z" />
                    </svg>
                </span>
                temukost<span style="color:var(--orange)">.</span>
            </a>

            <nav class="nav-links" aria-label="Navigasi Utama">
                <a href="{{ route('home') }}#cara-kerja">Cara kerja</a>
                <a href="{{ route('home') }}#alur-benefit">Alur & benefit</a>
                <a href="{{ route('order.create') }}">Cari kos</a>
                @auth
                    <a href="{{ route('account.index') }}">Pesanan saya</a>
                    @if(Auth::user()->isStaff())
                        <a href="{{ route('staff.index') }}" style="color:var(--orange); font-weight:700;">Panel Tim</a>
                    @endif
                @endauth
            </nav>

            <div class="nav-actions">
                @auth
                    @if(Auth::user()->isStaff())
                        <a href="{{ route('staff.index') }}" class="btn btn-outline btn-sm">Panel Tim</a>
                    @endif
                    <a href="{{ route('account.index') }}" class="btn btn-primary btn-sm">
                        {{ explode(' ', Auth::user()->name)[0] }} · Akun
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm" title="Keluar">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                    <a href="{{ route('order.create') }}" class="btn btn-primary btn-sm">Mulai cari kos</a>
                @endauth
            </div>
        </div>
    </header>

    <main id="main-content" style="min-height: calc(100vh - 200px); padding: 36px 0;">
        <div class="shell">
            @if(session('success'))
                <div class="alert-box alert-success" role="alert">
                    <span>{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;font-size:16px;">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box alert-error" role="alert">
                    <div>
                        <strong>Terdapat beberapa catatan:</strong>
                        <ul style="margin-left: 20px; margin-top: 4px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;font-size:16px;">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="site-footer">
        <div class="shell footer-inner">
            <div>
                <strong>TemuKost</strong> &copy; {{ date('Y') }}. Layanan pencarian kos personal dan transparan.
            </div>
            <div>
                Platform pencarian, survei video, dan pendampingan pindah kos.
            </div>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
