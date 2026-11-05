let currentRejectCard = null;
let currentRejectId = null;

document.addEventListener('DOMContentLoaded', () => {

    /* Head office dropdown toggle */
    const toggleBtn = document.getElementById('headOfficeToggle');
    const subMenu = document.getElementById('headOfficeMenu');
    if (toggleBtn && subMenu) {
        toggleBtn.addEventListener('click', () => {
            subMenu.classList.toggle('open');
            toggleBtn.classList.toggle('active');
        });
    }

    /* ---------- SMOOTH SCROLL NAV ---------- */
    function setupNav(navId, sectionId) {
        const nav = document.getElementById(navId);
        const section = document.getElementById(sectionId);
        if (nav && section) {
            nav.addEventListener('click', (e) => {
                e.preventDefault();
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                document.querySelectorAll('.nav-links a').forEach(link => link.classList.remove('active'));
                nav.classList.add('active');
            });
        }
    }

    setupNav('navAllReports', 'all_reports');
    setupNav('navCrimeRate', 'crime_rate');
    setupNav('navAbout', 'about');
    setupNav('navBugReport', 'bug_report');

    const navHome = document.getElementById('navHome');
    if (navHome) {
        navHome.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
            document.querySelectorAll('.nav-links a').forEach(link => link.classList.remove('active'));
            navHome.classList.add('active');
        });
    }

    /* ---------- REJECT MODAL ---------- */
    const modal = document.getElementById('rejectModal');
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeRejectModal();
        });
    }

    document.addEventListener('keydown', (e) => {
        const m = document.getElementById('rejectModal');
        if (e.key === 'Escape' && m && m.classList.contains('active')) {
            closeRejectModal();
        }
    });

    /* Live validation for reject reason */
    const reasonInput = document.getElementById('rejectReason');
    if (reasonInput) {
        reasonInput.addEventListener('input', () => {
            if (reasonInput.value.trim().length >= 5) {
                reasonInput.classList.remove('error');
                const err = document.getElementById('reasonError');
                if (err) err.classList.remove('show');
            }
        });
    }

    /* Reject form submit */
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function (event) {
            const reasonEl = document.getElementById('rejectReason');
            const errorMsg = document.getElementById('reasonError');
            const reason = reasonEl.value.trim();

            if (reason.length < 5) {
                event.preventDefault();
                reasonEl.classList.add('error');
                errorMsg.classList.add('show');
                reasonEl.focus();
                return;
            }

            const submitBtn = document.querySelector('.btn-confirm-reject');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }
        });
    }

    /* ---------- BUG REPORT FORM VALIDATION ---------- */
    const bugForm = document.getElementById('bugReportForm');
    if (bugForm) {
        bugForm.addEventListener('submit', function (e) {
            const title = document.getElementById('bugTitle');
            const category = document.getElementById('bugCategory');
            const severity = document.getElementById('bugSeverity');
            const description = document.getElementById('bugDescription');
            const consent = document.getElementById('bugConsent');
            const errorBox = document.getElementById('bugFormError');
            const errorText = document.getElementById('bugFormErrorText');

            let errors = [];
            [title, category, severity, description].forEach(el => el.classList.remove('error'));

            if (title.value.trim().length < 5) {
                errors.push('Bug title must be at least 5 characters.');
                title.classList.add('error');
            }
            if (!category.value) {
                errors.push('Please select a category.');
                category.classList.add('error');
            }
            if (!severity.value) {
                errors.push('Please select a severity.');
                severity.classList.add('error');
            }
            if (description.value.trim().length < 15) {
                errors.push('Description must be at least 15 characters.');
                description.classList.add('error');
            }
            if (!consent.checked) {
                errors.push('Please confirm this is a genuine bug report.');
            }

            if (errors.length > 0) {
                e.preventDefault();
                errorText.textContent = errors[0];
                errorBox.classList.add('show');
                return;
            }

            errorBox.classList.remove('show');
            const btn = bugForm.querySelector('.bug-submit-btn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting…';
        });

        bugForm.querySelectorAll('input, select, textarea').forEach(el => {
            const clear = () => {
                el.classList.remove('error');
                document.getElementById('bugFormError').classList.remove('show');
            };
            el.addEventListener('input', clear);
            el.addEventListener('change', clear);
        });
    }
});

/* ---------- OPEN REJECT MODAL ---------- */
function openRejectModal(btn) {
    currentRejectCard = btn.closest('.report-card');
    currentRejectId = btn.getAttribute('data-report-id');

    const modal = document.getElementById('rejectModal');
    const reasonInput = document.getElementById('rejectReason');
    const errorMsg = document.getElementById('reasonError');
    const idLabel = document.getElementById('rejectReportId');
    const hiddenIdInput = document.getElementById('modalReportId');

    idLabel.textContent = `#${currentRejectId}`;
    if (hiddenIdInput) hiddenIdInput.value = currentRejectId;

    reasonInput.value = '';
    reasonInput.classList.remove('error');
    errorMsg.classList.remove('show');

    modal.classList.add('active');
    setTimeout(() => reasonInput.focus(), 350);
}

/* ---------- CLOSE REJECT MODAL ---------- */
function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('active');
    currentRejectCard = null;
    currentRejectId = null;
}