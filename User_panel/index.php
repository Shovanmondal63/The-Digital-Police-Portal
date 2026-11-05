<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();




if(isset($_POST["submit"]))
    {
        $user = $_POST["userid"];
        $password = $_POST["userpassword"];

        $servername = "localhost";
        $username = "root";
        $db_password = "";
        $db_name = "OPS";

        $conn = new mysqli($servername , $username , $db_password , $db_name);

        if($conn -> connect_error)
        {
            die("Connection error!!!");
        }
           

        $sql = "SELECT user_id,full_name,phone,u_password,user_image FROM civilians_data";

        $result = $conn -> query($sql);

        $found = false;

        if($result -> num_rows > 0)
        {
            while($row = $result -> fetch_assoc())
            {
                if(($user == $row["user_id"]) && ($password == $row["u_password"]))
                    {
                        $conform_id = $row["user_id"];
                        $conform_name = $row["full_name"];
                        $conform_phone = $row["phone"];
                        $conform_img = $row["user_image"];
                        $found = true;
                        break;
                    }
            }
        }

        if($found == true)
        {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $conform_id;
            $_SESSION["full_name"] = $conform_name;
            $_SESSION["phone"] = $conform_phone;
            $_SESSION["image"] = $conform_img;

            /* ---- WELCOME POPUP TRIGGER (redirect handled by JS after popup) ---- */
            $welcome_name = $conform_name;
            $redirect_url = "home/home.php";
            $show_welcome = true;
            /* ------------------------------------------------------------------- */
        }
        else 
        {
            /* ---- ERROR POPUP TRIGGER (invalid user ID or password) ---- */
            $show_error = true;
            /* ----------------------------------------------------------- */
        }

        $conn->close();
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Login | Secure Access</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="style.css">

</head>
<body>

    <?php if (!empty($show_welcome)): ?>
    <!-- ==========================================
         WELCOME POPUP (shown before redirect)
         ========================================== -->
    <div class="welcome-overlay" id="welcomeOverlay">
        <div class="welcome-box" id="welcomeBox">
            <div class="welcome-glow"></div>

            <div class="welcome-icon">
                <i class="fas fa-shield-halved"></i>
                <span class="welcome-check"><i class="fas fa-check"></i></span>
            </div>

            <div class="welcome-badge">
                <i class="fas fa-circle-check"></i> Login Successful
            </div>

            <h1 class="welcome-title">Welcome</h1>

            <p class="welcome-name"><?php echo htmlspecialchars($welcome_name); ?></p>

            <p class="welcome-msg">
                You have successfully signed in to the
                <strong>Online Police System</strong>.
            </p>

            <div class="welcome-loader">
                <span></span><span></span><span></span>
            </div>

            <p class="welcome-redirect">
                Redirecting you to your dashboard…
            </p>
        </div>
    </div>

    <script>
        window.__WELCOME_REDIRECT__ = <?php echo json_encode($redirect_url); ?>;
    </script>
    <?php endif; ?>

    <?php if (!empty($show_error)): ?>
    <!-- ==========================================
         ERROR POPUP (invalid user ID or password)
         ========================================== -->
    <div class="error-overlay" id="errorOverlay">
        <div class="error-box" id="errorBox">
            <div class="error-glow"></div>

            <div class="error-icon">
                <i class="fas fa-shield-halved"></i>
                <span class="error-cross"><i class="fas fa-xmark"></i></span>
            </div>

            <div class="error-badge">
                <i class="fas fa-circle-xmark"></i> Login Failed
            </div>

            <h1 class="error-title">Access Denied</h1>

            <p class="error-msg">
                The <strong>User ID</strong> or <strong>password</strong> you entered is incorrect.
                Please check your details and try again.
            </p>

            <button type="button" class="error-btn" id="errorCloseBtn">Try Again</button>
        </div>
    </div>
    <?php endif; ?>

    <div class="main-wrapper">

        <!-- ==========================================
             LEFT SIDE — INFORMATION PANEL
             ========================================== -->
        <aside class="info-panel">

            <!-- Login Instructions -->
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-key"></i>
                    Login Instructions
                </div>
                <ul class="info-list">
                    <li><i class="fas fa-circle"></i><span>Enter your <strong>registered User ID</strong> and password.</span></li>
                    <li><i class="fas fa-circle"></i><span>Passwords are <strong>case-sensitive</strong>. Type carefully.</span></li>
                    <li><i class="fas fa-circle"></i><span>Keep your login credentials <strong>strictly confidential</strong>.</span></li>
                    <li><i class="fas fa-circle"></i><span>Never share your password with anyone, including officials.</span></li>
                    <li><i class="fas fa-circle"></i><span>Multiple failed attempts may <strong>temporarily lock</strong> your account.</span></li>
                    <li><i class="fas fa-circle"></i><span>Always <strong>log out</strong> after using a shared device.</span></li>
                </ul>
            </div>

            <!-- About Police Service -->
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-shield-halved"></i>
                    About Police Service
                </div>
                <div class="about-text">
                    The <strong>Online Police System</strong> is a secure digital platform dedicated to serving citizens with
                    <span class="highlight">transparency, efficiency, and integrity</span>. Access your account to file complaints,
                    track FIR status, and access public safety information.
                </div>
                <div class="service-badges">
                    <span><i class="fas fa-check-circle"></i> FIR Filing</span>
                    <span><i class="fas fa-check-circle"></i> Complaint Status</span>
                    <span><i class="fas fa-check-circle"></i> Public Safety</span>
                    <span><i class="fas fa-check-circle"></i> Emergency</span>
                </div>
            </div>

            <!-- General Information -->
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-circle-info"></i>
                    General Information
                </div>
                <ul class="info-list">
                    <li><i class="fas fa-circle"></i><span>Operational <strong>24 hours a day, 7 days a week</strong>.</span></li>
                    <li><i class="fas fa-circle"></i><span>For emergencies, dial <strong>112</strong> or local helpline.</span></li>
                    <li><i class="fas fa-circle"></i><span>Forgot password? Contact your <strong>local station</strong>.</span></li>
                    <li><i class="fas fa-circle"></i><span>Data protected under <strong>IT Act &amp; Privacy Laws</strong>.</span></li>
                </ul>
            </div>
        </aside>

        <!-- ==========================================
             RIGHT SIDE — LOGIN FORM
             ========================================== -->
        <form action="" method="POST">
            <!-- Logo Header -->
            <div class="police-header">
                <div class="police-logo">
                    <div class="shield">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                </div>
                <div class="header-text">
                    <div class="system-name">Online <span>Police</span> System</div>
                    <div class="system-tagline">Secure • Official • Trusted</div>
                </div>
            </div>

            <!-- Form Body -->
            <div class="form-body">
                <div class="User_id">
                    <label>User ID:</label>
                    <input type="text" name="userid" class="userid" placeholder="Enter your user ID">
                </div>
                <div class="user_password">
                    <label>Password</label>
                    <input type="password" name="userpassword" class="userpassword" id="userpassword" placeholder="Enter your password">
                </div>

                <!-- Remember / Forgot -->
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <div class="submit">
                    <input type="submit" value="Login" name="submit" class="submit">
                </div>

                <!-- Register Redirect -->
                <div class="register-redirect">
                    <span>Don't have an account?</span>
                    <a href="register/register.php">Register here</a>
                </div>
            </div>

            <!-- Helpline Banner -->
            <div class="helpline-banner">
                <div class="helpline-icon">
                    <i class="fas fa-phone-volume"></i>
                </div>
                <div class="helpline-text">
                    <span class="label">Police Helpline (General Purpose)</span>
                    <span class="number">100 / 112</span>
                    <span class="note">Available 24×7 — Emergencies &amp; general assistance</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="form-footer">
                <span><i class="fas fa-lock"></i> Official Use Only <i class="fas fa-lock"></i></span>
            </div>
        </form>
    </div>

    <!-- External JavaScript (eye toggle) -->
    <script src="script.js"></script>
</body>
</html>