<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MecApp') }} — {{ __('Registro') }}</title>
    
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
                radial-gradient(at 100% 100%, rgba(56, 189, 248, 0.1) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            animation: fadeIn 0.5s ease both;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
            margin-top: -2rem;
        }

        .login-header .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
            text-decoration: none;
        }

        .login-header .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--accent), #0EA5E9);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #fff;
            font-size: 1.1rem;
        }

        .login-header .brand-text {
            font-size: 1.25rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }

        .login-header h2 {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #94A3B8;
            font-size: 0.9rem;
        }

        .login-card {
            background: var(--surface);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #0A192F;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .form-control {
            border: 1.5px solid #E2E8F0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-glow);
            color: #0A192F;
        }

        .form-control::placeholder {
            color: #94A3B8;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            color: #EF4444;
            display: block;
            margin-top: 0.35rem;
        }

        .form-control.is-invalid {
            border-color: #EF4444;
            background: #FFF7F7;
        }

        .form-control.is-invalid:focus {
            border-color: #EF4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .btn-register {
            background: var(--navy);
            border: none;
            color: #fff;
            border-radius: 8px;
            padding: 0.85rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
        }

        .btn-register:hover {
            background: var(--navy-mid);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(10, 25, 47, 0.3);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 1.75rem 0;
            gap: 1rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E2E8F0;
        }

        .divider span {
            font-size: 0.8rem;
            color: #94A3B8;
            font-weight: 500;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #E2E8F0;
        }

        .auth-footer p {
            color: #0A192F;
            font-size: 0.9rem;
            margin: 0;
        }

        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .auth-footer a:hover {
            color: #0EA5E9;
            text-decoration: underline;
        }

        .password-note {
            font-size: 0.8rem;
            color: #64748B;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .login-header {
                margin-bottom: 2rem;
                margin-top: -1rem;
            }

            .login-header h2 {
                font-size: 1.2rem;
            }

            .login-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <a href="/" class="brand">
                <div class="brand-icon">🏭</div>
                <div class="brand-text">{{ config('app.name', 'MecApp') }}</div>
            </a>
            <h2>{{ __('Crear Cuenta') }}</h2>
            <p>{{ __('Regístrate como Técnico') }}</p>
        </div>

        <div class="login-card">
            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>{{ __('¡Error en el registro!') }}</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" novalidate>
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">{{ __('Nombre Completo') }}</label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name"
                        name="name" 
                        value="{{ old('name') }}"
                        placeholder="{{ __('Tu nombre completo') }}"
                        required 
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">{{ __('Correo Electrónico') }}</label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email"
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="{{ __('correo@ejemplo.com') }}"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">{{ __('Contraseña') }}</label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password"
                        name="password" 
                        placeholder="{{ __('Mínimo 8 caracteres') }}"
                        required
                    >
                    @error('password')
                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                    @enderror
                    <div class="password-note">
                        <i class="bi bi-info-circle"></i>
                        {{ __('Mínimo 8 caracteres, letras y números') }}
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">{{ __('Confirmar Contraseña') }}</label>
                    <input 
                        type="password" 
                        class="form-control @error('password_confirmation') is-invalid @enderror" 
                        id="password_confirmation"
                        name="password_confirmation" 
                        placeholder="{{ __('Repite tu contraseña') }}"
                        required
                    >
                    @error('password_confirmation')
                        <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-register">
                    <i class="bi bi-person-check-fill"></i>
                    {{ __('Crear Cuenta de Técnico') }}
                </button>
            </form>

            <div class="divider">
                <span>{{ __('¿Ya tienes cuenta?') }}</span>
            </div>

            <div class="auth-footer">
                <p>
                    <a href="{{ route('login') }}">{{ __('Inicia sesión aquí') }}</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
