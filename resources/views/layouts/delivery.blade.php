
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Delivery') | LinisLaba</title>

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

        html, body {
            margin: 0;
            min-height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--page-bg);
            color: var(--text-dark);
            font-size: 14px;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        .delivery-sidebar {
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
            font-size: 22px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.7px;
            white-space: nowrap;
        }

        .brand-name span {
            color: #00D6A0;
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
            border-top: 1px solid rgba(255,255,255,0.10);
            margin: 0 0 14px;
        }

        .logout-form {
            margin: 0;
            padding: 0;
        }

        .logout-link:hover {
            background: rgba(220,70,70,0.12);
            color: #FF8585;
        }

        .delivery-main {
            margin-left: var(--sidebar-width);
            min-width: 0;
            min-height: 100vh;
            min-height: 100dvh;
        }

        .delivery-topbar {
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

        .delivery-content {
            width: 100%;
            min-width: 0;
            padding: 27px 30px;
        }


        .topbar-profile {
            position: relative;
            flex-shrink: 0;
        }

        .profile-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: var(--text-muted);
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .profile-toggle:hover,
        .profile-toggle[aria-expanded="true"] {
            background: var(--emerald-light);
            border-color: #D5EEE3;
        }

        .profile-avatar {
            color: var(--emerald);
            font-size: 21px;
        }

        .profile-name {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .profile-chevron {
            color: #89928D;
            font-size: 10px;
            transition: transform 0.2s ease;
        }

        .profile-toggle[aria-expanded="true"] .profile-chevron {
            transform: rotate(180deg);
        }

        .profile-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 280px;
            max-width: calc(100vw - 24px);
            background: #FFFFFF;
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(37,42,52,0.12);
            z-index: 2000;
            overflow: hidden;
        }

        .profile-dropdown.show {
            display: block;
        }

        .profile-dropdown-header {
            padding: 18px;
            background: #F8FBF9;
            border-bottom: 1px solid var(--border);
        }

        .profile-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .profile-large-avatar {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: var(--emerald-light);
            color: var(--emerald-dark);
            font-size: 18px;
        }

        .profile-details {
            min-width: 0;
        }

        .profile-dropdown-name {
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .profile-dropdown-email {
            margin-top: 4px;
            color: var(--text-muted);
            font-size: 11px;
            overflow-wrap: anywhere;
        }

        .profile-dropdown-role {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 12px;
            padding: 6px 9px;
            border-radius: 5px;
            background: var(--emerald-light);
            color: var(--emerald-dark);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.4px;
        }

        .profile-dropdown-body {
            padding: 8px;
        }

        .profile-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: #DC5555;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
        }

        .profile-logout:hover {
            background: #FFF1F1;
        }

        .mobile-menu-button {
            display: none;
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 7px;
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


        @media (max-width: 900px) {
            .delivery-sidebar {
                transform: translateX(-100%);
                box-shadow: 8px 0 25px rgba(0,0,0,0.12);
            }

            .delivery-sidebar.sidebar-open {
                transform: translateX(0);
            }

            .delivery-main {
                margin-left: 0;
            }

            .mobile-menu-button {
                display: inline-flex;
            }

            .sidebar-overlay.overlay-open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.45);
                z-index: 999;
            }

            .delivery-content {
                padding: 22px 20px;
            }

            .delivery-topbar {
                min-height: 64px;
                padding: 12px 20px;
            }
        }

        @media (max-width: 600px) {
            .delivery-content {
                padding: 16px 12px;
            }

            .delivery-topbar {
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

            .profile-toggle {
                padding: 7px;
                gap: 6px;
            }

            .profile-name {
                display: none;
            }

            .profile-dropdown {
                right: 0;
                width: min(280px, calc(100vw - 24px));
            }
        }

        @media (max-width: 360px) {
            .topbar-title {
                font-size: 14px;
            }

            .mobile-menu-button {
                width: 34px;
                height: 34px;
            }

            .delivery-content {
                padding: 14px 10px;
            }
        }

        @stack('layout-styles')
    </style>

    @stack('styles')
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="delivery-sidebar" id="deliverySidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="fa-solid fa-shirt"></i>
            </div>

            <div>
                <div class="brand-name">Linis<span>Laba</span></div>
                <div class="brand-description">Delivery Personnel Portal</div>
            </div>
        </div>

        <div class="sidebar-heading">WORKSPACE</div>

        <nav class="sidebar-menu">

            <a href="{{ route('delivery.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('delivery.deliveries.index') }}"
               class="sidebar-link {{ request()->routeIs('delivery.deliveries.*') ? 'active' : '' }}">
                <i class="fa-solid fa-box"></i>
                <span>My Deliveries</span>
            </a>

            <a href="{{ route('delivery.tracking.index') }}"
               class="sidebar-link {{ request()->routeIs('delivery.tracking.*') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot"></i>
                <span>Delivery Tracking</span>
            </a>

            <a href="{{ route('delivery.history.index') }}"
               class="sidebar-link {{ request()->routeIs('delivery.history.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Delivery History</span>
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

    <main class="delivery-main">

        <header class="delivery-topbar">

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

            <div class="topbar-profile">

                <button type="button"
                        class="profile-toggle"
                        id="profileToggle"
                        aria-expanded="false"
                        aria-haspopup="true"
                        aria-controls="profileDropdown">

                    <i class="fa-solid fa-circle-user profile-avatar"></i>

                    <span class="profile-name">
                        {{ auth()->user()->name ?? 'Delivery Personnel' }}
                    </span>

                    <i class="fa-solid fa-chevron-down profile-chevron"></i>
                </button>

                <div class="profile-dropdown" id="profileDropdown">

                    <div class="profile-dropdown-header">

                        <div class="profile-info">

                            <div class="profile-large-avatar">
                                <i class="fa-solid fa-user"></i>
                            </div>

                            <div class="profile-details">
                                <div class="profile-dropdown-name">
                                    {{ auth()->user()->name ?? 'Delivery Personnel' }}
                                </div>

                                <div class="profile-dropdown-email">
                                    {{ auth()->user()->email ?? 'No email available' }}
                                </div>
                            </div>

                        </div>

                        <span class="profile-dropdown-role">
                            <i class="fa-solid fa-truck-fast"></i>
                            DELIVERY PERSONNEL
                        </span>

                    </div>

                    <div class="profile-dropdown-body">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf

                            <button type="submit" class="profile-logout">
                                <i class="fa-solid fa-right-from-bracket"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>

                </div>

            </div>

        </header>

        <section class="delivery-content">
            @yield('content')
        </section>

    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(function () {

            const sidebar = $('#deliverySidebar');
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

            const profileToggle = $('#profileToggle');
            const profileDropdown = $('#profileDropdown');

            function closeProfileDropdown() {
                profileDropdown.removeClass('show');
                profileToggle.attr('aria-expanded', 'false');
            }

            profileToggle.on('click', function (event) {
                event.stopPropagation();

                const isOpen = profileDropdown.hasClass('show');

                profileDropdown.toggleClass('show', !isOpen);
                profileToggle.attr('aria-expanded', String(!isOpen));
            });

            $(document).on('click', function (event) {
                if (!$(event.target).closest('.topbar-profile').length) {
                    closeProfileDropdown();
                }
            });

            $(document).on('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                    closeProfileDropdown();
                }
            });

        });
    </script>

    @stack('scripts')

</body>
</html>
