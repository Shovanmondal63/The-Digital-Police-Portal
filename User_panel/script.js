/* ============================================
   ONLINE POLICE SYSTEM — LOGIN SCRIPT
   ============================================ */
(function () {
    'use strict';

    // --------------------------------------------------
    // Password eye toggle (emoji version)
    // --------------------------------------------------
    const passwordInput = document.querySelector('.userpassword');

    if (passwordInput) {
        const wrapper = document.createElement('div');
        wrapper.className = 'password-wrapper';
        passwordInput.parentNode.insertBefore(wrapper, passwordInput);
        wrapper.appendChild(passwordInput);

        const toggleBtn = document.createElement('span');
        toggleBtn.className = 'toggle-password';
        toggleBtn.textContent = '👁️';
        toggleBtn.setAttribute('aria-label', 'Show password');
        toggleBtn.setAttribute('role', 'button');
        toggleBtn.setAttribute('tabindex', '0');

        wrapper.appendChild(toggleBtn);

        function togglePassword() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'password') {
                toggleBtn.textContent = '👁️';
                toggleBtn.setAttribute('aria-label', 'Show password');
            } else {
                toggleBtn.textContent = '🙈';
                toggleBtn.setAttribute('aria-label', 'Hide password');
            }
        }

        toggleBtn.addEventListener('click', function (e) {
            e.preventDefault();
            togglePassword();
        });

        toggleBtn.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                togglePassword();
            }
        });
    }

    // --------------------------------------------------
    // Simple login validation
    // --------------------------------------------------
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const userid = document.querySelector('.userid');
            const password = document.querySelector('.userpassword');

            [userid, password].forEach(el => { if (el) el.style.borderColor = '#d4e0ee'; });

            let hasError = false;

            if (!userid.value.trim()) {
                userid.style.borderColor = '#e74c3c';
                hasError = true;
            }
            if (!password.value.trim()) {
                password.style.borderColor = '#e74c3c';
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                form.style.animation = 'none';
                form.offsetHeight;
                form.style.animation = 'shake 0.4s ease';
                return false;
            }
        });
    }

    // Shake keyframe
    if (!document.getElementById('shake-style')) {
        const style = document.createElement('style');
        style.id = 'shake-style';
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                20% { transform: translateX(-6px); }
                40% { transform: translateX(6px); }
                60% { transform: translateX(-4px); }
                80% { transform: translateX(4px); }
            }
        `;
        document.head.appendChild(style);
    }
})();

/* ============================================
   WELCOME POPUP — LOGIC
   Shows popup, then redirects to home page.
   ============================================ */
(function () {
    'use strict';

    const overlay = document.getElementById('welcomeOverlay');
    if (!overlay) return;

    const redirectUrl = window.__WELCOME_REDIRECT__ || 'home/home.php';

    // Small delay so the browser paints the overlay first,
    // then trigger the "show" animation.
    requestAnimationFrame(function () {
        setTimeout(function () {
            overlay.classList.add('show');
        }, 60);
    });

    // Lock body scroll while popup is visible
    const prevOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';

    // Total time the popup stays visible before redirect (ms)
    const POPUP_DURATION = 2600;

    setTimeout(function () {
        // Fade out
        overlay.classList.add('hide');

        // Redirect after fade-out completes
        setTimeout(function () {
            document.body.style.overflow = prevOverflow;
            window.location.href = redirectUrl;
        }, 450);
    }, POPUP_DURATION);

    // Safety: if anything goes wrong, still redirect
    setTimeout(function () {
        if (window.location.pathname.indexOf('home.php') === -1) {
            window.location.href = redirectUrl;
        }
    }, POPUP_DURATION + 2500);
})();


/* ============================================
   LOGIN FAILED POPUP — LOGIC
   ============================================ */
(function () {
    'use strict';

    const overlay = document.getElementById('errorOverlay');
    if (!overlay) return;

    const closeBtn = document.getElementById('errorCloseBtn');
    const prevOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';

    requestAnimationFrame(function () {
        setTimeout(function () {
            overlay.classList.add('show', 'shake');
        }, 60);
    });

    function closePopup() {
        overlay.classList.remove('show');
        overlay.classList.add('hide');
        document.body.style.overflow = prevOverflow;

        setTimeout(function () {
            overlay.remove();
            const userid = document.querySelector('.userid');
            if (userid) userid.focus();
        }, 400);
    }

    closeBtn.addEventListener('click', closePopup);

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closePopup();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closePopup();
    });

    // Auto-close after 5 seconds
    setTimeout(closePopup, 5000);

    closeBtn.focus();
})();