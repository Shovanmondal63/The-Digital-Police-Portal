/* ============================================
   ONLINE POLICE SERVICE — HOME SCRIPT
   ============================================ */
(function () {
    'use strict';

    const navbar      = document.getElementById('navbar');
    const hamburger   = document.getElementById('hamburger');
    const navMenu     = document.getElementById('navMenu');
    const profileBtn  = document.getElementById('profileBtn');
    const dropdown    = document.getElementById('profileDropdown');
    const navLinks    = document.querySelectorAll('.nav-link');
    const toTop       = document.getElementById('toTop');

    // Height of the sticky navbar (used as scroll offset)
    function navHeight() { return navbar.offsetHeight; }

    // --------------------------------------------------
    // Navbar shadow + back-to-top button on scroll
    // --------------------------------------------------
    function onScroll() {
        navbar.classList.toggle('scrolled', window.scrollY > 10);
        if (toTop) toTop.classList.toggle('show', window.scrollY > 500);
        updateActiveLink();
    }

    // --------------------------------------------------
    // Mobile menu (hamburger)
    // --------------------------------------------------
    function setMenu(open) {
        navMenu.classList.toggle('open', open);
        hamburger.classList.toggle('open', open);
        hamburger.setAttribute('aria-expanded', String(open));
    }

    hamburger.addEventListener('click', function (e) {
        e.stopPropagation();
        setMenu(!navMenu.classList.contains('open'));
        setProfile(false);
    });

    // --------------------------------------------------
    // Profile dropdown
    // --------------------------------------------------
    function setProfile(open) {
        dropdown.classList.toggle('open', open);
        profileBtn.setAttribute('aria-expanded', String(open));
    }

    profileBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        setProfile(!dropdown.classList.contains('open'));
        setMenu(false);
    });

    dropdown.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    document.addEventListener('click', function () {
        setProfile(false);
        setMenu(false);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            setProfile(false);
            setMenu(false);
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 760) setMenu(false);
    });

    // --------------------------------------------------
    // Smooth scroll to a section (nav links + any #anchor)
    // --------------------------------------------------
    function scrollToTarget(hash) {
        const target = document.querySelector(hash);
        if (!target) return false;

        const top = target.getBoundingClientRect().top + window.pageYOffset - navHeight();
        window.scrollTo({ top: top, behavior: 'smooth' });
        return true;
    }

    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            const hash = link.getAttribute('href');

            if (hash === '#') {
                e.preventDefault();
                return;
            }

            if (scrollToTarget(hash)) {
                e.preventDefault();
                history.replaceState(null, '', hash);
                setMenu(false);
            }
        });
    });

    if (toTop) {
        toTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // --------------------------------------------------
    // Scroll-spy: highlight the nav link of the section in view
    // --------------------------------------------------
    const spyLinks = Array.prototype.filter.call(navLinks, function (l) {
        const h = l.getAttribute('href');
        return h && h.length > 1 && document.querySelector(h);
    });

    function setActive(link) {
        navLinks.forEach(function (l) { l.classList.remove('active'); });
        if (link) link.classList.add('active');
    }

    function updateActiveLink() {
        if (!spyLinks.length) return;

        const marker = navHeight() + 140;
        let current = spyLinks[0];

        spyLinks.forEach(function (link) {
            const section = document.querySelector(link.getAttribute('href'));
            if (section && section.getBoundingClientRect().top <= marker) {
                current = link;
            }
        });

        if (window.innerHeight + window.scrollY >= document.body.scrollHeight - 4) {
            current = spyLinks[spyLinks.length - 1];
        }

        setActive(current);
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // --------------------------------------------------
    // Reveal-on-scroll animation
    // --------------------------------------------------
    const revealEls = document.querySelectorAll('.reveal');

    if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        revealEls.forEach(function (el) { io.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('in'); });
    }

    // --------------------------------------------------
    // POPUP / MODAL
    // --------------------------------------------------
    const popupOverlay = document.getElementById('opsPopup');
    const popupClose   = document.getElementById('opsPopupClose');
    const popupBtn     = document.getElementById('opsPopupBtn');
    const popupIcon    = document.getElementById('opsPopupIcon');
    const popupTitle   = document.getElementById('opsPopupTitle');
    const popupMessage = document.getElementById('opsPopupMessage');

    function showPopup(type, title, message) {
        if (!popupOverlay) return;

        popupOverlay.classList.remove('success', 'error');
        popupOverlay.classList.add(type === 'error' ? 'error' : 'success');

        popupIcon.innerHTML = type === 'error'
            ? '<i class="fas fa-circle-exclamation"></i>'
            : '<i class="fas fa-circle-check"></i>';

        popupTitle.textContent   = title;
        popupMessage.textContent = message;

        popupOverlay.classList.add('show');
        popupOverlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function hidePopup() {
        if (!popupOverlay) return;
        popupOverlay.classList.remove('show');
        popupOverlay.style.display = 'none';
        document.body.style.overflow = '';
    }

    if (popupClose) popupClose.addEventListener('click', hidePopup);
    if (popupBtn)   popupBtn.addEventListener('click', hidePopup);

    if (popupOverlay) {
        popupOverlay.addEventListener('click', function (e) {
            if (e.target === popupOverlay) hidePopup();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') hidePopup();
    });

    // ✅ Show popup on page load if PHP set the flag
    window.addEventListener('load', function () {
        if (window.__OPS_POPUP__) {
            const p = window.__OPS_POPUP__;
            setTimeout(function () {
                showPopup(p.type, p.title, p.message);
                window.__OPS_POPUP__ = null;
                if (location.hash) {
                    history.replaceState(null, '', location.pathname);
                }
            }, 250);
        }
    });

    // --------------------------------------------------
    // If page is opened with #diary (or any #section), scroll there
    // --------------------------------------------------
    if (location.hash && location.hash.length > 1 && !window.__OPS_POPUP__) {
        window.addEventListener('load', function () {
            setTimeout(function () { scrollToTarget(location.hash); }, 80);
        });
    }

})();