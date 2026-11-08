
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Lavanderie')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('favicon.jpg') }}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(request()->routeIs('home'))
    <style>
        :root {
            --navy: #172e43;
            --blue: #2869ca;
            --blue-dark: #1e55a8;
            --soft-blue: #e9f1fc;
            --white: #fff;
            --text: #253548;
            --muted: #687786;
            --line: #dce2e8;
            --content-width: 1180px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 85px;
        }

        body {
            margin: 0;
            color: var(--text);
            background: var(--white);
            font-family: "DM Sans", sans-serif;
            font-size: 15px;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }

        body.menu-open {
            overflow: hidden;
        }

        img {
            display: block;
            max-width: 100%;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            font: inherit;
            -webkit-tap-highlight-color: transparent;
        }

        .container {
            width: min(var(--content-width), calc(100% - 64px));
            margin-inline: auto;
        }

        /* HEADER */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, .98);
            border-bottom: 1px solid var(--line);
        }

        .nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand-name {
            display: block;
            color: var(--navy);
            font-family: "Manrope", sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.2;
        }

        .brand-name span {
            color: var(--blue);
        }

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
        }

        .nav-links a,
        .nav-login {
            color: #465568;
            font-size: 13px;
            font-weight: 600;
            transition: color .2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active,
        .nav-login:hover {
            color: var(--blue);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-shrink: 0;
        }

        .button {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 19px;
            border: 1px solid transparent;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            transition: background .2s ease, color .2s ease;
        }

        .button-primary {
            color: var(--white);
            background: var(--blue);
        }

        .button-primary:hover {
            background: var(--blue-dark);
        }

        .button-outline {
            color: var(--white);
            background: transparent;
            border-color: rgba(255, 255, 255, .55);
        }

        .button-outline:hover {
            color: var(--navy);
            background: var(--white);
        }

        .menu-toggle {
            display: none;
            width: 43px;
            height: 43px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--line);
            background: var(--white);
            color: var(--navy);
            cursor: pointer;
        }

        /* HERO */

        .hero {
            position: relative;
            min-height: 590px;
            display: flex;
            align-items: center;
            overflow: hidden;
            isolation: isolate;
            color: var(--white);
            background: var(--navy);
        }

        .hero-image {
            position: absolute;
            z-index: -2;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 53%;
        }

        .hero::before {
            content: "";
            position: absolute;
            z-index: -1;
            inset: 0;
            background: linear-gradient(
                90deg,
                rgba(17, 39, 58, .96) 0%,
                rgba(17, 39, 58, .89) 34%,
                rgba(17, 39, 58, .57) 62%,
                rgba(17, 39, 58, .20) 100%
            );
        }

        .hero-content {
            width: min(680px, 66%);
            padding-block: 90px 86px;
        }

        .hero h1 {
            max-width: 680px;
            margin: 0;
            font-family: "Manrope", sans-serif;
            font-size: clamp(52px, 5.4vw, 74px);
            font-weight: 800;
            letter-spacing: -4px;
            line-height: 1.08;
        }

        .hero h1 span {
            display: block;
            color: #9bc2ff;
        }

        .hero-copy {
            max-width: 510px;
            margin: 25px 0 0;
            color: rgba(255, 255, 255, .86);
            font-size: 16px;
            line-height: 1.9;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 30px;
        }

        /* ABOUT */

        .fw-about {
            width: 100%;
            padding: 90px 6%;
            background: var(--white);
            overflow: hidden;
        }

        .fw-about-container {
            max-width: 1240px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: .92fr 1.08fr;
            align-items: center;
            gap: 75px;
        }

        .fw-about-content {
            width: 100%;
            max-width: 540px;
        }

        .fw-about-title {
            margin: 0 0 24px;
            color: var(--navy);
            font-family: "Manrope", sans-serif;
            font-size: clamp(36px, 4vw, 54px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.8px;
        }

        .fw-about-title span {
            display: block;
            color: var(--blue);
        }

        .fw-about-description {
            max-width: 500px;
            margin: 0 0 32px;
            color: #718096;
            font-size: 14px;
            line-height: 1.95;
        }

        .fw-about-button {
            min-height: 49px;
            padding: 0 23px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            border: 1.5px solid var(--blue);
            border-radius: 50px;
            color: var(--blue);
            background: transparent;
            font-size: 13px;
            font-weight: 700;
            transition: background .2s ease, color .2s ease;
        }

        .fw-about-button span {
            font-size: 20px;
            transition: transform .2s ease;
        }

        .fw-about-button:hover {
            color: var(--white);
            background: var(--blue);
        }

        .fw-about-button:hover span {
            transform: translateX(3px);
        }

        .fw-about-visual {
            position: relative;
            min-width: 0;
            padding: 24px 20px 0 0;
        }

        .fw-about-accent {
            position: absolute;
            top: -14px;
            right: 0;
            width: 76%;
            height: 83%;
            border-radius: 17px;
            background: #dceaff;
        }

        .fw-about-image {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 420px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* STAFF */

        .staff-section {
            padding: 75px 24px 90px;
            background: var(--white);
        }

        .staff-container {
            max-width: 1120px;
            margin: 0 auto;
        }

        .staff-heading {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 38px;
        }

        .staff-heading h2,
        .services-heading h2 {
            font-family: "Bebas Neue", sans-serif;
            font-size: 42px;
            font-weight: 400;
            -webkit-text-stroke: 1px #102f59;
            color: #102f59;
            letter-spacing: 1px;
            margin: 0 0 12px;
        }

        .staff-heading p {
            max-width: 550px;
            margin: 0;
            color: #687f9f;
            font-size: 14px;
            line-height: 1.7;
        }

        .staff-gallery {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .staff-photo {
            overflow: hidden;
            border-radius: 12px;
            background: #edf3fa;
            text-align: center;
            padding-bottom: 20px;
        }

        .staff-photo img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform .3s ease;
        }

        .staff-photo h3 {
            margin: 15px 0 5px;
            font-size: 22px;
            font-weight: 700;
            color: #000;
        }

        .staff-photo p {
            margin: 0;
            font-size: 15px;
            color: #555;
        }

        .staff-photo:hover img {
            transform: scale(1.035);
        }

        /* SERVICES */

        .services-section {
            padding: 70px 6%;
            background: #f4f9ff;
        }

        .services-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .services-heading {
            text-align: center;
            margin-bottom: 38px;
        }

        .services-label {
            display: inline-block;
            padding: 7px 20px;
            margin-bottom: 12px;
            border-radius: 30px;
            background: #e1f1ff;
            color: #1689e8;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 3px;
        }

        .services-heading h2 {
            margin-bottom: 12px;
        }

        .services-heading p {
            max-width: 570px;
            margin: 0 auto;
            color: #687f9f;
            font-size: 14px;
            line-height: 1.8;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .service-card {
            padding: 10px;
            border-radius: 20px;
            background: var(--white);
            box-shadow: 0 5px 20px rgba(34, 105, 170, .07);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 26px rgba(34, 105, 170, .13);
        }

        .service-card img {
            width: 100%;
            height: 190px;
            object-fit: cover;
            border-radius: 13px;
        }

        .service-content {
            min-height: 135px;
            padding: 17px 14px 16px;
            background: linear-gradient(135deg, #f0f8ff, #eaf5ff);
            border-radius: 0 0 13px 13px;
        }

        .service-content h3 {
            margin: 0 0 9px;
            color: #102f59;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.4;
        }

        .service-content p {
            margin: 0;
            color: #68809f;
            font-size: 13px;
            line-height: 1.7;
        }

        /* FOOTER */

        .site-footer {
            padding: 18px;
            text-align: center;
            background: #f4f8fc;
            border-top: 1px solid #e5edf5;
        }

        .site-footer p {
            margin: 0;
            color: #6b7b8f;
            font-size: 13px;
            line-height: 1.8;
        }

        .site-footer strong {
            color: #173b65;
        }

        .footer-disclaimer {
            color: #8290a1;
            font-size: 12px;
        }

        /* RESPONSIVE */

        @media (max-width: 1050px) {
            .container {
                width: calc(100% - 48px);
            }

            .nav {
                gap: 18px;
            }

            .nav-links {
                gap: 18px;
            }

            .nav-actions {
                gap: 13px;
            }

            .hero-content {
                width: 72%;
            }

            .fw-about-container {
                gap: 38px;
            }

            .fw-about-image {
                height: 350px;
            }
        }

        @media (max-width: 760px) {
            .container {
                width: calc(100% - 36px);
            }

            .nav {
                min-height: 70px;
            }

            .menu-toggle {
                display: inline-flex;
            }

            .nav-links {
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                display: none;
                align-items: stretch;
                gap: 0;
                padding: 10px 18px 20px;
                border-bottom: 1px solid var(--line);
                background: var(--white);
            }

            .nav-links.is-open {
                display: flex;
                flex-direction: column;
            }

            .nav-links a {
                padding: 13px 5px;
                border-bottom: 1px solid #edf0f3;
            }

            .nav-actions {
                gap: 12px;
                margin-left: auto;
            }

            .nav-actions .nav-login {
                display: none;
            }

            .nav-actions .button {
                min-height: 40px;
                padding: 0 13px;
                font-size: 12px;
            }

            .hero {
                min-height: 590px;
            }

            .hero::before {
                background: linear-gradient(
                    90deg,
                    rgba(17, 39, 58, .94),
                    rgba(17, 39, 58, .72)
                );
            }

            .hero-content {
                width: 100%;
                padding-block: 76px;
            }

            .hero h1 {
                font-size: clamp(44px, 8vw, 64px);
                letter-spacing: -2.5px;
            }

            .hero-copy {
                font-size: 15px;
            }

            .fw-about {
                padding: 60px 22px;
            }

            .fw-about-container {
                grid-template-columns: 1fr;
                gap: 42px;
            }

            .fw-about-title {
                font-size: clamp(34px, 9vw, 44px);
            }

            .fw-about-image {
                height: 300px;
            }

            .staff-gallery,
            .services-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .service-card img {
                height: 220px;
            }
        }

        @media (max-width: 550px) {
            .services-section {
                padding: 50px 5%;
            }

            .staff-gallery,
            .services-grid {
                grid-template-columns: 1fr;
            }

            .staff-photo img {
                height: 300px;
            }

            .services-heading h2,
            .staff-heading h2 {
                font-size: 34px;
            }

            .hero-buttons {
                align-items: stretch;
                flex-direction: column;
            }

            .hero-buttons .button {
                width: 100%;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
        .reveal {
            opacity: 0;
            transform: translateY(35px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    @endif

    @stack('styles')
</head>

<body class="antialiased">

    @if(request()->routeIs('home'))

        @include('partials._header')

        <main>
            @yield('content')
        </main>

        @include('partials._footer')

    @else

        {{-- Laravel Breeze Login and Register --}}
        {{ $slot ?? '' }}

    @endif

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 800,
                    once: true,
                    offset: 50
                });
            }
        });
    </script>

    @stack('scripts')

</body>
</html>
