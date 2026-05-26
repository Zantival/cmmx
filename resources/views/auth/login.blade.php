<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CMMX') }} — {{ __('Acceso') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style nonce="{{ $cspNonce ?? '' }}">
        :root {
            --navy: #0A192F;
            --navy-mid: #112240;
            --accent: #38BDF8;
            --accent-glow: rgba(56, 189, 248, 0.2);
            --surface: #F8FAFC;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy);
            background-image: 
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(56, 189, 248, 0.1) 0px, transparent 50%),
                linear-gradient(135deg, #0A192F 0%, #112240 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            margin: 0;
            overflow-x: hidden;
        }

        /* ─── Animated Background Pattern ─── */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 0;
            pointer-events: none;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 575.98px) {
            .login-card { padding: 2rem 1.5rem; border-radius: 20px; }
        }

        .logo-box {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--accent), #0EA5E9);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #fff;
            font-size: 1.75rem;
            box-shadow: 0 10px 15px -3px var(--accent-glow);
        }

        .brand-name {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--navy);
            letter-spacing: -1px;
            margin-bottom: 0.5rem;
        }
        .brand-name span { color: var(--accent); }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 1.1rem;
            transition: color 0.2s;
        }
        .form-control {
            height: 52px;
            padding-left: 48px;
            border-radius: 12px;
            border: 2px solid #F1F5F9;
            background: #F8FAFC;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .form-control:focus {
            background: #fff;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px var(--accent-glow);
            color: var(--navy);
        }
        .form-control:focus + i { color: var(--accent); }

        .btn-login {
            height: 52px;
            background: var(--navy);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            margin-top: 0.5rem;
        }
        .btn-login:hover {
            background: #112240;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.2);
            color: #fff;
        }
        .btn-login:active { transform: translateY(0); }

        .form-check-input:checked { background-color: var(--accent); border-color: var(--accent); }
        .form-check-label { font-size: 0.875rem; color: #64748B; cursor: pointer; }

        .lang-footer {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .lang-link {
            font-size: 0.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            text-transform: uppercase;
            transition: all 0.2s;
        }
        .lang-link:hover, .lang-link.active {
            color: #fff;
        }

        .animate-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card animate-up">
        <div class="text-center">
            <div class="logo-box">
                <i class="bi bi-cpu-fill"></i>
            </div>
            <h1 class="brand-name">CMM<span>X</span></h1>
            <p class="text-muted small mb-4">{{ __('Sistema de Gestión de Mantenimiento') }}</p>
        </div>

        @if($errors->any())
        <div class="alert alert-danger border-0 py-2 px-3 mb-4 animate-up" style="border-radius:12px; font-size:0.85rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ $errors->first() }}
        </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success border-0 py-2 px-3 mb-4 animate-up" style="border-radius:12px; font-size:0.85rem;">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">{{ __('Correo Electrónico') }}</label>
                <div class="input-group-custom">
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                           placeholder="usuario@cmmx.com" required autofocus>
                    <i class="bi bi-envelope"></i>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label">{{ __('Contraseña') }}</label>
                </div>
                <div class="input-group-custom">
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    <i class="bi bi-lock"></i>
                </div>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        {{ __('Recordarme') }}
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-login">
                <span>{{ __('Iniciar Sesión') }}</span>
                <i class="bi bi-arrow-right"></i>
            </button>

            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <p style="color: #94A3B8; font-size: 0.9rem; margin: 0;">
                    {{ __('¿No tienes cuenta?') }}
                    <a href="{{ route('register') }}" style="color: var(--accent); text-decoration: none; font-weight: 600; transition: color 0.3s ease;">
                        {{ __('Regístrate aquí') }}
                    </a>
                </p>
            </div>
        </form>
    </div>

    <div class="lang-footer animate-up" style="animation-delay: 0.2s;">
        <a href="{{ route('locale.switch', 'es') }}" class="lang-link {{ app()->getLocale() === 'es' ? 'active' : '' }}">Español</a>
        <div style="width: 1px; height: 12px; background: rgba(255,255,255,0.2); margin-top: 2px;"></div>
        <a href="{{ route('locale.switch', 'en') }}" class="lang-link {{ app()->getLocale() === 'en' ? 'active' : '' }}">English</a>
    </div>
</div>

</body>
</html>
