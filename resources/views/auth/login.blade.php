<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>S.I.D. — Sistema de Información Digital | UMSS</title>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:      #1246A0;
            --blue-dark: #0B2E6A;
            --blue-hover:#0F3D8C;
            --bg:        #F1F3F7;
            --white:     #FFFFFF;
            --text:      #0D1B2E;
            --text-sub:  #4A5E78;
            --text-muted:#8D9DB5;
            --border:    #D0D8E4;
            --input-bg:  #F8F9FB;
        }

        html, body {
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* ── HEADER ── */
        .header {
            background: var(--blue-dark);
            position: relative;
            overflow: hidden;
            padding-bottom: 96px;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 96px;
            background: var(--bg);
            clip-path: ellipse(56% 100% at 50% 100%);
        }

        /* single thin light line across top */
        .header-top-line {
            height: 3px;
            background: linear-gradient(90deg, var(--blue-dark), #2368CC 50%, var(--blue-dark));
        }

        .header-inner {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 2.5rem 3.5rem 3rem;
        }

        .header-logo {
            width: auto;
            opacity: 0.95;
        }

        .header-logo-left { max-height: 90px; }

        .header-logo-right {
            max-height: 56px;
            filter: brightness(0) invert(1);
        }

        /* ── CARD AREA ── */
        .main {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 1.5rem 4rem;
            margin-top: -72px;
            position: relative;
            z-index: 10;
        }

        .card {
            width: 100%;
            max-width: 440px;
            background: var(--white);
            border-radius: 4px;
            border-top: 3px solid var(--blue);
            box-shadow:
                0 1px 2px rgba(10,30,60,0.06),
                0 4px 12px rgba(10,30,60,0.07),
                0 20px 40px rgba(10,30,60,0.06);
            animation: up 0.5s cubic-bezier(0.22,1,0.36,1) both;
        }

        @keyframes up {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-body { padding: 2.75rem 3rem 2.5rem; }

        /* header */
        .card-head { margin-bottom: 2.25rem; }

        .card-label {
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.26em;
            text-transform: uppercase;
            color: var(--blue);
            margin-bottom: 0.5rem;
        }

        .card-title {
            font-size: 1.55rem;
            font-weight: 300;
            color: var(--text);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .card-title strong {
            font-weight: 600;
            color: var(--blue-dark);
        }

        .card-rule {
            margin-top: 1.5rem;
            height: 1px;
            background: var(--border);
        }

        /* fields */
        .field { margin-bottom: 1.25rem; }

        .field-label {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            color: var(--text-sub);
            margin-bottom: 0.45rem;
        }

        .input-wrap { position: relative; }

        .input-wrap svg {
            position: absolute;
            left: 13px; top: 50%;
            transform: translateY(-50%);
            width: 15px; height: 15px;
            fill: none;
            stroke: var(--text-muted);
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
            pointer-events: none;
            transition: stroke 0.15s;
        }

        .input-wrap:focus-within svg { stroke: var(--blue); }

        .form-input {
            width: 100%;
            padding: 0.82rem 1rem 0.82rem 2.65rem;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 3px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            font-weight: 400;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
            -webkit-appearance: none;
        }

        .form-input::placeholder { color: var(--text-muted); }

        .form-input:focus {
            border-color: var(--blue);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(18,70,160,0.1);
        }

        .form-input.is-invalid {
            border-color: #B83030;
            box-shadow: 0 0 0 3px rgba(184,48,48,0.08);
        }

        .invalid-feedback {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 0.35rem;
            font-size: 0.72rem;
            color: #9A2020;
            font-weight: 500;
        }

        /* button */
        .btn-submit {
            width: 100%;
            padding: 0.88rem;
            margin-top: 0.5rem;
            background: var(--blue);
            border: none;
            border-radius: 3px;
            color: var(--white);
            font-family: 'Inter', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            box-shadow: 0 2px 8px rgba(18,70,160,0.28);
            transition: background 0.15s, box-shadow 0.15s, transform 0.1s;
        }

        .btn-submit:hover {
            background: var(--blue-hover);
            box-shadow: 0 4px 16px rgba(18,70,160,0.35);
            transform: translateY(-1px);
        }

        .btn-submit:active { transform: translateY(0); }

        .btn-submit svg {
            width: 14px; height: 14px;
            fill: none; stroke: white;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        }

        /* card foot */
        .card-foot {
            margin-top: 2.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 0.6rem;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* page foot */
        .page-foot {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.6rem;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: #A8B4C4;
        }

        /* responsive */
        @media (max-width: 540px) {
            .header-inner {
                padding: 2rem 1.75rem 2.5rem;
                flex-direction: column;
                align-items: center;
                gap: 0.75rem;
            }
            .card-body { padding: 2.25rem 2rem 2rem; }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-top-line"></div>
        <div class="header-inner">
            <img class="header-logo header-logo-left" src="/img/icon/logo%20archivos%20blanco1.png" alt="Seccion Archivos - UMSS">
            <img class="header-logo header-logo-right" src="/img/icon/logo sf.png" alt="SID — UMSS">
        </div>
    </div>

    <div class="main">
        <div class="card">
            <div class="card-body">

                <div class="card-head">
                    <h1 class="card-title">Bienvenidos al <strong>S.I.D.</strong></h1>
                    <div class="card-rule"></div>
                </div>

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="field">
                        <label class="field-label" for="email">Usuario</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 16 16">
                                <rect x="2" y="4" width="12" height="9" rx="1"/>
                                <polyline points="2,4 8,9.5 14,4"/>
                            </svg>
                            <input id="email" type="email"
                                class="form-input @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}"
                                placeholder="correo@archivos.net"
                                required autocomplete="email" autofocus>
                        </div>
                        @error('email')
                            <div class="invalid-feedback" role="alert">
                                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="8" cy="8" r="6"/><line x1="8" y1="5" x2="8" y2="9"/><circle cx="8" cy="11.5" r=".7" fill="currentColor" stroke="none"/></svg>
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="password">Contraseña</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 16 16">
                                <rect x="3" y="7" width="10" height="7.5" rx="1"/>
                                <path d="M5.5 7V5a2.5 2.5 0 0 1 5 0v2"/>
                            </svg>
                            <input id="password" type="password"
                                class="form-input @error('password') is-invalid @enderror"
                                name="password" placeholder=""
                                required autocomplete="current-password">
                        </div>
                        @error('password')
                            <div class="invalid-feedback" role="alert">
                                <svg width="12" height="12" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="8" cy="8" r="6"/><line x1="8" y1="5" x2="8" y2="9"/><circle cx="8" cy="11.5" r=".7" fill="currentColor" stroke="none"/></svg>
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 16 16">
                            <path d="M10 2h3a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1h-3"/>
                            <polyline points="7 11 10 8 7 5"/>
                            <line x1="10" y1="8" x2="2" y2="8"/>
                        </svg>
                        Ingresar
                    </button>
                </form>


            </div>
        </div>

        <div class="page-foot">Universidad Mayor de San Simón &nbsp;·&nbsp; Sección Archivos</div>
    </div>

</body>
</html>
