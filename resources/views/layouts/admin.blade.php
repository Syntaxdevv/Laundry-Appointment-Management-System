
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('favicon.jpg') }}">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
            font-size: 14px;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
        }

       
        .admin-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            height: 100vh;
            height: 100dvh;
            padding: 12px 16px;

            display: flex;
            flex-direction: column;

            background: var(--graphite);
            color: var(--white);

            z-index: 1000;
            overflow-x: hidden;
            overflow-y: auto;
            overscroll-behavior: contain;

            transition: transform 0.25s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;

            padding: 0 8px;
            margin: 0 0 28px;

            min-height: 64px;
            flex-shrink: 0;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            flex: 0 0 43px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--emerald);
            border-radius: 8px;

            color: var(--white);
            font-size: 21px;
        }

        .brand-name {
            color: #FFFFFF;
            font-size: 21px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
            white-space: nowrap;
        }

        .brand-name span {
            color: #FFFFFF;
        }

        .brand-description {
            margin-top: 4px;
            color: #A9B2B0;
            font-size: 10px;
            white-space: nowrap;
        }

        .sidebar-heading {
            margin: 0 10px 10px;
            color: #929D9A;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
            flex-shrink: 0;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex-shrink: 0;
        }

        .sidebar-link {
            width: 100%;
            min-height: 43px;
            padding: 0 12px;

            display: flex;
            align-items: center;
            gap: 12px;

            border: 0;
            border-radius: 6px;

            background: transparent;
            color: #C5CECA;

            font-family: inherit;
            font-size: 13px;
            font-weight: 500;
            text-align: left;
            text-decoration: none;

            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .sidebar-link i {
            width: 18px;
            flex-shrink: 0;
            text-align: center;
            font-size: 14px;
        }

        .sidebar-link:hover {
            background: var(--graphite-light);
            color: var(--white);
        }

        .sidebar-link.active {
            background: rgba(0, 168, 120, 0.16);
            color: #42D5A8;
            font-weight: 600;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding-top: 18px;
            padding-bottom: 3px;
            flex-shrink: 0;
        }

        .sidebar-divider {
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.10);
            margin: 0 0 14px;
        }

        .logout-form {
            margin: 0;
            padding: 0;
        }

        .logout-link {
            color: #C5CECA;
        }

        .logout-link:hover {
            background: rgba(220, 70, 70, 0.12);
            color: #FF8585;
        }
        .admin-main {
            margin-left: var(--sidebar-width);
            min-width: 0;
            min-height: 100vh;
            min-height: 100dvh;
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 900;

            min-height: 68px;
            padding: 14px 30px;

            background: var(--white);
            border-bottom: 1px solid var(--border);

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }

        .topbar-title {
            margin: 0;
            color: var(--text-dark);
            font-size: 19px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 9px;
            flex-shrink: 0;

            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .topbar-user i {
            color: var(--emerald);
            font-size: 18px;
        }

        .admin-content {
            width: 100%;
            min-width: 0;
            padding: 27px 30px;
        }

        .mobile-menu-button {
            display: none;

            width: 38px;
            height: 38px;
            flex-shrink: 0;

            align-items: center;
            justify-content: center;

            border: 0;
            border-radius: 6px;

            background: transparent;
            color: var(--text-dark);

            font-size: 19px;
            cursor: pointer;
        }

        .mobile-menu-button:hover {
            background: var(--emerald-light);
            color: var(--emerald-dark);
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 1100px) {
            .admin-content {
                padding: 24px 20px;
            }

            .admin-topbar {
                padding: 14px 20px;
            }
        }
        @media (max-width: 900px) {
            .admin-sidebar {
                transform: translateX(-100%);
                box-shadow: 8px 0 25px rgba(0, 0, 0, 0.12);
            }

            .admin-sidebar.sidebar-open {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .mobile-menu-button {
                display: inline-flex;
            }

            .sidebar-overlay.overlay-open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.45);
                z-index: 999;
            }

            .admin-content {
                padding: 20px 16px;
            }

            .admin-topbar {
                min-height: 65px;
                padding: 12px 16px;
            }
        }

        @media (max-width: 600px) {
            .admin-topbar {
                min-height: 60px;
                padding: 10px 12px;
                gap: 8px;
            }

            .topbar-left {
                gap: 8px;
            }

            .topbar-title {
                font-size: 16px;
            }

            .topbar-user {
                gap: 6px;
                font-size: 11px;
                max-width: 130px;
            }

            .topbar-user span {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .admin-content {
                padding: 16px 12px;
            }
        }

        @media (max-width: 360px) {
            .topbar-title {
                font-size: 14px;
            }

            .topbar-user {
                max-width: 105px;
            }

            .mobile-menu-button {
                width: 34px;
                height: 34px;
            }

            .admin-content {
                padding: 14px 10px;
            }
        }

        @stack('layout-styles')
    </style>

    @stack('styles')
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="admin-sidebar" id="adminSidebar">

        <div class="sidebar-brand">

            <div class="brand-icon">
                <i class="fa-solid fa-shirt"></i>
            </div>

            <div>
                <div class="brand-name">Lavanderie</div>
                <div class="brand-description">Laundry Management System</div>
            </div>

        </div>

        <div class="sidebar-heading">WORKSPACE</div>

        <nav class="sidebar-menu">

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Orders Management</span>
            </a>

            <a href="{{ route('admin.customers.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('admin.inventory.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
                <i class="fas fa-boxes-stacked"></i>
                <span>Inventory</span>
            </a>

            <a href="{{ route('admin.payments.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <i class="fa-solid fa-credit-card"></i>
                <span>Payments</span>
            </a>

            <a href="{{ route('admin.reports.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i>
                <span>Reports &amp; Analytics</span>
            </a>

            <a href="{{ route('admin.staff.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-gear"></i>
                <span>Staff Management</span>
            </a>

        </nav>

        <div class="sidebar-bottom">

            <hr class="sidebar-divider">

            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf

                <button type="submit" class="sidebar-link logout-link">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>

        </div>

    </aside>

    <main class="admin-main">

        <header class="admin-topbar">

            <div class="topbar-left">

                <button type="button"
                        class="mobile-menu-button"
                        id="mobileMenuButton"
                        aria-label="Toggle navigation"
                        aria-expanded="false">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <h1 class="topbar-title">
                    @yield('page-heading', '')
                </h1>

            </div>

            <div class="topbar-user">
                <i class="fa-solid fa-circle-user"></i>
                <span>{{ auth()->user()->name ?? 'Administrator' }}</span>
            </div>

        </header>

        <section class="admin-content">
            @yield('content')
        </section>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(function () {

            const sidebar = $('#adminSidebar');
            const overlay = $('#sidebarOverlay');
            const menuButton = $('#mobileMenuButton');

            function closeSidebar() {
                sidebar.removeClass('sidebar-open');
                overlay.removeClass('overlay-open');
                menuButton.attr('aria-expanded', 'false');
            }

            menuButton.on('click', function () {
                const isOpen = sidebar.hasClass('sidebar-open');

                sidebar.toggleClass('sidebar-open', !isOpen);
                overlay.toggleClass('overlay-open', !isOpen);

                menuButton.attr('aria-expanded', String(!isOpen));
            });

            overlay.on('click', closeSidebar);

            $('.sidebar-menu .sidebar-link').on('click', function () {
                if (window.innerWidth <= 900) {
                    closeSidebar();
                }
            });

            $(document).on('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

        });
    </script>

    @stack('scripts')

</body>
</html>
