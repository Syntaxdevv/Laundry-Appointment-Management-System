
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.jpg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --graphite: #252A34;
            --graphite-light: #303641;
            --emerald: #00A878;
            --emerald-dark: #008C64;
            --emerald-light: #E8F5EF;
            --page-bg: #F3F5F2;
            --white: #FFFFFF;
            --text-dark: #29312D;
            --text-muted: #78827B;
            --border: #E1E6E0;
            --sidebar-width: 255px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
        }

        .customer-wrapper {
            min-height: 100vh;
        }

        /* SIDEBAR */

        .customer-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--graphite);
            color: var(--white);
            padding: 25px 17px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .25s ease;
            overflow-y: auto;
        }

        .customer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 10px 28px;
            border-bottom: 1px solid rgba(255,255,255,.10);
        }

        .customer-brand-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            background: var(--emerald);
            border-radius: 9px;
            font-size: 19px;
        }

        .customer-brand-name {
            color: #FFFFFF;
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .customer-menu-label {
            color: #929D9A;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            padding: 27px 12px 12px;
            text-transform: uppercase;
        }

        .customer-nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .customer-nav a {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 12px 13px;
            color: #C5CCC9;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            transition: .2s;
        }

        .customer-nav a i {
            width: 19px;
            text-align: center;
            font-size: 15px;
        }

        .customer-nav a:hover,
        .customer-nav a.active {
            background: rgba(0,168,120,.17);
            color: #45D9AB;
        }

        .customer-sidebar-bottom {
            margin-top: auto;
            border-top: 1px solid rgba(255,255,255,.10);
            padding-top: 15px;
        }

        .customer-logout {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            color: #C5CCC9;
            font-size: 13px;
            font-weight: 600;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            border-radius: 6px;
        }

        .customer-logout:hover {
            color: #45D9AB;
            background: rgba(255,255,255,.05);
        }

        /* MAIN CONTENT */

        .customer-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            min-width: 0;
        }

        .customer-topbar {
            min-height: 72px;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .customer-topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .customer-page-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* CUSTOMER ACCOUNT DISPLAY */

        .customer-user {
            display: flex;
            align-items: center;
            gap: 11px;
            min-width: 0;
        }

        .customer-user-icon {
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 50%;
            background: var(--emerald-light);
            color: var(--emerald-dark);
        }

        .customer-user-info {
            min-width: 0;
        }

        .customer-user-info strong {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-dark);
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .customer-user-info span {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* CONTENT */

        .customer-content {
            width: 100%;
            min-width: 0;
            padding: 27px 30px;
        }

        /* MOBILE MENU */

        .customer-menu-toggle {
            display: none;
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: var(--graphite);
            font-size: 20px;
            cursor: pointer;
        }

        .customer-menu-toggle:hover {
            background: #F0F3F0;
        }

        .customer-overlay {
            display: none;
        }

        /* RESPONSIVE */

        @media (max-width: 900px) {

            .customer-sidebar {
                transform: translateX(-100%);
            }

            .customer-sidebar.open {
                transform: translateX(0);
            }

            .customer-main {
                margin-left: 0;
            }

            .customer-menu-toggle {
                display: grid;
                place-items: center;
            }

            .customer-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.45);
                z-index: 900;
            }

            .customer-topbar {
                padding: 0 18px;
            }

            .customer-content {
                padding: 22px 18px;
            }
        }

        @media (max-width: 480px) {

            .customer-topbar {
                min-height: 65px;
                padding: 0 12px;
                gap: 8px;
            }

            .customer-topbar-left {
                gap: 8px;
            }

            .customer-page-title {
                font-size: 15px;
            }

            .customer-user {
                gap: 7px;
            }

            .customer-user-icon {
                width: 35px;
                height: 35px;
            }

            .customer-user-info {
                display: none;
            }

            .customer-content {
                padding: 18px 12px;
            }
        }

        @stack('layout-styles')
    </style>

    @stack('styles')
</head>

<body>

<div class="customer-wrapper">

    <!-- SIDEBAR -->
    <aside class="customer-sidebar" id="customerSidebar">

        <div class="customer-brand">

            <div class="customer-brand-icon">
                <i class="fa-solid fa-shirt"></i>
            </div>

            <div class="customer-brand-name">
                Lavanderie
            </div>

        </div>

        <div class="customer-menu-label">Customer Menu</div>

        <nav class="customer-nav">

            <a href="{{ route('customer.dashboard') }}"
               class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('customer.bookings.create') }}"
               class="{{ request()->routeIs('customer.bookings.create') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-plus"></i>
                <span>New Booking</span>
            </a>

            <a href="{{ route('customer.bookings.index') }}"
               class="{{ request()->routeIs('customer.bookings.*') && !request()->routeIs('customer.bookings.create') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>My Orders</span>
            </a>

        </nav>

        <div class="customer-sidebar-bottom">

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="customer-logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </button>

            </form>

        </div>

    </aside>

    <!-- MOBILE OVERLAY -->
    <div class="customer-overlay" id="customerOverlay"></div>

    <!-- MAIN CONTENT -->
    <main class="customer-main">

        <!-- TOPBAR -->
        <header class="customer-topbar">

            <div class="customer-topbar-left">

                <button class="customer-menu-toggle"
                        id="customerMenuToggle"
                        type="button"
                        aria-label="Toggle menu"
                        aria-expanded="false">

                    <i class="fa-solid fa-bars"></i>

                </button>

                <h1 class="customer-page-title">
                    @yield('page-heading', 'Dashboard')
                </h1>

            </div>

            <!-- CUSTOMER NAME -->
            <div class="customer-user">

                <div class="customer-user-icon">
                    <i class="fa-solid fa-user"></i>
                </div>

                <div class="customer-user-info">
                    <strong>{{ auth()->user()->name ?? 'Customer' }}</strong>
                    <span>Customer Account</span>
                </div>

            </div>

        </header>

        <!-- PAGE CONTENT -->
        <section class="customer-content">
            @yield('content')
        </section>

    </main>

</div>

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- SIDEBAR SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const toggle = document.getElementById('customerMenuToggle');
    const sidebar = document.getElementById('customerSidebar');
    const overlay = document.getElementById('customerOverlay');

    function openSidebar() {
        sidebar?.classList.add('open');
        overlay?.classList.add('show');
        toggle?.setAttribute('aria-expanded', 'true');
    }

    function closeSidebar() {
        sidebar?.classList.remove('open');
        overlay?.classList.remove('show');
        toggle?.setAttribute('aria-expanded', 'false');
    }

    // TOGGLE SIDEBAR
    toggle?.addEventListener('click', function () {

        if (sidebar?.classList.contains('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }

    });

    // CLOSE WHEN CLICKING OVERLAY
    overlay?.addEventListener('click', closeSidebar);

    // CLOSE WHEN CLICKING NAVIGATION
    sidebar?.querySelectorAll('.customer-nav a').forEach(function (link) {
        link.addEventListener('click', closeSidebar);
    });

    // CLOSE USING ESCAPE KEY
    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closeSidebar();
        }

    });

    // RESET SIDEBAR WHEN RETURNING TO DESKTOP
    window.addEventListener('resize', function () {

        if (window.innerWidth > 900) {
            closeSidebar();
        }

    });

});
</script>

<!-- PAGE SCRIPTS -->
@stack('scripts')

</body>
</html>
