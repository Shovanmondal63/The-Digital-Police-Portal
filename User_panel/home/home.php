<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

/* ---- Only logged-in users can see this page ---- */
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

/* ---- Logged-in user data ---- */
$user_name  = $_SESSION["full_name"] ?? "My Account";
$user_phone = $_SESSION["phone"] ?? "";
$user_image = $_SESSION["image"] ?? "";

$image_folder = "../uploads/";

$image_src = "";
if (!empty($user_image)) {
    $image_src = (preg_match('#^(https?:)?//#', $user_image))
        ? $user_image
        : $image_folder . $user_image;
}
?>

<?php
if (isset($_POST["submit"])) {

    $full_name = $_POST["full_name"];
    $contact_number = $_POST["contact_number"];
    $email = $_POST["email"];
    $id_type = $_POST["id_type"];
    $id_number = $_POST["id_number"];
    $relation = $_POST["relation"];
    $present_address = $_POST["present_address"];

    $district = $_POST["district"];
    $police_station = $_POST["police_station"];
    $report_category = $_POST["report_category"];

    $incident_datetime = $_POST["incident_datetime"];
    $incident_location = $_POST["incident_location"];

    $subject_title = $_POST["subject_title"];
    $detailed_description = $_POST["detailed_description"];

    $declaration = isset($_POST["declaration"]) ? "Accepted" : "Not Accepted";
    $c_id = $_SESSION["user_id"];

    $servername = "localhost";
    $username = "root";
    $db_password = "";
    $db_name = "OPS";

    $conn = new mysqli($servername, $username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed !!!" . $conn->connect_error);
    }

    // FIXED: Using Prepared Statements to prevent SQL Syntax errors and Injection
    $sql = "INSERT INTO dairy_data
    (
        Full_Name,
        Contact_Number,
        Email_Address,
        Identification_Type,
        ID_Number,
        Relationship_Incident,
        Present_Address,
        District,
        Police_Station,
        Type_Report,
        Date_Time,
        Place,
        Subject,
        Description,
        civilians_id
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters ("s" for strings, "i" for integers)
    $stmt->bind_param(
        "ssssssssssssssi",
        $full_name, 
        $contact_number, 
        $email, 
        $id_type, 
        $id_number, 
        $relation, 
        $present_address, 
        $district, 
        $police_station, 
        $report_category, 
        $incident_datetime, 
        $incident_location, 
        $subject_title, 
        $detailed_description, 
        $c_id
    );

    if ($stmt->execute()) {
        echo '<script>window.__OPS_POPUP__ = { type: "success", title: "Entry Submitted", message: "Your General Diary entry has been filed successfully." };</script>';
    } else {
        echo '<script>window.__OPS_POPUP__ = { type: "error", title: "Submission Failed", message: "We could not save your entry. Please try again." };</script>';
    }

    $stmt->close();
    $conn->close();
}
?>


<?php

    /* ---- Status check: run query, store rows for later rendering ---- */
    $status_checked = false;
    $status_rows    = [];

    
        $status_checked = true;

        $servername  = "localhost";
        $username    = "root";
        $db_password = "";
        $db_name     = "OPS";

        $conn = new mysqli($servername, $username, $db_password, $db_name);

        if ($conn->connect_error)
        {
            die("Connection failed !!!" . $conn->connect_error);
        }

        // FIXED: Added 'regecter_reason' to the SELECT query so we don't have to query it later
        $sql = "SELECT Full_Name,ID_Number,Police_Station,Place,accept,civilians_id,regecter_reason FROM dairy_data";

        $result = $conn->query($sql);

        if ($result->num_rows > 0)
        {
            while ($row = $result->fetch_assoc())
            {
                if($row["civilians_id"] == $_SESSION["user_id"])
                    {
                        $status_rows[] = $row;

                    }
            }
        }

        $conn->close();
    
?>

<?php

    if(isset($_POST["Withdraw"]))
        {
            $servername  = "localhost";
            $username    = "root";
            $db_password = "";
            $db_name     = "OPS";

            $conn = new mysqli($servername, $username, $db_password, $db_name);

            if ($conn->connect_error)
            {
                die("Connection failed !!!" . $conn->connect_error);
            }

            $id = $_SESSION["id_num"] ?? ''; // Ensure ID exists

            if (!empty($id)) {
                // FIXED: Using Prepared Statements for DELETE
                $sql = "DELETE FROM dairy_data WHERE ID_Number = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $id); // ID_Number is bigint in DB, but often stored as string in session

                if($stmt->execute())
                    {
                        echo "<script>alert('Record deleted Successfully...'); window.location.href='home.php';</script>";
                    }
                else
                    {
                        echo "Error:".$stmt->error;
                    }
                $stmt->close();
            }
            $conn->close();
        }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Police Service</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="home.css">
</head>
<body>

    <header class="navbar" id="navbar">
        <div class="navbar-inner">

            <a href="#home" class="brand">
                <span class="brand-logo"><i class="fas fa-shield-halved"></i></span>
                <span class="brand-text">
                    <span class="brand-name">Online <span>Police</span> Service</span>
                    <span class="brand-tag">Secure • Official • Trusted</span>
                </span>
            </a>

            <nav class="nav-menu" id="navMenu" aria-label="Main navigation">
                <a href="#home" class="nav-link active"><i class="fas fa-house"></i> Home</a>
                <a href="#diary" class="nav-link"><i class="fas fa-book"></i> Diary</a>
                <a href="#status" class="nav-link"><i class="fas fa-clipboard-check"></i> Status</a>
                <a href="#rules" class="nav-link"><i class="fas fa-scale-balanced"></i> Rules</a>
                <a href="#help" class="nav-link"><i class="fas fa-circle-question"></i> Help</a>
            </nav>

            <div class="nav-right">

                <div class="profile" id="profile">
                    <button type="button" class="round_profile_icon" id="profileBtn"
                            aria-label="Open profile menu" aria-haspopup="true" aria-expanded="false">
                        <?php if ($image_src): ?>
                            <img src="<?php echo htmlspecialchars($image_src); ?>" alt="Profile photo">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                    </button>

                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-head">
                            <div class="profile-avatar">
                                <?php if ($image_src): ?>
                                    <img src="<?php echo htmlspecialchars($image_src); ?>" alt="Profile photo">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <div class="profile-info">
                                <div class="profile-name"><?php echo htmlspecialchars($user_name); ?></div>
                                <?php if ($user_phone !== ""): ?>
                                <div class="profile-phone">
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo htmlspecialchars($user_phone); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a href="#"><i class="fas fa-id-card"></i> My Profile</a>
                        <a href="#"><i class="fas fa-gear"></i> Settings</a>
                        <a href="../index.php" class="logout"><i class="fas fa-right-from-bracket"></i> Logout</a>
                    </div>
                </div>

                <button type="button" class="hamburger" id="hamburger"
                        aria-label="Toggle menu" aria-expanded="false" aria-controls="navMenu">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </div>
    </header>

    <main class="main_content">

        <!-- ============================================
             HOME
             ============================================ -->
        <div class="home_part" id="home">

            <section class="hero">
                <i class="fas fa-shield-halved hero-bg-icon"></i>
                <div class="container hero-inner">
                    <div>
                        <div class="hero-badge"><i class="fas fa-star"></i> Serving &amp; Protecting India</div>
                        <h1>The <span>Indian Police</span> System</h1>
                        <p>
                            Welcome, <strong><?php echo htmlspecialchars($user_name); ?></strong>.
                            India's police force is one of the largest in the world, working every day to
                            maintain law and order, prevent crime and protect citizens. Explore how it is
                            organised, how it grew over the centuries, and file your General Diary entry online.
                        </p>
                        <div class="hero-btns">
                            <a href="#diary" class="btn btn-gold"><i class="fas fa-pen-to-square"></i> File a Diary Entry</a>
                            <a href="#rules" class="btn btn-ghost"><i class="fas fa-scale-balanced"></i> Read the Rules</a>
                        </div>
                    </div>

                    <div class="hero-card">
                        <h3>Quick Facts</h3>
                        <ul>
                            <li><i class="fas fa-circle-check"></i> Police is a <strong>State subject</strong> under the Constitution.</li>
                            <li><i class="fas fa-circle-check"></i> Each State police is headed by a <strong>Director General of Police (DGP)</strong>.</li>
                            <li><i class="fas fa-circle-check"></i> Central forces work under the <strong>Ministry of Home Affairs</strong>.</li>
                            <li><i class="fas fa-circle-check"></i> Emergency number for all India: <strong>112</strong>.</li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="stats">
                <div class="container stats-grid">
                    <div class="stat reveal"><strong>1861</strong><span>Police Act enacted</span></div>
                    <div class="stat reveal"><strong>1948</strong><span>Indian Police Service formed</span></div>
                    <div class="stat reveal"><strong>112</strong><span>Single emergency number</span></div>
                    <div class="stat reveal"><strong>2024</strong><span>New criminal laws in force</span></div>
                </div>
            </section>

            <section class="block">
                <div class="container">
                    <div class="section-head reveal">
                        <span class="section-eyebrow">Role &amp; Duties</span>
                        <h2 class="section-title">What Does the Police Do?</h2>
                        <p class="section-sub">The police are the first point of contact between a citizen and the justice system.</p>
                    </div>
                    <div class="duty-grid">
                        <div class="duty-card reveal">
                            <div class="duty-icon"><i class="fas fa-scale-balanced"></i></div>
                            <h4>Maintain Law &amp; Order</h4>
                            <p>Keep public peace, manage crowds, and respond quickly to disturbances.</p>
                        </div>
                        <div class="duty-card reveal">
                            <div class="duty-icon"><i class="fas fa-magnifying-glass"></i></div>
                            <h4>Prevent &amp; Detect Crime</h4>
                            <p>Patrol, collect intelligence, investigate offences and bring offenders to court.</p>
                        </div>
                        <div class="duty-card reveal">
                            <div class="duty-icon"><i class="fas fa-user-shield"></i></div>
                            <h4>Protect Life &amp; Property</h4>
                            <p>Safeguard citizens, with special care for women, children and senior citizens.</p>
                        </div>
                        <div class="duty-card reveal">
                            <div class="duty-icon"><i class="fas fa-handshake-angle"></i></div>
                            <h4>Serve the Public</h4>
                            <p>Register complaints, verify documents and guide people in times of need.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="block">
                <div class="container">
                    <div class="section-head reveal">
                        <span class="section-eyebrow">Structure</span>
                        <h2 class="section-title">How Is the Police Organised?</h2>
                        <p class="section-sub">Policing is shared between the States and the Union Government.</p>
                    </div>
                    <div class="structure">
                        <div class="panel reveal">
                            <h3><i class="fas fa-sitemap"></i> State &amp; Central Forces</h3>
                            <p>
                                Since "Police" is a State subject, every State and Union Territory has its own police force.
                                In big cities a <strong>Commissioner of Police</strong> leads the Commissionerate system, while
                                districts are led by a <strong>Superintendent of Police (SP)</strong>.
                            </p>
                            <p>The Union Government runs the Central Armed Police Forces and national agencies:</p>
                            <div class="tags">
                                <span>BSF</span><span>CRPF</span><span>CISF</span><span>ITBP</span>
                                <span>SSB</span><span>Assam Rifles</span><span>CBI</span><span>NIA</span><span>NCRB</span>
                            </div>
                        </div>

                        <div class="panel reveal">
                            <h3><i class="fas fa-medal"></i> Rank Ladder (State Police)</h3>
                            <ul class="ranks">
                                <li>Director General of Police <small>DGP</small></li>
                                <li>Inspector General <small>IG</small></li>
                                <li>Deputy Inspector General <small>DIG</small></li>
                                <li>Superintendent of Police <small>SP</small></li>
                                <li>Deputy SP / Inspector <small>DSP</small></li>
                                <li>Sub-Inspector / Constable <small>SI / PC</small></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <section class="block" id="history">
                <div class="container">
                    <div class="section-head reveal">
                        <span class="section-eyebrow">History</span>
                        <h2 class="section-title">A Journey Through Time</h2>
                        <p class="section-sub">From ancient town watchmen to a modern, technology-driven police service.</p>
                    </div>

                    <div class="timeline">
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">Ancient &amp; Medieval</span>
                                <h4>Early Systems of Order</h4>
                                <p>Ancient texts such as the Arthashastra describe officials and spies who kept order. Under the Mughals, the <em>Kotwal</em> was responsible for the security of a city.</p>
                            </div>
                        </div>
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">1792</span>
                                <h4>The Thana System</h4>
                                <p>Lord Cornwallis introduced police stations (<em>thanas</em>) headed by a <em>daroga</em>, laying the base of the modern police station.</p>
                            </div>
                        </div>
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">1861</span>
                                <h4>The Police Act</h4>
                                <p>After the 1857 uprising, the Police Act of 1861 created an organised civil police, modelled on the Irish Constabulary. Its structure still influences policing today.</p>
                            </div>
                        </div>
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">1902–03</span>
                                <h4>Fraser Police Commission</h4>
                                <p>The commission recommended provincial CID branches, better training and more Indians in higher police posts.</p>
                            </div>
                        </div>
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">1948</span>
                                <h4>Indian Police Service (IPS)</h4>
                                <p>After Independence, the IPS was formed as an All-India Service. Officers are trained at the Sardar Vallabhbhai Patel National Police Academy, Hyderabad.</p>
                            </div>
                        </div>
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">1963–1986</span>
                                <h4>National Institutions</h4>
                                <p>The CBI was set up in 1963, the Bureau of Police Research &amp; Development in 1970, and the National Crime Records Bureau in 1986.</p>
                            </div>
                        </div>
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">2006</span>
                                <h4>Police Reforms Directions</h4>
                                <p>In the Prakash Singh case, the Supreme Court issued directions to make police more accountable and independent of improper pressure.</p>
                            </div>
                        </div>
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">2019</span>
                                <h4>Emergency Response: 112</h4>
                                <p>The Emergency Response Support System began bringing police, fire and ambulance help under the single number 112.</p>
                            </div>
                        </div>
                        <div class="tl-item reveal">
                            <div class="tl-card">
                                <span class="tl-year">2024</span>
                                <h4>New Criminal Laws</h4>
                                <p>From 1 July 2024, the Bharatiya Nyaya Sanhita, Bharatiya Nagarik Suraksha Sanhita and Bharatiya Sakshya Adhiniyam replaced the IPC, CrPC and Evidence Act.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="block">
                <div class="container">
                    <div class="section-head reveal">
                        <span class="section-eyebrow">Helplines</span>
                        <h2 class="section-title">Important Numbers</h2>
                    </div>
                    <div class="help-grid">
                        <div class="help-card reveal"><i class="fas fa-phone-volume"></i><strong>112</strong><span>All emergencies</span></div>
                        <div class="help-card reveal"><i class="fas fa-person-dress"></i><strong>1091</strong><span>Women helpline</span></div>
                        <div class="help-card reveal"><i class="fas fa-child"></i><strong>1098</strong><span>Child helpline</span></div>
                        <div class="help-card reveal"><i class="fas fa-laptop-code"></i><strong>1930</strong><span>Cyber crime</span></div>
                    </div>
                </div>
            </section>
        </div>

        <!-- ============================================
             DIARY
             ============================================ -->
        <div class="diary_part" id="diary">
            <div class="container">
                <div class="section-head reveal">
                    <span class="section-eyebrow">General Diary</span>
                    <h2 class="section-title">File an Online Diary Entry</h2>
                    <p class="section-sub">Fill in the details below. Fields marked with <strong style="color:#e74c3c">*</strong> are required.</p>
                </div>

                <form action="" method="POST" class="diary-form">

                    <fieldset class="form-card reveal">
                        <legend><span class="step-num">1</span> Reporter / Applicant Details</legend>
                        <div class="form-grid">
                            <div class="field">
                                <label>Full Name <em>*</em></label>
                                <input type="text" name="full_name" required>
                            </div>
                            <div class="field">
                                <label>Contact Number <em>*</em></label>
                                <input type="tel" name="contact_number" required>
                            </div>
                            <div class="field">
                                <label>Email Address</label>
                                <input type="email" name="email" placeholder="name@example.com">
                            </div>
                            <div class="field">
                                <label>Identification Type <em>*</em></label>
                                <select name="id_type" required>
                                    <option value="">Select ID Type</option>
                                    <option value="Aadhaar">Aadhaar</option>
                                    <option value="Passport">Passport</option>
                                    <option value="Driving License">Driving License</option>
                                    <option value="Voter ID">Voter ID</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>ID Number <em>*</em></label>
                                <input type="text" name="id_number" required>
                            </div>
                            <div class="field">
                                <label>Relationship to Incident <em>*</em></label>
                                <select name="relation" required>
                                    <option value="Self">Self</option>
                                    <option value="Relative">Relative</option>
                                    <option value="Bystander">Bystander</option>
                                    <option value="Representative">Representative</option>
                                </select>
                            </div>
                            <div class="field full">
                                <label>Present Address <em>*</em></label>
                                <textarea name="present_address" rows="2" required></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-card reveal">
                        <legend><span class="step-num">2</span> Police Jurisdiction &amp; Category</legend>
                        <div class="form-grid">
                            <div class="field">
                                <label>District <em>*</em></label>
                                <select name="district" required>
                                    <option value="">Select District</option>
                                    <option value="District A">District A</option>
                                    <option value="District B">District B</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Police Station <em>*</em></label>
                                <select name="police_station" required>
                                    <option value="">Select Police Station</option>
                                    <option value="Station 1">Station 1</option>
                                    <option value="Station 2">Station 2</option>
                                </select>
                            </div>
                            <div class="field full">
                                <label>Type of Report / Category <em>*</em></label>
                                <select name="report_category" required>
                                    <option value="">Select Category</option>
                                    <option value="Lost Item">Lost/Found Item</option>
                                    <option value="Non-Cognizable">Non-Cognizable Complaint</option>
                                    <option value="Identity Misuse">Identity / Document Misuse</option>
                                    <option value="Tenant Verification">Tenant / Maid Verification</option>
                                    <option value="Cyber Harassment">Cyber Harassment / Minor Cyber Incident</option>
                                    <option value="General Intimation">General Intimation / Security Precaution</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-card reveal">
                        <legend><span class="step-num">3</span> Incident Location &amp; Time</legend>
                        <div class="form-grid">
                            <div class="field">
                                <label>Date &amp; Time of Incident <em>*</em></label>
                                <input type="datetime-local" name="incident_datetime" required>
                            </div>
                            <div class="field">
                                <label>Place / Location of Occurrence <em>*</em></label>
                                <input type="text" name="incident_location" placeholder="Street, Landmark, Area" required>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-card reveal">
                        <legend><span class="step-num">4</span> Incident Details &amp; Content</legend>
                        <div class="form-grid">
                            <div class="field full">
                                <label>Subject / Brief Description Title <em>*</em></label>
                                <input type="text" name="subject_title" placeholder="e.g., Loss of Original College Degree Certificate" required>
                            </div>
                            <div class="field full">
                                <label>Detailed Description of Event <em>*</em></label>
                                <textarea name="detailed_description" rows="4" placeholder="Provide complete sequence of events..." required></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-card reveal">
                        <legend><span class="step-num">5</span> Verification &amp; Legal Declaration</legend>
                        <div class="declare">
                            <input id="declaration" name="declaration" type="checkbox" required>
                            <label for="declaration">
                                I hereby declare that the information provided above is true to the best of my knowledge and belief.
                                I understand that filing a false report is a punishable offense under the law.
                            </label>
                        </div>
                        <button type="submit" class="submit-btn" name="submit">
                            <i class="fas fa-paper-plane"></i> Submit General Diary Entry
                        </button>
                    </fieldset>

                </form>
            </div>
        </div>

        <!-- ============================================
             STATUS
             ============================================ -->
        <div class="status_bar" id="status">
            <div class="container">
                <div class="section-head reveal">
                    <span class="section-eyebrow">Track Record</span>
                    <h2 class="section-title">Check Your Diary Status</h2>
                    <p class="section-sub">Press the button below to view all diary entries stored in the system.</p>
                </div>

                <form action="" method="POST" class="status-form">
                    <button type="submit" name="cheack" class="status-btn">
                        <i class="fas fa-magnifying-glass"></i>
                        Check Diary Status
                    </button>
                </form>

                <?php if ($status_checked): ?>

                    <?php if (!empty($status_rows)): ?>

                        <div class="status-results">
                            <?php foreach ($status_rows as $row): ?>

                                <?php
                                    /* ---- Determine status from 'accept' column ---- */
                                    $acc = $row["accept"] ?? null;

                                    if ($acc === null || $acc === "") {
                                        $status_key   = "pending";
                                        $status_label = "Pending";
                                        $status_icon  = "fa-hourglass-half";
                                    } elseif (strtolower(trim($acc)) === "accept") {
                                        $status_key   = "accepted";
                                        $status_label = "Accepted";
                                        $status_icon  = "fa-circle-check";
                                    } elseif (strtolower(trim($acc)) === "reject" || strtolower(trim($acc)) === "regect" || strtolower(trim($acc)) === "rejected") {
                                        $status_key   = "rejected";
                                        $status_label = "Rejected";
                                        $status_icon  = "fa-circle-xmark";
                                        
                                    } else {
                                        $status_key   = "pending";
                                        $status_label = "Pending";
                                        $status_icon  = "fa-hourglass-half";
                                    }
                                ?>

                                <article class="status-card status-card--<?php echo $status_key; ?>">
                                    <div class="status-card-head">
                                        <span class="status-badge">
                                            <i class="fas fa-folder-open"></i> Diary Record
                                        </span>
                                        <span class="status-pill status-pill--<?php echo $status_key; ?>">
                                            <i class="fas <?php echo $status_icon; ?>"></i>
                                            <?php echo $status_label; ?>
                                        </span>
                                    </div>
                                    <dl class="status-list">
                                        <div class="status-row">
                                            <dt><i class="fas fa-user"></i> Full Name</dt>
                                            <dd><?php echo htmlspecialchars($row["Full_Name"]); ?></dd>
                                        </div>
                                        <div class="status-row">
                                            <dt><i class="fas fa-id-card"></i> ID Number</dt>
                                            <dd><?php echo htmlspecialchars($row["ID_Number"]); 
                                                $_SESSION["id_num"] = $row["ID_Number"] ;?></dd>
                                        </div>
                                        <div class="status-row">
                                            <dt><i class="fas fa-shield-halved"></i> Police Station</dt>
                                            <dd><?php echo htmlspecialchars($row["Police_Station"]); ?></dd>
                                        </div>
                                        <div class="status-row">
                                            <dt><i class="fas fa-location-dot"></i> Place</dt>
                                            <dd><?php echo htmlspecialchars($row["Place"]); ?></dd>
                                        </div>

                                        <?php
                                        // FIXED: Displaying the rejection reason safely using the data already fetched
                                        if($status_label == "Rejected" && !empty($row["regecter_reason"]))
                                            {
                                                ?>
                                                <div class="status-row" style="background: #ffebee; padding: 10px; border-radius: 5px; margin-top: 10px; border-left: 4px solid #f44336;">
                                                    <dt style="color: #c62828;"><i class="fas fa-circle-exclamation"></i> Rejection Reason</dt>
                                                    <dd style="color: #c62828;"><?php echo htmlspecialchars($row["regecter_reason"]); ?></dd>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                    </dl>
                                    
                                    <form action="" method="POST" class="withdraw-form">
                                        <button type="submit" name="Withdraw" class="withdraw-btn">
                                            <i class="fas fa-trash-can"></i>
                                                Withdraw
                                        </button>
                                    </form>
                                </article>
                            <?php endforeach; ?>
                        </div>

                    <?php else: ?>

                        <div class="status-empty">
                            <i class="fas fa-inbox"></i>
                            <p>No diary records found.</p>
                        </div>

                    <?php endif; ?>

                <?php endif; ?>
            </div>
        </div>

        <!-- ============================================
             RULES & REGULATIONS  (now after Status)
             ============================================ -->
        <div class="rules_part" id="rules">
            <div class="container">
                <div class="section-head reveal">
                    <span class="section-eyebrow">Legal Framework</span>
                    <h2 class="section-title">Rules, Acts &amp; Regulations</h2>
                    <p class="section-sub">The Indian Police operates under a robust legal framework — from constitutional provisions to the new criminal codes of 2024.</p>
                </div>

                <div class="rules-block reveal">
                    <h3 class="rules-heading"><i class="fas fa-scroll"></i> Constitutional Provisions</h3>
                    <div class="rules-grid">
                        <article class="rule-card">
                            <span class="rule-tag">Article 246</span>
                            <h4>Police as a State Subject</h4>
                            <p>Entry 1 and 2 of List II (State List) of the Seventh Schedule place "Police" and "Public Order" under State legislative authority. This is why each State has its own police force.</p>
                        </article>
                        <article class="rule-card">
                            <span class="rule-tag">Article 355</span>
                            <h4>Union's Duty to Protect States</h4>
                            <p>It is the duty of the Union to protect every State against external aggression and internal disturbance, and to ensure that State governance is carried on in accordance with the Constitution.</p>
                        </article>
                        <article class="rule-card">
                            <span class="rule-tag">Article 21</span>
                            <h4>Right to Life &amp; Liberty</h4>
                            <p>Police action must respect the fundamental right to life and personal liberty. Arbitrary arrest or detention violates Article 21 and Article 22.</p>
                        </article>
                        <article class="rule-card">
                            <span class="rule-tag">Article 22</span>
                            <h4>Protection Against Arrest</h4>
                            <p>An arrested person must be informed of the grounds of arrest, allowed to consult a lawyer, and produced before a magistrate within 24 hours.</p>
                        </article>
                    </div>
                </div>

                <div class="rules-block reveal">
                    <h3 class="rules-heading"><i class="fas fa-book-open"></i> Core Police Legislation</h3>
                    <div class="rules-grid">
                        <article class="rule-card">
                            <span class="rule-tag">Act No. V of 1861</span>
                            <h4>The Police Act, 1861</h4>
                            <p>The foundational law that organised the Indian police into a civil force. Still in force in many States, it defines police duties, ranks, and discipline.</p>
                        </article>
                        <article class="rule-card">
                            <span class="rule-tag">Act No. 47 of 1963</span>
                            <h4>Police Forces (Restriction of Rights) Act</h4>
                            <p>Restricts members of the police force from joining associations or engaging in political activity that could compromise discipline.</p>
                        </article>
                        <article class="rule-card">
                            <span class="rule-tag">Act No. 22 of 2005</span>
                            <h4>Private Security Agencies Regulation Act</h4>
                            <p>Regulates private security agencies, ensuring coordination with local police and verification of personnel.</p>
                        </article>
                        <article class="rule-card">
                            <span class="rule-tag">State Police Acts</span>
                            <h4>State-Level Police Laws</h4>
                            <p>Each State (e.g., Maharashtra Police Act 1951, Kerala Police Act 2011, Karnataka Police Act 1963) has its own legislation governing policing.</p>
                        </article>
                    </div>
                </div>

                <div class="rules-block reveal">
                    <h3 class="rules-heading"><i class="fas fa-gavel"></i> New Criminal Laws (From 1 July 2024)</h3>
                    <div class="rules-grid">
                        <article class="rule-card rule-card--highlight">
                            <span class="rule-tag">BNS, 2023</span>
                            <h4>Bharatiya Nyaya Sanhita</h4>
                            <p>Replaces the Indian Penal Code (IPC), 1860. Defines offences and punishments including new provisions for organised crime, terrorism and mob lynching.</p>
                        </article>
                        <article class="rule-card rule-card--highlight">
                            <span class="rule-tag">BNSS, 2023</span>
                            <h4>Bharatiya Nagarik Suraksha Sanhita</h4>
                            <p>Replaces the Code of Criminal Procedure (CrPC), 1973. Governs arrest, bail, FIR registration, trial procedure and investigation timelines.</p>
                        </article>
                        <article class="rule-card rule-card--highlight">
                            <span class="rule-tag">BSA, 2023</span>
                            <h4>Bharatiya Sakshya Adhiniyam</h4>
                            <p>Replaces the Indian Evidence Act, 1872. Modernises rules of evidence, including electronic records and digital signatures.</p>
                        </article>
                        <article class="rule-card rule-card--highlight">
                            <span class="rule-tag">Zero FIR</span>
                            <h4>Mandatory FIR Rules</h4>
                            <p>Under BNSS §173, an FIR must be registered for any cognizable offence, even if the incident occurred outside the police station's jurisdiction (Zero FIR).</p>
                        </article>
                    </div>
                </div>

                <div class="rules-block reveal">
                    <h3 class="rules-heading"><i class="fas fa-hand-holding-heart"></i> Citizens' Rights &amp; Duties</h3>
                    <div class="two-col">
                        <div class="col-card">
                            <h4><i class="fas fa-check-circle"></i> Your Rights</h4>
                            <ul class="rule-list">
                                <li>Right to free FIR registration for cognizable offences</li>
                                <li>Right to a copy of the FIR free of cost</li>
                                <li>Right to know the grounds of arrest</li>
                                <li>Right to be produced before a magistrate within 24 hours</li>
                                <li>Right to legal counsel and free legal aid (Article 39A)</li>
                                <li>Right to medical examination and safety in custody</li>
                                <li>Women cannot be arrested after sunset and before sunrise (except with special permission)</li>
                                <li>Right against self-incrimination (Article 20(3))</li>
                            </ul>
                        </div>
                        <div class="col-card">
                            <h4><i class="fas fa-exclamation-circle"></i> Your Duties</h4>
                            <ul class="rule-list">
                                <li>Report cognizable offences to the police without delay</li>
                                <li>Cooperate with investigations and provide truthful information</li>
                                <li>Do not obstruct police in discharge of their duties</li>
                                <li>Filing a false complaint is punishable under BNS §217</li>
                                <li>Respect the rights of fellow citizens and public property</li>
                                <li>Follow lawful orders during public assemblies and emergencies</li>
                                <li>Provide assistance in preventing crime when legally required</li>
                                <li>Keep valid identification documents on person</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================
             HELP & SUPPORT  (now after Rules)
             ============================================ -->
        <div class="help_part" id="help">
            <div class="container">
                <div class="section-head reveal">
                    <span class="section-eyebrow">Support Center</span>
                    <h2 class="section-title">Help &amp; Contact</h2>
                    <p class="section-sub">Need assistance? Reach out through our helplines, emails, or visit a police station near you.</p>
                </div>

                <div class="contact-grid">
                    <article class="contact-card reveal">
                        <div class="contact-icon"><i class="fas fa-phone-volume"></i></div>
                        <h4>Emergency Helpline</h4>
                        <p class="contact-number">112</p>
                        <p class="contact-note">24×7 All-India Emergency Response</p>
                    </article>

                    <article class="contact-card reveal">
                        <div class="contact-icon"><i class="fas fa-person-dress"></i></div>
                        <h4>Women Helpline</h4>
                        <p class="contact-number">1091 / 181</p>
                        <p class="contact-note">For women in distress · 24×7</p>
                    </article>

                    <article class="contact-card reveal">
                        <div class="contact-icon"><i class="fas fa-child"></i></div>
                        <h4>Child Helpline</h4>
                        <p class="contact-number">1098</p>
                        <p class="contact-note">For children in need of care</p>
                    </article>

                    <article class="contact-card reveal">
                        <div class="contact-icon"><i class="fas fa-laptop-code"></i></div>
                        <h4>Cyber Crime</h4>
                        <p class="contact-number">1930</p>
                        <p class="contact-note">Report online fraud &amp; cyber crime</p>
                    </article>

                    <article class="contact-card reveal">
                        <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                        <h4>Email Support</h4>
                        <p class="contact-email">support@onlinepoliceservice.in</p>
                        <p class="contact-note">Response within 48 hours</p>
                    </article>

                    <article class="contact-card reveal">
                        <div class="contact-icon"><i class="fas fa-location-dot"></i></div>
                        <h4>Head Office</h4>
                        <p class="contact-address">Ministry of Home Affairs<br>North Block, New Delhi – 110001</p>
                        <p class="contact-note">Mon – Fri · 9:00 AM – 5:30 PM</p>
                    </article>
                </div>

                <div class="faq-wrap reveal">
                    <h3 class="rules-heading" style="text-align:center;"><i class="fas fa-circle-question"></i> Frequently Asked Questions</h3>
                    <div class="faq-list">

                        <details class="faq-item">
                            <summary>How do I file an FIR online?</summary>
                            <p>Many State police portals offer e-FIR for vehicle theft, lost documents and non-cognizable matters. For serious offences, visit your nearest police station or use the 112 app. Under BNSS §173, every cognizable offence must be registered.</p>
                        </details>

                        <details class="faq-item">
                            <summary>Can a police officer refuse to register my FIR?</summary>
                            <p>No. If the officer refuses, you may: (1) send a written complaint to the Superintendent of Police under BNSS §173(4), (2) file a complaint before the Judicial Magistrate under BNSS §175(3), or (3) use the State police online grievance portal.</p>
                        </details>

                        <details class="faq-item">
                            <summary>Is there a time limit to file an FIR?</summary>
                            <p>There is no strict time limit for most cognizable offences; however, earlier reporting helps preserve evidence. For certain offences under special laws (e.g., POCSO, NDPS), prompt reporting is essential.</p>
                        </details>

                        <details class="faq-item">
                            <summary>What is a Zero FIR?</summary>
                            <p>A Zero FIR is registered at any police station regardless of jurisdiction, then transferred to the appropriate station. Introduced by the Justice Verma Committee and codified in BNSS §173, it prevents delays in sexual assault and other serious cases.</p>
                        </details>

                        <details class="faq-item">
                            <summary>What are my rights if I'm arrested?</summary>
                            <p>You have the right to: know the grounds of arrest, inform a relative or friend, consult a lawyer, be produced before a magistrate within 24 hours, receive medical examination, and be protected from torture or custodial violence. Women cannot be arrested after sunset or before sunrise except in exceptional circumstances.</p>
                        </details>

                        <details class="faq-item">
                            <summary>How do I report cyber crime?</summary>
                            <p>Report online at <strong>cybercrime.gov.in</strong> or dial <strong>1930</strong>. For financial fraud, report within the "golden hour" (first 24 hours) so the transaction can be frozen. Preserve screenshots, transaction IDs, and messages as evidence.</p>
                        </details>

                        <details class="faq-item">
                            <summary>How can I check the status of my complaint?</summary>
                            <p>Use the "Check Diary Status" section on this page, or log in to your State's police Citizen Portal with the FIR number. You may also call the police station where the complaint was filed.</p>
                        </details>

                        <details class="faq-item">
                            <summary>Where can I get free legal aid?</summary>
                            <p>Contact the District Legal Services Authority (DLSA) in your district or the National Legal Services Authority (NALSA). Toll-free: <strong>15100</strong>. Free legal aid is a fundamental right under Article 39A.</p>
                        </details>

                    </div>
                </div>

                <div class="support-strip reveal">
                    <div class="support-item">
                        <i class="fas fa-headset"></i>
                        <div>
                            <strong>Senior Citizen Helpline</strong>
                            <span>14567 · Elder Line</span>
                        </div>
                    </div>
                    <div class="support-item">
                        <i class="fas fa-user-shield"></i>
                        <div>
                            <strong>Anti-Trafficking</strong>
                            <span>1098 / 181</span>
                        </div>
                    </div>
                    <div class="support-item">
                        <i class="fas fa-heart-pulse"></i>
                        <div>
                            <strong>Ambulance</strong>
                            <span>108 / 102</span>
                        </div>
                    </div>
                    <div class="support-item">
                        <i class="fas fa-fire-extinguisher"></i>
                        <div>
                            <strong>Fire Service</strong>
                            <span>101</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <footer class="site-footer">
        <div class="container">&copy; <?php echo date("Y"); ?> Online Police Service &bull; For emergencies dial 112</div>
    </footer>

    <button type="button" class="to-top" id="toTop" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- ============ POPUP / MODAL ============ -->
    <div class="ops-popup-overlay" id="opsPopup" style="position:fixed;inset:0;z-index:2000;display:none;align-items:center;justify-content:center;background:rgba(10,20,40,0.55);backdrop-filter:blur(4px);padding:20px;">
        <div class="ops-popup" role="dialog" aria-modal="true" aria-labelledby="opsPopupTitle">
            <button type="button" class="ops-popup-close" id="opsPopupClose" aria-label="Close">
                <i class="fas fa-xmark"></i>
            </button>
            <div class="ops-popup-icon" id="opsPopupIcon">
                <i class="fas fa-circle-check"></i>
            </div>
            <h3 class="ops-popup-title" id="opsPopupTitle">Success</h3>
            <p class="ops-popup-message" id="opsPopupMessage">Your request has been processed.</p>
            <button type="button" class="ops-popup-btn" id="opsPopupBtn">OK</button>
        </div>
    </div>

    <!-- Navbar + scroll + popup JS -->
    <script src="home.js"></script>
</body>
</html>