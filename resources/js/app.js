window.addEventListener('load', function () {
    if (window.jQuery) {
        const $ = window.jQuery;

        const $toggle = $('#menuToggle');
        const $navigation = $('#mainNavigation');

        function closeMenu() {
            $navigation.removeClass('is-open');
            $('body').removeClass('menu-open');

            $toggle.attr('aria-expanded', 'false');
            $toggle.attr('aria-label', 'Open navigation');
            $toggle.find('i')
                .removeClass('fa-xmark')
                .addClass('fa-bars');
        }

        $toggle.on('click', function () {
            const isOpen = $navigation.hasClass('is-open');

            if (isOpen) {
                closeMenu();
            } else {
                $navigation.addClass('is-open');
                $('body').addClass('menu-open');

                $toggle.attr('aria-expanded', 'true');
                $toggle.attr('aria-label', 'Close navigation');
                $toggle.find('i')
                    .removeClass('fa-bars')
                    .addClass('fa-xmark');
            }
        });

        $navigation.find('a').on('click', function () {
            closeMenu();
        });

        $(window).on('resize', function () {
            if (window.innerWidth > 760) {
                closeMenu();
            }
        });

        const $sections = $('main section[id]');
        const $navLinks = $('.nav-links a');

        function updateActiveLink() {
            let current = 'home';
            const scrollPosition = $(window).scrollTop() + 130;

            $sections.each(function () {
                if ($(this).offset().top <= scrollPosition) {
                    current = this.id;
                }
            });

            $navLinks.removeClass('active');
            $navLinks
                .filter('[href="#' + current + '"]')
                .addClass('active');
        }

        $(window).on('scroll', updateActiveLink);
        updateActiveLink();
    }

    if (window.AOS) {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: false,
            offset: 100
        });
    }
});
