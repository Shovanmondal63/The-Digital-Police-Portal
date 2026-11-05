// script.js
document.addEventListener('DOMContentLoaded', function () {
    // ----- SUCCESS -----
    const successFlag = document.getElementById('login-success-flag');
    if (successFlag && successFlag.dataset.success === 'true') {
        const popup = document.getElementById('successPopup');
        popup.style.display = 'flex';

        // After 2 seconds, redirect to the home page
        setTimeout(function () {
            window.location.href = 'home_page/home.php';
        }, 2000);
    }

    // ----- ERROR -----
    const errorFlag = document.getElementById('login-error-flag');
    if (errorFlag && errorFlag.dataset.error === 'true') {
        const errorPopup = document.getElementById('errorPopup');
        const closeBtn = document.getElementById('errorPopupClose');

        errorPopup.style.display = 'flex';

        function closeErrorPopup() {
            errorPopup.style.display = 'none';
            const pwd = document.getElementById('user_password');
            if (pwd) {
                pwd.value = '';
                pwd.focus();
            }
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', closeErrorPopup);
        }

        // Also close when clicking the dark overlay background
        errorPopup.addEventListener('click', function (e) {
            if (e.target === errorPopup) {
                closeErrorPopup();
            }
        });
    }
});