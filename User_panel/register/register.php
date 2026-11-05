<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST["submit"]))
    {
        $id = $_POST["userid"];
        $name = $_POST["username"];
        $phone = $_POST["userphone"];
        $u_password = $_POST["userpassword"];
        $c_password = $_POST["cpassword"];

        
        $image = $_FILES["userimage"]["name"];
        $temp = $_FILES["userimage"]["tmp_name"];
        $folder = "../uplodes/" . $image;        
        move_uploaded_file($temp,$folder);

        $servername="localhost";
	    $username="root";
	    $password="";
	    $dbName="OPS";
	
	    $conn=new mysqli($servername,$username,$password,$dbName);
	
	    if($conn->connect_error)
	    {
		    die("Connection failed" .$conn->connect_error);
	    }
       
        $sql = "INSERT INTO civilians_data(user_id, full_name, phone, u_password, c_password, user_image) 
        VALUES('$id', '$name', '$phone', '$u_password', '$c_password', '$folder')"; 

        if($conn -> query($sql) === TRUE)
            {
                $popup_type = "success";
                $popup_title = "Registration Successful";
                $popup_message = "User registration successfully";
                $popup_redirect = "../index.php";
            }
        else
            {
                // ===== FAILURE FLAG =====
                $popup_type = "error";
                $popup_title = "Registration Failed";
                $popup_message = "Data could not be inserted. Please try again.";
                $popup_redirect = "";
            }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Registration | Secure Access</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- External CSS -->
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="main-wrapper">

        <!-- ==========================================
             LEFT SIDE — INFORMATION PANEL
             ========================================== -->
        <aside class="info-panel">

            <!-- Registration Instructions -->
            <div class="info-card">
                <div class="card-title">
                    <i class="fas fa-clipboard-list"></i>
                    Registration Instructions
                </div>
                <ul class="info-list">
                    <li><i class="fas fa-circle"></i><span>Provide your <strong>accurate user ID</strong> and personal details.</span></li>
                    <li><i class="fas fa-circle"></i><span>Use a <strong>valid phone number</strong> for OTP verification.</span></li>
                    <li><i class="fas fa-circle"></i><span>Password must be <strong>at least 8 characters</strong> with letters, numbers &amp; symbols.</span></li>
                    <li><i class="fas fa-circle"></i><span>Confirm your password carefully — it cannot be changed immediately.</span></li>
                    <li><i class="fas fa-circle"></i><span>All fields are <strong>mandatory</strong>. False information is punishable by law.</span></li>
                    <li><i class="fas fa-circle"></i><span>Keep your credentials <strong>confidential</strong>.</span></li>
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
                    <span class="highlight">transparency, efficiency, and integrity</span>. Our mission is to provide
                    accessible law enforcement services, complaint registration, and public safety information
                    at your fingertips.
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
                    <li><i class="fas fa-circle"></i><span>Registration is <strong>free of cost</strong>. Beware of fraud.</span></li>
                    <li><i class="fas fa-circle"></i><span>Data protected under <strong>IT Act &amp; Privacy Laws</strong>.</span></li>
                </ul>
            </div>
        </aside>

        <!-- ==========================================
             RIGHT SIDE — REGISTRATION FORM
             ========================================== -->
        <form action="" method="POST" enctype="multipart/form-data">
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
                <div class="user_name">
                    <label>Name:</label>
                    <input type="text" name="username" class="username" placeholder="Enter your full name">
                </div>
                <div class="user_phone">
                    <label>Phone:</label>
                    <input type="text" name="userphone" class="userphone" placeholder="Enter your phone number">
                </div>
                <div class="user_password">
                    <label>Password</label>
                    <input type="password" name="userpassword" class="userpassword" id="userpassword" placeholder="Create a strong password">
                </div>
                <div class="conformpassword">
                    <label>Confirm Password</label>
                    <input type="password" name="cpassword" class="cpassword" id="cpassword" placeholder="Re-enter your password">
                </div>
                <div class="user_image">
                    <label>Profile Image:</label>
                    <div class="image-upload-wrapper">
                        <input type="file" name="userimage" class="userimage" id="userimage" accept="image/*">
                        <label for="userimage" class="image-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span class="upload-text">Choose an image</span>
                        </label>
                    </div>
                    <div class="image-preview" id="imagePreview">
                        <img src="" alt="Preview" id="previewImg">
                    </div>
                </div>
                <div class="submit">
                    <input type="submit" value="submit" name="submit" class="submit">
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

    <?php if (isset($popup_type)): ?>
    <!-- ==========================================
         PREMIUM SUCCESS POP-UP MODAL
         ========================================== -->
    <div class="popup-overlay" id="popupOverlay">
        <div class="popup-box <?php echo $popup_type; ?>" id="popupBox">
            <div class="popup-icon-wrap">
                <div class="popup-icon-ring"></div>
                <div class="popup-icon-ring-2"></div>
                <div class="popup-icon">
                    <?php if ($popup_type === "success"): ?>
                        <i class="fas fa-check"></i>
                    <?php else: ?>
                        <i class="fas fa-times"></i>
                    <?php endif; ?>
                </div>
            </div>
            <div class="popup-title"><?php echo $popup_title; ?></div>
            <div class="popup-message"><?php echo $popup_message; ?></div>
            <button class="popup-btn" id="popupBtn">
                <?php echo ($popup_type === "success") ? "Go to Login" : "Try Again"; ?>
            </button>
            <div class="popup-progress"></div>
        </div>
    </div>

    <script>
        (function () {
            const overlay   = document.getElementById('popupOverlay');
            const popupBox  = document.getElementById('popupBox');
            const popupBtn  = document.getElementById('popupBtn');
            const redirectUrl = "<?php echo $popup_redirect; ?>";
            const isSuccess = "<?php echo $popup_type; ?>" === "success";

            // ---------- Confetti (success only) ----------
            function fireConfetti() {
                const colors = ['#2ecc71', '#7bed9f', '#c9a227', '#e6c34b', '#3498db', '#f39c12'];
                for (let i = 0; i < 60; i++) {
                    const piece = document.createElement('div');
                    piece.className = 'confetti';
                    piece.style.left = Math.random() * 100 + '%';
                    piece.style.background = colors[Math.floor(Math.random() * colors.length)];
                    piece.style.width  = (6 + Math.random() * 8) + 'px';
                    piece.style.height = (6 + Math.random() * 8) + 'px';
                    piece.style.borderRadius = Math.random() > 0.5 ? '50%' : '2px';
                    piece.style.animationDelay = (Math.random() * 0.8) + 's';
                    piece.style.animationDuration = (2 + Math.random() * 1.5) + 's';
                    popupBox.appendChild(piece);
                    setTimeout(() => piece.remove(), 3500);
                }
            }

            // ---------- Show popup ----------
            setTimeout(() => {
                overlay.classList.add('show');
                if (isSuccess) {
                    setTimeout(fireConfetti, 400);
                }
            }, 100);

            // ---------- Close on button click ----------
            popupBtn.addEventListener('click', function () {
                overlay.classList.remove('show');
                overlay.classList.add('hide');
                if (isSuccess && redirectUrl) {
                    setTimeout(() => {
                        window.location.href = redirectUrl;
                    }, 400);
                }
            });

            // ---------- Auto-redirect after 3.5s on success ----------
            if (isSuccess && redirectUrl) {
                setTimeout(() => {
                    window.location.href = redirectUrl;
                }, 3500);
            }
        })();
    </script>
    <?php endif; ?>

    <!-- External JavaScript -->
    <script src="register.js"></script>
</body>
</html>