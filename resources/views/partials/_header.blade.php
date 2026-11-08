
<header class="ll-public-header">
    <div class="ll-nav-container">

        <a href="{{ url('/') }}#home" class="ll-brand">
            <span class="ll-brand-icon">
                <i class="fa-solid fa-shirt"></i>
            </span>

            <span class="ll-brand-copy">
                <strong>Lavanderie</strong>
                <small>Laundry Made Simple.</small>
            </span>
        </a>

        <nav class="ll-nav-links">
            <a href="{{ url('/') }}#home">Home</a>
            <a href="{{ url('/') }}#services">Services</a>
            <a href="{{ url('/') }}#about">About</a>
            <a href="{{ url('/') }}#staff">Our Team</a>
        </nav>

        <div class="ll-nav-actions">
            @guest
                <a href="{{ route('login') }}" class="ll-login-link">Login</a>

                <a href="{{ route('register') }}" class="ll-get-started">
                    Get Started
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @else
                <a href="{{ route('customer.dashboard') }}" class="ll-get-started">
                    My Dashboard
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            @endguest
        </div>

    </div>
</header>

<style>
.ll-public-header,
.ll-public-header * {
    box-sizing: border-box;
}

.ll-public-header {
    position: relative;
    z-index: 1000;
    width: 100%;
    height: 78px;
    background: #fff;
    border-bottom: 1px solid #edf0f4;
}

.ll-nav-container {
    width: min(1200px, calc(100% - 48px));
    height: 100%;
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.ll-brand {
    display: flex;
    align-items: center;
    gap: 11px;
    text-decoration: none;
}

.ll-brand-icon {
    width: 43px;
    height: 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    background: #2876d2;
    color: #fff;
    font-size: 19px;
}

.ll-brand-copy strong {
    display: block;
    color: #19354d;
    font-size: 19px;
    font-weight: 800;
}

.ll-brand-copy small {
    display: block;
    margin-top: 2px;
    color: #8a99a8;
    font-size: 10px;
}

.ll-nav-links {
    display: flex;
    align-items: center;
    gap: 32px;
}

.ll-nav-links a,
.ll-login-link {
    color: #53677b;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: color .2s ease;
}

.ll-nav-links a:hover,
.ll-login-link:hover {
    color: #2876d2;
}

.ll-nav-actions {
    display: flex;
    align-items: center;
    gap: 22px;
}

.ll-get-started {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 12px 17px;
    border-radius: 5px;
    background: #2876d2;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    transition: background .2s ease;
}

.ll-get-started:hover {
    background: #195eaf;
    color: #fff;
}

@media (max-width: 768px) {
    .ll-nav-container {
        width: calc(100% - 28px);
    }

    .ll-nav-links {
        display: none;
    }

    .ll-nav-actions {
        gap: 12px;
    }

    .ll-get-started {
        padding: 10px 12px;
    }
}

@media (max-width: 420px) {
    .ll-brand-copy strong {
        font-size: 16px;
    }

    .ll-brand-copy small {
        font-size: 9px;
    }

    .ll-login-link {
        font-size: 12px;
    }
}
</style>
