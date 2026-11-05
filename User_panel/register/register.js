/* ============================================
   ONLINE POLICE SYSTEM — REGISTRATION SCRIPT
   ============================================ */
(function () {
    'use strict';

    // --------------------------------------------------
    // 1. Fix spelling in the confirm-password label
    // --------------------------------------------------
    const confirmLabel = document.querySelector('.conformpassword label');
    if (confirmLabel && confirmLabel.textContent.trim() === 'Conform password') {
        confirmLabel.textContent = 'Confirm Password';
    }

    // --------------------------------------------------
    // 2. Add eye toggle to both password fields
    // --------------------------------------------------
    const passwordInputs = [
        document.querySelector('.userpassword'),
        document.querySelector('.cpassword')
    ];

    passwordInputs.forEach(function (input) {
        if (!input) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'password-wrapper';

        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);

        const toggleBtn = document.createElement('span');
        toggleBtn.className = 'toggle-password';
        toggleBtn.textContent = '👁️';
        toggleBtn.setAttribute('aria-label', 'Show password');
        toggleBtn.setAttribute('role', 'button');
        toggleBtn.setAttribute('tabindex', '0');
        toggleBtn.style.fontSize = '1.15rem';
        toggleBtn.style.lineHeight = '1';
        toggleBtn.style.userSelect = 'none';

        wrapper.appendChild(toggleBtn);

        function togglePassword() {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);

            if (type === 'password') {
                toggleBtn.textContent = '👁️';
                toggleBtn.setAttribute('aria-label', 'Show password');
                toggleBtn.style.fontSize = '1.15rem';
            } else {
                toggleBtn.textContent = '🙈';
                toggleBtn.setAttribute('aria-label', 'Hide password');
                toggleBtn.style.fontSize = '1.1rem';
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
    });

    // --------------------------------------------------
    // 3. Image upload — live preview
    // --------------------------------------------------
    const imageInput   = document.getElementById('userimage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg   = document.getElementById('previewImg');

    if (imageInput && imagePreview && previewImg) {
        imageInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (file) {
                if (!file.type.startsWith('image/')) {
                    imagePreview.classList.remove('show');
                    previewImg.src = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    imagePreview.classList.add('show');
                };
                reader.readAsDataURL(file);
            } else {
                previewImg.src = '';
                imagePreview.classList.remove('show');
            }
        });
    }

    // --------------------------------------------------
    // 4. Prevent accidental form submission on Enter
    //    in the "Confirm Password" field
    // --------------------------------------------------
    const cpassword = document.querySelector('.cpassword');
    if (cpassword) {
        cpassword.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const submitBtn = document.querySelector('input[type="submit"]');
                if (submitBtn) submitBtn.focus();
            }
        });
    }

    // --------------------------------------------------
    // 5. Real-time password match indicator
    // --------------------------------------------------
    const userpassword   = document.querySelector('.userpassword');
    const cpasswordField = document.querySelector('.cpassword');

    function checkPasswordMatch() {
        if (!userpassword || !cpasswordField) return;

        const pw  = userpassword.value;
        const cpw = cpasswordField.value;

        if (pw.length === 0 && cpw.length === 0) {
            userpassword.style.borderColor   = '#d4e0ee';
            cpasswordField.style.borderColor = '#d4e0ee';
            return;
        }

        if (cpw.length === 0) {
            cpasswordField.style.borderColor = '#d4e0ee';
            userpassword.style.borderColor   = (pw.length > 0 && pw.length < 8)
                ? '#e74c3c'
                : '#d4e0ee';
            return;
        }

        if (pw === cpw && pw.length >= 8) {
            userpassword.style.borderColor   = '#2ecc71';
            cpasswordField.style.borderColor = '#2ecc71';
        } else {
            cpasswordField.style.borderColor = '#e74c3c';
            userpassword.style.borderColor   = (pw.length > 0 && pw.length < 8)
                ? '#e74c3c'
                : '#d4e0ee';
        }
    }

    if (userpassword && cpasswordField) {
        userpassword.addEventListener('input', checkPasswordMatch);
        cpasswordField.addEventListener('input', checkPasswordMatch);
    }

    // --------------------------------------------------
    // 6. Client-side validation before submit
    // --------------------------------------------------
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            const userid         = document.querySelector('.userid');
            const username       = document.querySelector('.username');
            const userphone      = document.querySelector('.userphone');
            const userpasswordEl = document.querySelector('.userpassword');
            const cpasswordEl    = document.querySelector('.cpassword');
            const imageEl        = document.querySelector('.userimage');
            const uploadLabel    = document.querySelector('.image-upload-label');

            [userid, username, userphone, userpasswordEl, cpasswordEl].forEach(function (el) {
                if (el) el.style.borderColor = '#d4e0ee';
            });
            if (uploadLabel) {
                uploadLabel.style.borderColor = '#c9a227';
                uploadLabel.style.background  = '#ffffff';
            }

            let hasError = false;

            if (!userid || !userid.value.trim()) {
                if (userid) userid.style.borderColor = '#e74c3c';
                hasError = true;
            }
            if (!username || !username.value.trim()) {
                if (username) username.style.borderColor = '#e74c3c';
                hasError = true;
            }
            if (!userphone || !userphone.value.trim()) {
                if (userphone) userphone.style.borderColor = '#e74c3c';
                hasError = true;
            }
            if (!userpasswordEl || !userpasswordEl.value.trim()) {
                if (userpasswordEl) userpasswordEl.style.borderColor = '#e74c3c';
                hasError = true;
            }
            if (!cpasswordEl || !cpasswordEl.value.trim()) {
                if (cpasswordEl) cpasswordEl.style.borderColor = '#e74c3c';
                hasError = true;
            }

            if (userpasswordEl && cpasswordEl &&
                userpasswordEl.value !== cpasswordEl.value) {
                userpasswordEl.style.borderColor = '#e74c3c';
                cpasswordEl.style.borderColor    = '#e74c3c';
                hasError = true;
            }

            if (userpasswordEl &&
                userpasswordEl.value.length > 0 &&
                userpasswordEl.value.length < 8) {
                userpasswordEl.style.borderColor = '#e74c3c';
                hasError = true;
            }

            if (imageEl && (!imageEl.files || imageEl.files.length === 0)) {
                if (uploadLabel) {
                    uploadLabel.style.borderColor = '#e74c3c';
                    uploadLabel.style.background  = 'rgba(231, 76, 60, 0.06)';
                }
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

    // --------------------------------------------------
    // 7. Add shake keyframe dynamically
    // --------------------------------------------------
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