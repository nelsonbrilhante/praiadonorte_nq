<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $locale === 'pt' ? 'Em Manutenção' : 'Under Maintenance' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            color: #fff;
            overflow: hidden;
            background: #0b1022;
        }

        /* Animated gradient background */
        .bg {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0b1022 0%, #001d3d 40%, #003566 70%, #0b1022 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            z-index: 0;
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        /* Floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            z-index: 1;
        }

        .orb-blue {
            width: 400px;
            height: 400px;
            background: #0066cc;
            top: -100px;
            right: -100px;
            animation: float1 20s ease-in-out infinite;
        }

        .orb-green {
            width: 300px;
            height: 300px;
            background: #00cc66;
            bottom: -80px;
            left: -80px;
            animation: float2 25s ease-in-out infinite;
        }

        .orb-orange {
            width: 250px;
            height: 250px;
            background: #ffa500;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: float3 18s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(-60px, 40px); }
            66% { transform: translate(30px, -30px); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(50px, -30px); }
            66% { transform: translate(-40px, 20px); }
        }

        @keyframes float3 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.2); }
        }

        /* Content */
        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 520px;
            padding: 2rem;
        }

        .logo {
            width: 180px;
            margin: 0 auto 2.5rem;
            display: block;
        }

        h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            line-height: 1.2;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff, #7fb3e0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .description {
            font-size: 1rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2rem;
        }

        /* Pulsing dots */
        .dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            animation: pulse 1.5s ease-in-out infinite;
        }

        .dot:nth-child(1) { background: #0066cc; animation-delay: 0s; }
        .dot:nth-child(2) { background: #00cc66; animation-delay: 0.3s; }
        .dot:nth-child(3) { background: #ffa500; animation-delay: 0.6s; }

        @keyframes pulse {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.4); }
        }

        /* Language switcher */
        .lang-switch {
            display: inline-flex;
            gap: 0;
            border-radius: 9999px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 1.5rem;
        }

        .lang-switch a {
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
            letter-spacing: 0.05em;
        }

        .lang-switch a.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .lang-switch a:hover:not(.active) {
            color: rgba(255, 255, 255, 0.8);
        }

        .contact {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.4);
        }

        .contact a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.2s;
        }

        .contact a:hover {
            color: #fff;
            border-bottom-color: rgba(255, 255, 255, 0.5);
        }

        @media (prefers-reduced-motion: reduce) {
            .bg { animation: none; }
            .orb { animation: none; }
            .dot { animation: none; opacity: 1; }
        }

        @media (max-width: 480px) {
            h1 { font-size: 1.5rem; }
            .logo { width: 140px; }
        }
    </style>
</head>
<body>
    <div class="bg"></div>
    <div class="orb orb-blue"></div>
    <div class="orb orb-green"></div>
    <div class="orb orb-orange"></div>

    <div class="content">
        <img src="{{ asset('images/logos/imagem-grafica-nq-white-name.svg') }}" alt="Nazaré Qualifica" class="logo">

        <h1>
            @if($locale === 'pt')
                Em Manutenção
            @else
                Under Maintenance
            @endif
        </h1>

        <p class="description">
            @if($message && isset($message[$locale]))
                {{ $message[$locale] }}
            @elseif($locale === 'pt')
                Estamos a melhorar o nosso website. Voltamos em breve com novidades.
            @else
                We're improving our website. We'll be back shortly with updates.
            @endif
        </p>

        <div class="dots">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>

        <div class="lang-switch">
            <a href="/pt" class="{{ $locale === 'pt' ? 'active' : '' }}">PT</a>
            <a href="/en" class="{{ $locale === 'en' ? 'active' : '' }}">EN</a>
        </div>

        <p class="contact">
            @if($locale === 'pt')
                Contacto: <a href="mailto:geral@nazarequalifica.pt">geral@nazarequalifica.pt</a>
            @else
                Contact: <a href="mailto:geral@nazarequalifica.pt">geral@nazarequalifica.pt</a>
            @endif
        </p>
    </div>
</body>
</html>
