<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Sin acceso · CMMX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style nonce="{{ $cspNonce ?? '' }}">
        :root {
            --navy: #0A192F;
            --accent: #38BDF8;
            --danger: #EF4444;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .error-card {
            text-align: center;
            max-width: 480px;
            animation: fadeIn 0.4s ease both;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .error-icon {
            width: 100px; height: 100px;
            background: rgba(239,68,68,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.8rem;
            color: var(--danger);
            margin: 0 auto 1.5rem;
            border: 2px solid rgba(239,68,68,0.3);
        }
        .error-code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            color: var(--danger);
            letter-spacing: -4px;
            margin-bottom: 0.5rem;
        }
        .error-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #fff;
        }
        .error-desc {
            font-size: 0.95rem;
            color: #94A3B8;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--accent);
            color: var(--navy);
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .btn-back:hover { opacity: 0.85; color: var(--navy); }
        .logo {
            font-size: 0.9rem;
            color: #475569;
            margin-top: 2.5rem;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .logo span { color: var(--accent); }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="bi bi-shield-lock-fill"></i>
        </div>
        <div class="error-code">403</div>
        <div class="error-title">Acceso no autorizado</div>
        <p class="error-desc">
            No tienes los permisos necesarios para acceder a esta sección.<br>
            Si crees que esto es un error, contacta a tu administrador del sistema.
        </p>
        @if(auth()->check())
            @php
                $home = match(auth()->user()->role) {
                    'Admin'      => '/dashboard',
                    'Technician' => '/technician/dashboard',
                    'Analyst'    => '/analyst/dashboard',
                    default      => '/login',
                };
            @endphp
            <a href="{{ $home }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Volver al inicio
            </a>
        @else
            <a href="/login" class="btn-back">
                <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
            </a>
        @endif
        <div class="logo">CMM<span>X</span> · Sistema CMMS Industrial</div>
    </div>
</body>
</html>
