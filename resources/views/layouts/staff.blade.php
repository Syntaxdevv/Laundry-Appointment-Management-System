
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Staff Dashboard') | LinisLaba</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('favicon.jpg') }}">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --graphite: #252A34;
            --graphite-light: #303641;
            --emerald: #00A878;
            --emerald-dark: #008C64;
            --emerald-light: #E8F5EF;

            --background: #F3F5F2;
            --white: #FFFFFF;
            --text: #29312D;
            --muted: #78827B;
            --border: #E1E6E0;

            --danger: #D9534F;
            --sidebar-width: 255px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--background);
            color: var(--text);
            font-size: 14px;
        }

        a {
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }


        .staff-sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            height: 100dvh;
            position: fixed;
            inset: 0 auto 0 0;

            background: var(--graphite);
            color: var(--white);

            display: flex;
            flex-direction: column;

            z-index: 1000;
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;

            transition: transform .25s ease;
        }

        .staff-brand {
            min-height: 76px;
            padding: 0 24px;

            display: flex;
            align-items: center;
            gap: 12px;

            border-bottom: 1px solid rgba(255,255,255,.09);
            flex-shrink: 0;
        }

        .staff-brand-icon {
            width: 37px;
            height: 37px;
            flex: 0 0 37px;

            border-radius: 6px;
            background: var(--emerald);

            display: flex;
            align-items: center;
            justify-content: center;

            color: white;
            font-size: 16px;
        }

        .staff-brand-name {
            color: #FFFFFF;
            font-size: 19px;
            font-weight: 700;
            letter-spacing: -.6px;
            white-space: nowrap;
        }

        /* BRAND NAME ALL WHITE */
        .staff-brand-name span {
            color: #FFFFFF;
        }

        .staff-nav {
            flex: 1;
            min-height: 0;
            padding: 25px 15px;
            overflow-y: auto;
        }

        .staff-nav-label {
            color: #929AA5;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.1px;
            padding: 0 12px;
            margin-bottom: 11px;
        }

        .staff-nav a {
            display: flex;
            align-items: center;
            gap: 12px;

            min-height: 42px;
            padding: 11px 13px;
            margin-bottom: 4px;

            border-radius: 5px;
            color: #C1C6CE;

            font-size: 13px;
            font-weight: 500;

            transition: background .15s ease, color .15s ease;
        }

        .staff-nav a i {
            width: 18px;
            flex-shrink: 0;
            font-size: 14px;
            text-align: center;
        }

        .staff-nav a:hover {
            background: var(--graphite-light);
            color: white;
        }

        .staff-nav a.active {
            background: var(--emerald);
            color: white;
            font-weight: 600;
        }

        .staff-sidebar-footer {
            padding: 15px;
            border-top: 1px solid rgba(255,255,255,.09);
            flex-shrink: 0;
        }

        .staff-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            min-width: 0;
        }

        .staff-avatar {
            width: 36px;
            height: 36px;
            flex: 0 0 36px;

            border-radius: 5px;
            background: rgba(0,168,120,.18);
            color: #43D6A8;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: 700;
            font-size: 14px;
        }

        .staff-user-info {
            flex: 1;
            min-width: 0;
        }

        .staff-user-info strong {
            display: block;
            font-size: 12px;
            color: white;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .staff-user-info small {
            display: block;
            color: #929AA5;
            font-size: 10px;
            margin-top: 3px;
        }

        .staff-logout {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;
            border-radius: 5px;
            background: transparent;

            color: #AEB5BF;
            font-size: 14px;
            cursor: pointer;
        }

        .staff-logout:hover {
            background: rgba(220,70,70,.12);
            color: #FF7777;
        }



        .staff-main {
            width: calc(100% - var(--sidebar-width));
            min-width: 0;
            min-height: 100vh;
            min-height: 100dvh;
            margin-left: var(--sidebar-width);
        }

        .staff-topbar {
            position: sticky;
            top: 0;
            z-index: 900;

            min-height: 68px;
            background: var(--white);
            border-bottom: 1px solid var(--border);

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;

            padding: 0 30px;
        }

        .staff-topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .staff-topbar-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
            overflow-wrap: anywhere;
        }

        .staff-topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;

            color: var(--muted);
            font-size: 12px;
            flex-shrink: 0;
        }

        .staff-status {
            display: flex;
            align-items: center;
            gap: 7px;
            white-space: nowrap;
        }

        .staff-status-dot {
            width: 7px;
            height: 7px;
            background: var(--emerald);
            border-radius: 50%;
            flex-shrink: 0;
        }

        .staff-date {
            padding-left: 17px;
            border-left: 1px solid var(--border);
            white-space: nowrap;
        }

        .staff-content {
            width: 100%;
            min-width: 0;
            padding: 27px 30px;
        }

        /* SHARED COMPONENTS */

        .staff-content .card,
        .staff-content .panel {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(28,39,32,.025);
        }

        .staff-content .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            background: var(--emerald);
            color: white;
            border: 1px solid var(--emerald);

            padding: 10px 15px;
            border-radius: 4px;

            font-size: 12px;
            font-weight: 600;
            cursor: pointer;

            transition: background .15s ease;
        }

        .staff-content .btn-primary:hover {
            background: var(--emerald-dark);
        }

        .staff-content input,
        .staff-content select,
        .staff-content textarea {
            max-width: 100%;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: white;
            color: var(--text);
            font-size: 13px;
            outline: none;
        }

        .staff-content input:focus,
        .staff-content select:focus,
        .staff-content textarea:focus {
            border-color: var(--emerald);
            box-shadow: 0 0 0 2px rgba(0,168,120,.08);
        }

        .staff-content label {
            display: block;
            margin-bottom: 7px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text);
        }


        .staff-content .table-responsive {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .staff-content table {
            border-collapse: collapse;
        }

        .staff-content th {
            background: #F7F8F6;
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            padding: 12px 15px;
            border-bottom: 1px solid var(--border);
        }

        .staff-content td {
            padding: 13px 15px;
            font-size: 12px;
            border-bottom: 1px solid #EDF0EC;
        }

        .staff-content tbody tr:last-child td {
            border-bottom: none;
        }

        .staff-content tbody tr:hover {
            background: #FAFBF9;
        }

        .staff-menu-toggle {
            display: none;

            width: 38px;
            height: 38px;
            flex: 0 0 38px;

            align-items: center;
            justify-content: center;

            background: transparent;
            border: none;
            border-radius: 6px;

            color: var(--text);
            font-size: 18px;
            cursor: pointer;
        }

        .staff-menu-toggle:hover {
            background: var(--emerald-light);
            color: var(--emerald-dark);
        }

        .staff-overlay {
            display: none;
        }

        @media (max-width: 1100px) {
            .staff-content {
                padding: 24px 20px;
            }

            .staff-topbar {
                padding: 0 20px;
            }
        }


        @media (max-width: 900px) {
            .staff-sidebar {
                transform: translateX(-100%);
                box-shadow: 8px 0 25px rgba(0,0,0,.12);
            }

            .staff-sidebar.open {
                transform: translateX(0);
            }

            .staff-main {
                width: 100%;
                margin-left: 0;
            }

            .staff-menu-toggle {
                display: inline-flex;
            }

            .staff-overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.45);
                z-index: 999;
            }

            .staff-content {
                padding: 20px 16px;
            }
        }

        @media (max-width: 600px) {
            .staff-topbar {
                min-height: 60px;
                padding: 0 12px;
                gap: 8px;
            }

            .staff-topbar-left {
                gap: 8px;
            }

            .staff-topbar-right {
                gap: 8px;
            }

            .staff-topbar-right .staff-date {
                display: none;
            }

            .staff-topbar-title {
                font-size: 13px;
            }

            .staff-content {
                padding: 16px 12px;
            }
        }

        @media (max-width: 360px) {
            .staff-topbar {
                padding: 0 9px;
            }

            .staff-menu-toggle {
                width: 34px;
                height: 34px;
                flex-basis: 34px;
            }

            .staff-content {
                padding: 14px 10px;
            }
        }

        @stack('layout-styles')
    </style>

    @stack('styles')
</head>

<body>

    <aside class="staff-sidebar" id="staffSidebar">

        <div class="staff-brand">

            <div class="staff-brand-icon">
                <i class="fa-solid fa-soap"></i>
            </div>

            <div class="staff-brand-name">Lavanderie</div>

        </div>

        <nav class="staff-nav">

            <div class="staff-nav-label">WORKSPACE</div>

            <a href="{{ route('staff.dashboard') }}"
               class="{{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>
                Dashboard
            </a>

            <a href="{{ route('staff.orders.index') }}"
               class="{{ request()->routeIs('staff.orders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i>
                Orders
            </a>

            <a href="{{ route('staff.walk-ins.index') }}"
               class="{{ request()->routeIs('staff.walk-ins.*') ? 'active' : '' }}">
                <i class="fa-solid fa-store"></i>
                Walk-in Customers
            </a>

            <a href="{{ route('staff.delivery-dispatch.index') }}"
               class="{{ request()->routeIs('staff.delivery-dispatch.*') ? 'active' : '' }}">
                <i class="fa-solid fa-truck"></i>
                <span>Delivery Assignment</span>
            </a>

            <a href="{{ route('staff.processing.index') }}"
               class="{{ request()->routeIs('staff.processing.*') ? 'active' : '' }}">
                <i class="fa-solid fa-soap"></i>
                Laundry Processing
            </a>

            <a href="{{ route('staff.inventory.index') }}"
               class="{{ request()->routeIs('staff.inventory.*') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked"></i>
                Inventory
            </a>

        </nav>

        <div class="staff-sidebar-footer">

            <div class="staff-user-card">

                <div class="staff-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'S', 0, 1)) }}
                </div>

                <div class="staff-user-info">
                    <strong>{{ auth()->user()->name ?? 'Staff Member' }}</strong>
                    <small>Staff Account</small>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button type="submit" class="staff-logout" title="Logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>

            </div>

        </div>

    </aside>

    <div class="staff-overlay" id="staffOverlay"></div>

    <main class="staff-main">

        <header class="staff-topbar">

            <div class="staff-topbar-left">

                <button class="staff-menu-toggle"
                        id="staffMenuToggle"
                        type="button"
                        aria-label="Toggle navigation"
                        aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="staff-topbar-title">
                    @yield('page-heading', '')
                </div>

            </div>

            </div>

        </header>

        <section class="staff-content">
            @yield('content')
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const sidebar = document.getElementById('staffSidebar');
            const overlay = document.getElementById('staffOverlay');
            const menuToggle = document.getElementById('staffMenuToggle');

            if (!sidebar || !overlay || !menuToggle) {
                return;
            }

            function openSidebar() {
                sidebar.classList.add('open');
                overlay.classList.add('show');
                menuToggle.setAttribute('aria-expanded', 'true');
            }

            function closeSidebar() {
                sidebar.classList.remove('open');
                overlay.classList.remove('show');
                menuToggle.setAttribute('aria-expanded', 'false');
            }

            menuToggle.addEventListener('click', function () {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            overlay.addEventListener('click', closeSidebar);

            sidebar.querySelectorAll('.staff-nav a').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth <= 900) {
                        closeSidebar();
                    }
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 900) {
                    closeSidebar();
                }
            });

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
