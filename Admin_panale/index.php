<!-- index.php -->
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');

    if(isset($_POST["submit"]))
    {
        $u_name = $_POST["user_id"];
        $u_password = $_POST["user_pass"];

        if(($u_name == "Shovan") && ($u_password == "1234"))
        {
            // Success – popup will be shown via JS, then redirect
            $login_success = true;
        }
        else
        {
            // Wrong credentials – popup will be shown via JS
            $login_error = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Admin Login · Indian Regulation</title>
    <link rel="stylesheet" href="style.css?v=1.0.2">
</head>
<body>
    <!-- Hidden flags for JS to know login result -->
    <?php if (isset($login_success) && $login_success === true): ?>
        <div id="login-success-flag" data-success="true" style="display:none;"></div>
    <?php endif; ?>

    <?php if (isset($login_error) && $login_error === true): ?>
        <div id="login-error-flag" data-error="true" style="display:none;"></div>
    <?php endif; ?>

    <!-- TOP-LEFT ADMIN PANEL LOGO -->
    <div class="admin-brand">
        <div class="brand-icon" aria-hidden="true">
            <svg viewBox="0 0 64 64" width="34" height="34" fill="none">
                <circle cx="32" cy="32" r="30" fill="#0b1f33" stroke="#ffd966" stroke-width="2.5"/>
                <path d="M32 12 L40 22 L54 24 L44 34 L47 48 L32 41 L17 48 L20 34 L10 24 L24 22 Z"
                      fill="#ffd966" stroke="#e6c34a" stroke-width="1.2"/>
                <circle cx="32" cy="30" r="4" fill="#0b1f33"/>
            </svg>
        </div>
        <div class="brand-text">
            <span class="brand-line-1">Online Police System</span>
            <span class="brand-line-2">Admin Panel</span>
        </div>
    </div>

    <div class="main-container">
        <!-- LEFT PANEL: Rules / regulations of Indian police -->
        <aside class="rules-panel">
            <h2>🇮🇳 Police Regulations</h2>
            <div class="rule-card">
                <h3>1. Politeness &amp; Civility</h3>
                <p>Police officers must behave politely and courteously with civilians. 
                   <span class="article-ref">(Article 21 – Right to dignity; Police Act 1861, Sec. 23)</span>
                </p>
            </div>
            <div class="rule-card">
                <h3>2. No harassment</h3>
                <p>Any form of harassment, intimidation, or abuse is prohibited. 
                   <span class="article-ref">(Indian Penal Code Sec. 506, 509; CrPC Sec. 41D)</span>
                </p>
            </div>
            <div class="rule-card">
                <h3>3. Right to information</h3>
                <p>Civilians have the right to know the grounds of arrest and to inform a relative. 
                   <span class="article-ref">(Article 22(1), CrPC Sec. 50, 50A)</span>
                </p>
            </div>
            <div class="rule-card">
                <h3>4. Respect for women &amp; children</h3>
                <p>Special care and politeness are mandatory, especially with women, children, and senior citizens. 
                   <span class="article-ref">(IPC Sec. 354, 509; Juvenile Justice Act)</span>
                </p>
            </div>
            <div class="rule-card">
                <h3>5. No third-degree</h3>
                <p>Physical or mental torture is strictly forbidden. 
                   <span class="article-ref">(Article 21, D.K. Basu v. State of W.B. 1997)</span>
                </p>
            </div>
            <p class="footnote">* These are excerpts from Indian law, police manuals, and Supreme Court judgments.</p>
        </aside>

        <!-- RIGHT PANEL: Admin login form -->
        <div class="login-panel">
            <div class="login admin-login">
                <div class="admin-login-badge">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="#0b1f33" stroke-width="2">
                        <path d="M12 2 L20 6 V12 C20 17 16.5 20.5 12 22 C7.5 20.5 4 17 4 12 V6 Z"/>
                    </svg>
                    Secure Admin Access
                </div>
                <h1>Admin Login</h1>
                <p class="login-subtitle">Authorized personnel only · Indian Police System</p>
                <form action="" method="POST" id="loginForm" autocomplete="off">
                    <div class="user_input">
                        <label for="user_id">Admin User ID</label>
                        <input type="text" name="user_id" id="user_id" placeholder="Enter admin user id" required>
                    </div>

                    <div class="user_password">
                        <label for="user_password">Admin Password</label>
                        <input type="password" name="user_pass" id="user_password" placeholder="Enter admin password" required>
                    </div>

                    <div class="submit">
                        <input type="submit" id="Submit" name="submit" value="LOGIN">
                    </div>
                </form>
                <div class="login-footer-note">
                    <span class="dot"></span> Encrypted session · Authorized use only
                </div>
            </div>
        </div>
    </div>

    <!-- Popup modal: SUCCESS (hidden by default) -->
    <div id="successPopup" class="popup-overlay" style="display: none;">
        <div class="popup-box">
            <h2>✔ Login Successful</h2>
            <p>Welcome, Admin Shovan.<br>Redirecting to home page…</p>
            <div class="popup-loader"></div>
        </div>
    </div>

    <!-- Popup modal: ERROR (hidden by default) -->
    <div id="errorPopup" class="popup-overlay" style="display: none;">
        <div class="popup-box popup-error">
            <div class="popup-error-icon" aria-hidden="true">✕</div>
            <h2>Login Failed</h2>
            <p>Wrong ID or password.<br>Please check your credentials and try again.</p>
            <button type="button" class="popup-close-btn" id="errorPopupClose">Try Again</button>
        </div>
    </div>

    <script src="script.js?v=1.0.2"></script>
</body>
</html>