<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lahore Leads University — @yield('page-title', 'Student Portal')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

        html, body {
            margin: 0; padding: 0;
            min-height: 100%; width: 100%;
        }

        /* Solid dark blue background */
        .auth-page {
            width: 100vw;
            min-height: 100vh;
            background: #1e3a8a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        /* Centered white card */
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: fadeUp 0.4s ease both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Gold top stripe */
        .card-top {
            height: 4px;
            background: linear-gradient(90deg, #C9A84C, #e8c96a, #C9A84C);
        }

        /* Card inner padding */
        .card-body { padding: 36px 40px 32px; }

        /* Brand: LLU | Online Admission System */
        .card-brand {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 6px;
        }
        .card-brand .abbr {
            font-size: 40px; font-weight: 900;
            color: #1e3a8a; letter-spacing: -1px; line-height: 1;
        }
        .card-brand .divider {
            width: 1.5px; height: 38px; background: #94a3b8;
        }
        .card-brand .subtitle {
            font-size: 12px; color: #475569;
            line-height: 1.5; font-weight: 400;
        }

        /* Gold line */
        .gold-line {
            height: 2px; width: 48px;
            background: linear-gradient(90deg, transparent, #C9A84C, transparent);
            margin: 10px 0 18px; border-radius: 99px;
        }

        /* Page heading */
        .card-heading {
            font-size: 24px; font-weight: 700;
            color: #1a1a1a; margin-bottom: 22px;
        }

        /* Field label */
        .field-label {
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.08em; color: #334155;
            text-transform: uppercase; margin-bottom: 6px;
            display: block;
        }

        /* Input wrapper */
        .input-wrap {
            display: flex; align-items: center;
            border: 1.5px solid #cbd5e1;
            border-radius: 6px; background: #f8fafc;
            overflow: hidden; transition: border-color 0.2s;
            margin-bottom: 16px;
        }
        .input-wrap:focus-within {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30,58,138,0.1);
            background: #fff;
        }
        .input-wrap .icon {
            padding: 0 12px; color: #94a3b8; font-size: 13px;
            display: flex; align-items: center;
        }
        .input-wrap .vdivider {
            width: 1px; height: 20px; background: #cbd5e1; flex-shrink: 0;
        }
        .input-wrap input {
            flex: 1; border: none; outline: none;
            padding: 11px 12px; font-size: 13.5px;
            background: transparent; color: #1e293b;
        }
        .input-wrap input::placeholder { color: #94a3b8; }
        .input-wrap .eye-btn {
            padding: 0 12px; color: #94a3b8; font-size: 13px;
            cursor: pointer; background: none; border: none;
            display: flex; align-items: center; transition: color 0.15s;
        }
        .input-wrap .eye-btn:hover { color: #1e3a8a; }

        /* Remember + Forgot */
        .remember-row {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 20px; font-size: 12.5px;
        }
        .remember-row label {
            display: flex; align-items: center;
            gap: 6px; color: #475569; cursor: pointer;
        }
        .remember-row input[type=checkbox] {
            width: 13px; height: 13px; accent-color: #1e3a8a;
        }
        .remember-row a { color: #1e3a8a; text-decoration: none; font-weight: 600; }
        .remember-row a:hover { text-decoration: underline; }

        /* Primary button */
        .btn-primary {
            width: 100%; padding: 13px;
            background: #1e3a8a; color: #fff;
            font-size: 15px; font-weight: 700;
            border: none; border-radius: 6px; cursor: pointer;
            letter-spacing: 0.03em;
            transition: background 0.2s, transform 0.1s;
            margin-bottom: 14px;
        }
        .btn-primary:hover  { background: #162d6e; }
        .btn-primary:active { transform: scale(0.99); }

        /* Bottom text */
        .bottom-text {
            text-align: center; font-size: 13px; color: #64748b;
        }
        .bottom-text a {
            color: #1e3a8a; font-weight: 700; text-decoration: none;
        }
        .bottom-text a:hover { text-decoration: underline; }

        /* Card footer */
        .card-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 12px 40px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>

    @stack('styles')
</head>

<body>
<div class="auth-page">

    <!-- Top-left LLU Logo -->
    <div style="position:fixed;top:22px;left:28px;display:flex;align-items:center;gap:8px;z-index:10;">
        <div style="width:42px;height:42px;border:2px solid rgba(255,255,255,0.4);border-radius:6px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,0.15);">
            <i class="fas fa-university" style="color: #C9A84C;font-size:18px;"></i>
        </div>
        <div style="line-height:1.3;">
            <span style="font-style:italic;font-size:10px;font-weight:600;color:rgba(255,255,255,0.7);">The</span><br>
            <span style="font-size:14px;font-weight:900;color:#fff;">Lahore Leads</span><br>
            <span style="font-size:11px;font-weight:600;font-style:italic;color:rgba(255,255,255,0.8);">University</span>
        </div>
    </div>

    <div class="auth-card">

        <!-- Gold top stripe -->
        <div class="card-top"></div>

        <div class="card-body">

            <!-- LLU | Online Admission System -->
            <div class="card-brand">
                <span class="abbr">LLU</span>
                <div class="divider"></div>
                <span class="subtitle">Online<br>Admission System</span>
            </div>

            <div class="gold-line"></div>

            <!-- Page title -->
            <h2 class="card-heading">@yield('auth-title', 'Login')</h2>

            <!-- Breeze form slot -->
            {{ $slot }}

        </div>

        <!-- Card footer -->
        <div class="card-footer">
            © {{ date('Y') }} Lahore Leads University — All rights reserved
        </div>

    </div>

</div>
@stack('scripts')
</body>
</html>