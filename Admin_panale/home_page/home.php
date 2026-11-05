<?php
    // ============================================================
    // 1. INITIALIZATION & DATABASE CONNECTION
    // ============================================================
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');

    $servername  = "localhost";
    $username    = "root";
    $db_password = "";
    $db_name     = "OPS";

    $conn = new mysqli($servername, $username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }

    // ============================================================
    // 2. HANDLE FORM SUBMISSIONS (ACCEPT / REJECT / LOGOUT / BUG)
    // ============================================================

    // Handle Logout
    if (isset($_POST["logout"])) {
        header("Location: ../index.php");
        exit;
    }

    // Handle Accept
    if (isset($_POST["accept"])) {
        $unic_id = intval($_POST["report_id"]);

        $stmt = $conn->prepare("UPDATE dairy_data SET accept = 'Accept' WHERE civilians_id = ?");
        $stmt->bind_param("i", $unic_id);

        if (!$stmt->execute()) {
            die("Error updating record: " . $stmt->error);
        }

        $stmt->close();
        header("Location: home.php");
        exit;
    }

    // Handle Reject
    if (isset($_POST["regect"])) {
        $reason  = trim($_POST["regection_reason"] ?? '');
        $unic_id = intval($_POST["report_id"] ?? 0);

        if (strlen($reason) < 5 || $unic_id <= 0) {
            $reject_error = "Rejection reason must be at least 5 characters.";
        } else {
            $stmt = $conn->prepare(
                "UPDATE dairy_data SET accept = 'Reject', `regecter_reason` = ? WHERE civilians_id = ?"
            );
            $stmt->bind_param("si", $reason, $unic_id);

            if (!$stmt->execute()) {
                die("Error rejecting record: " . $stmt->error);
            }

            $stmt->close();
            header("Location: home.php");
            exit;
        }
    }

    // Handle Bug Report Submission
    $bug_success = false;
    if (isset($_POST['bug_title'])) {
        $bug_title    = trim($_POST['bug_title'] ?? '');
        $bug_category = trim($_POST['bug_category'] ?? '');
        $bug_severity = trim($_POST['bug_severity'] ?? '');
        $bug_desc     = trim($_POST['bug_description'] ?? '');
        $bug_email    = trim($_POST['bug_email'] ?? '');

        if (strlen($bug_title) >= 5 && $bug_category !== '' && $bug_severity !== '' && strlen($bug_desc) >= 15) {
            $conn->query("CREATE TABLE IF NOT EXISTS bug_reports (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(200) NOT NULL,
                category VARCHAR(50) NOT NULL,
                severity VARCHAR(20) NOT NULL,
                description TEXT NOT NULL,
                email VARCHAR(150) DEFAULT NULL,
                status VARCHAR(20) DEFAULT 'open',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            $stmt = $conn->prepare(
                "INSERT INTO bug_reports (title, category, severity, description, email) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("sssss", $bug_title, $bug_category, $bug_severity, $bug_desc, $bug_email);
            $stmt->execute();
            $stmt->close();
            $bug_success = true;
        }
    }

    // ============================================================
    // 3. FETCH RECORDS
    // ============================================================
    $sql = "SELECT 
        Full_Name, Contact_Number, Email_Address, Identification_Type, 
        ID_Number, Relationship_Incident, Present_Address, District, 
        Police_Station, Type_Report, Date_Time, Place, Subject, 
        Description, civilians_id, accept, `regecter_reason` 
        FROM dairy_data";

    $result = $conn->query($sql);

    $records = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
    }

    // ============================================================
    // 4. CRIME RATE STATISTICS CALCULATIONS
    // ============================================================
    $total_reports    = count($records);
    $accepted_reports = 0;
    $pending_reports  = 0;
    $rejected_reports = 0;

    $category_counts = [];
    $district_counts = [];

    foreach ($records as $row) {
        $status = isset($row['accept']) ? strtolower(trim($row['accept'])) : '';

        if ($status === 'accept') {
            $accepted_reports++;
        } elseif ($status === 'reject' || $status === 'rejected') {
            $rejected_reports++;
        } else {
            $pending_reports++;
        }

        $type = !empty($row['Type_Report']) ? $row['Type_Report'] : 'Uncategorized';
        if (!isset($category_counts[$type])) $category_counts[$type] = 0;
        $category_counts[$type]++;

        $district = !empty($row['District']) ? $row['District'] : 'Unknown District';
        if (!isset($district_counts[$district])) $district_counts[$district] = 0;
        $district_counts[$district]++;
    }

    arsort($category_counts);
    arsort($district_counts);

    $max_category_count = !empty($category_counts) ? max($category_counts) : 1;
    $max_district_count = !empty($district_counts) ? max($district_counts) : 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Police System · Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="home.css?v=1.3">
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="brand-block">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                    <path d="M50 5 L90 20 L90 50 C90 75 50 95 50 95 C50 95 10 75 10 50 L10 20 Z" fill="#c9a227" stroke="#ffffff" stroke-width="2"/>
                    <path d="M50 12 L83 24 L83 50 C83 70 50 87 50 87 C50 87 17 70 17 50 L17 24 Z" fill="#0b1e3a"/>
                    <polygon points="50,30 53,40 63,40 55,46 58,56 50,50 42,56 45,46 37,40 47,40" fill="#c9a227"/>
                    <circle cx="50" cy="68" r="8" fill="none" stroke="#c9a227" stroke-width="2" stroke-dasharray="3, 3"/>
                    <circle cx="50" cy="68" r="2" fill="#c9a227"/>
                </svg>
            </div>
            <div class="brand-text">
                <h2>Online Police</h2>
                <p><i class="fas fa-lock"></i> Admin Panel</p>
            </div>
        </div>

        <nav class="nav-links">
            <a href="#home" class="active" id="navHome"><i class="fas fa-house"></i> <span>Home</span></a>
            <a href="#all_reports" id="navAllReports"><i class="fas fa-file-lines"></i> <span>All reports</span></a>
            <a href="#crime_rate" id="navCrimeRate"><i class="fas fa-chart-line"></i> <span>Crime rate</span></a>
            <a href="#about" id="navAbout"><i class="fas fa-circle-info"></i> <span>About</span></a>
            <a href="#bug_report" id="navBugReport"><i class="fas fa-bug"></i> <span>Bug report</span></a>

            <div class="divider"></div>

            <div class="dropdown-container">
                <div class="dropdown-toggle" id="headOfficeToggle">
                    <div class="left-content">
                        <i class="fas fa-building-columns"></i> <span>Head office</span>
                    </div>
                    <i class="fas fa-chevron-down arrow"></i>
                </div>
                <div class="sub-menu" id="headOfficeMenu">
                    <a href="#"><i class="fas fa-location-dot"></i> <span>Main HQ</span></a>
                    <a href="#"><i class="fas fa-map-location-dot"></i> <span>Regional</span></a>
                </div>
            </div>
        </nav>

        <form action="" method="POST" class="logout-form">
            <button type="submit" name="logout" class="logout-btn">
                <i class="fas fa-right-from-bracket"></i> <span>Logout</span>
            </button>
        </form>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- ============================================================ -->
        <!-- HOME / HERO SECTION                                            -->
        <!-- ============================================================ -->
        <section class="home-hero" id="home">
            <div class="home-hero-content">
                <span class="home-eyebrow"><i class="fas fa-shield-halved"></i> Admin Control Center</span>
                <h1>Welcome back, <span class="accent">Administrator</span></h1>
                <p>
                    Manage citizen reports, monitor live crime statistics, and keep your community
                    informed — all from one secure dashboard.
                </p>
                <div class="home-actions">
                    <a href="#all_reports" class="home-btn primary">
                        <i class="fas fa-file-lines"></i> View Reports
                    </a>
                    <a href="#crime_rate" class="home-btn ghost">
                        <i class="fas fa-chart-line"></i> Crime Analytics
                    </a>
                </div>
            </div>
            <div class="home-hero-badge">
                <div class="hero-badge-icon"><i class="fas fa-user-shield"></i></div>
                <h4>System Online</h4>
                <p>All services operational</p>
                <span class="hero-badge-status"><i class="fas fa-circle"></i> Live</span>
            </div>
        </section>

        <!-- QUICK STATS -->
        <section class="home-quickstats">
            <div class="quickstat-card">
                <div class="quickstat-icon blue"><i class="fas fa-folder-open"></i></div>
                <div><h3><?php echo $total_reports; ?></h3><p>Total Reports</p></div>
            </div>
            <div class="quickstat-card">
                <div class="quickstat-icon green"><i class="fas fa-circle-check"></i></div>
                <div><h3><?php echo $accepted_reports; ?></h3><p>Accepted</p></div>
            </div>
            <div class="quickstat-card">
                <div class="quickstat-icon amber"><i class="fas fa-clock"></i></div>
                <div><h3><?php echo $pending_reports; ?></h3><p>Pending</p></div>
            </div>
            <div class="quickstat-card">
                <div class="quickstat-icon red"><i class="fas fa-circle-xmark"></i></div>
                <div><h3><?php echo $rejected_reports; ?></h3><p>Rejected</p></div>
            </div>
        </section>

        <?php if (!empty($reject_error)): ?>
            <div class="toast error show" style="position:relative; transform:none; right:0; bottom:0; margin-bottom:1rem; display:inline-flex;">
                <i class="fas fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($reject_error); ?></span>
            </div>
        <?php endif; ?>

        <!-- ============================================================ -->
        <!-- ALL REPORTS                                                    -->
        <!-- ============================================================ -->
        <div class="all_reports" id="all_reports" style="margin-top: 2.5rem;">
            <?php if (count($records) > 0): ?>
                <div class="reports-header">
                    <h2><i class="fas fa-folder-open"></i> All Reports</h2>
                    <span class="reports-count"><i class="fas fa-database" style="margin-right: 6px;"></i> <?php echo count($records); ?> records</span>
                </div>

                <div class="reports-grid">
                    <?php foreach ($records as $index => $row): ?>
                        <?php
                            $db_status = isset($row['accept']) ? strtolower(trim($row['accept'])) : '';
                            $badge_style = '';
                            $badge_text = htmlspecialchars($row["Type_Report"]);

                            if ($db_status === 'accept') {
                                $badge_style = 'background: #1a7f4b; border-color: #14663c;';
                                $badge_text = 'Accepted';
                            } elseif ($db_status === 'reject' || $db_status === 'rejected') {
                                $badge_style = 'background: #b22234; border-color: #9e1e2d;';
                                $badge_text = 'Rejected';
                            }
                        ?>
                        <div class="report-card" style="animation-delay: <?php echo ($index * 0.03); ?>s;">
                            <div class="card-header">
                                <div class="civilian-id"><i class="fas fa-id-card"></i> ID: <?php echo htmlspecialchars($row["civilians_id"]); ?></div>
                                <div class="report-type-badge" style="<?php echo $badge_style; ?>"><?php echo $badge_text; ?></div>
                            </div>

                            <div class="card-body">
                                <div class="field-row"><div class="field-label"><i class="fas fa-user"></i> Full Name</div><div class="field-value"><?php echo htmlspecialchars($row["Full_Name"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-phone"></i> Contact</div><div class="field-value"><?php echo htmlspecialchars($row["Contact_Number"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-envelope"></i> Email</div><div class="field-value"><?php echo htmlspecialchars($row["Email_Address"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-id-badge"></i> ID Type</div><div class="field-value"><?php echo htmlspecialchars($row["Identification_Type"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-hashtag"></i> ID Number</div><div class="field-value"><?php echo htmlspecialchars($row["ID_Number"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-users"></i> Relationship</div><div class="field-value"><?php echo htmlspecialchars($row["Relationship_Incident"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-home"></i> Address</div><div class="field-value"><?php echo htmlspecialchars($row["Present_Address"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-map-pin"></i> District</div><div class="field-value"><?php echo htmlspecialchars($row["District"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-building"></i> Police Station</div><div class="field-value"><?php echo htmlspecialchars($row["Police_Station"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-calendar-alt"></i> Date / Time</div><div class="field-value"><?php echo htmlspecialchars($row["Date_Time"]); ?></div></div>
                                <div class="field-row"><div class="field-label"><i class="fas fa-location-dot"></i> Place</div><div class="field-value"><?php echo htmlspecialchars($row["Place"]); ?></div></div>
                                <div class="field-row full-width"><div class="field-label"><i class="fas fa-tag"></i> Subject</div><div class="field-value"><?php echo htmlspecialchars($row["Subject"]); ?></div></div>
                                <div class="field-row full-width"><div class="field-label"><i class="fas fa-align-left"></i> Description</div><div class="field-value"><?php echo nl2br(htmlspecialchars($row["Description"])); ?></div></div>

                                <?php if (!empty($row['regecter_reason'])): ?>
                                    <div class="field-row full-width" style="border-top: 1px solid #f0f4fb; padding-top: 0.6rem;">
                                        <div class="field-label" style="color:#b22234;"><i class="fas fa-ban"></i> Rejection Reason</div>
                                        <div class="field-value" style="background:#fff5f6; border-left-color:#b22234; color:#8b1a28;"><?php echo nl2br(htmlspecialchars($row['regecter_reason'])); ?></div>
                                    </div>
                                <?php endif; ?>

                                <form action="" method="POST" class="action-form">
                                    <input type="hidden" name="report_id" value="<?php echo htmlspecialchars($row["civilians_id"]); ?>">
                                    <div class="action-buttons">
                                        <button type="submit" name="accept" value="accept" class="btn-accept" <?php echo ($db_status === 'accept') ? 'disabled' : ''; ?>>
                                            <i class="fas fa-check-circle"></i> Accept
                                        </button>
                                        <button type="button" class="btn-reject"
                                            data-report-id="<?php echo htmlspecialchars($row["civilians_id"]); ?>"
                                            onclick="openRejectModal(this)"
                                            <?php echo ($db_status === 'reject' || $db_status === 'rejected') ? 'disabled' : ''; ?>>
                                            <i class="fas fa-times-circle"></i> Reject
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-records"><i class="fas fa-folder-open"></i><p>No records found in the database.</p></div>
            <?php endif; ?>
        </div>

        <!-- ============================================================ -->
        <!-- CRIME RATE SECTION                                             -->
        <!-- ============================================================ -->
        <div class="crime-section" id="crime_rate">
            <div class="reports-header">
                <h2><i class="fas fa-chart-pie"></i> Crime Rate Overview</h2>
                <span class="reports-count"><i class="fas fa-sync-alt" style="margin-right: 6px;"></i> Live Data</span>
            </div>

            <div class="stats-grid">
                <div class="stat-card"><div class="stat-icon total"><i class="fas fa-folder-open"></i></div><div class="stat-info"><h3><?php echo $total_reports; ?></h3><p>Total Reports</p></div></div>
                <div class="stat-card"><div class="stat-icon accepted"><i class="fas fa-check-circle"></i></div><div class="stat-info"><h3><?php echo $accepted_reports; ?></h3><p>Accepted Cases</p></div></div>
                <div class="stat-card"><div class="stat-icon pending"><i class="fas fa-clock"></i></div><div class="stat-info"><h3><?php echo $pending_reports; ?></h3><p>Pending Cases</p></div></div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <h3><i class="fas fa-tags"></i> Reports by Category</h3>
                    <div class="bar-chart">
                        <?php if (!empty($category_counts)): ?>
                            <?php foreach ($category_counts as $category => $count): ?>
                                <?php $width = ($count / $max_category_count) * 100; ?>
                                <div class="bar-group">
                                    <div class="bar-header"><span><?php echo htmlspecialchars($category); ?></span><span><?php echo $count; ?> cases</span></div>
                                    <div class="bar-track"><div class="bar-fill" style="width: <?php echo $width; ?>%;"></div></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center; color:#6b7f99; font-size:0.9rem;">No data available</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="chart-card">
                    <h3><i class="fas fa-map-location-dot"></i> Reports by District</h3>
                    <div class="bar-chart">
                        <?php if (!empty($district_counts)): ?>
                            <?php foreach ($district_counts as $district => $count): ?>
                                <?php $width = ($count / $max_district_count) * 100; ?>
                                <div class="bar-group">
                                    <div class="bar-header"><span><?php echo htmlspecialchars($district); ?></span><span><?php echo $count; ?> cases</span></div>
                                    <div class="bar-track"><div class="bar-fill gold" style="width: <?php echo $width; ?>%;"></div></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align:center; color:#6b7f99; font-size:0.9rem;">No data available</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- ABOUT SECTION                                                  -->
        <!-- ============================================================ -->
        <div class="about-section" id="about">
            <div class="reports-header">
                <h2><i class="fas fa-circle-info"></i> About the System</h2>
                <span class="reports-count"><i class="fas fa-code-branch" style="margin-right:6px;"></i> v1.3.0</span>
            </div>

            <div class="about-hero">
                <div class="about-hero-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                        <path d="M50 5 L90 20 L90 50 C90 75 50 95 50 95 C50 95 10 75 10 50 L10 20 Z" fill="#c9a227" stroke="#ffffff" stroke-width="2"/>
                        <path d="M50 12 L83 24 L83 50 C83 70 50 87 50 87 C50 87 17 70 17 50 L17 24 Z" fill="#0b1e3a"/>
                        <polygon points="50,30 53,40 63,40 55,46 58,56 50,50 42,56 45,46 37,40 47,40" fill="#c9a227"/>
                    </svg>
                </div>
                <div class="about-hero-text">
                    <h3>Online Police System</h3>
                    <p>
                        A secure digital platform designed to modernize the way citizens report incidents,
                        track case progress, and connect with law enforcement. Our mission is to make
                        reporting transparent, accessible, and efficient for every community we serve.
                    </p>
                </div>
            </div>

            <div class="about-grid">
                <div class="about-card">
                    <div class="about-card-icon"><i class="fas fa-bullseye"></i></div>
                    <h4>Our Mission</h4>
                    <p>To empower citizens with a fast, transparent, and reliable channel to report crimes and safety concerns — bridging the gap between communities and law enforcement.</p>
                </div>
                <div class="about-card">
                    <div class="about-card-icon"><i class="fas fa-eye"></i></div>
                    <h4>Our Vision</h4>
                    <p>A safer, more connected society where every report is heard, every case is tracked, and justice is delivered without unnecessary delay.</p>
                </div>
                <div class="about-card">
                    <div class="about-card-icon"><i class="fas fa-shield-halved"></i></div>
                    <h4>Data &amp; Privacy</h4>
                    <p>All submitted reports and personal data are stored securely and only accessed by authorized personnel. We follow strict data protection practices.</p>
                </div>
                <div class="about-card">
                    <div class="about-card-icon"><i class="fas fa-users-gear"></i></div>
                    <h4>Built For Everyone</h4>
                    <p>From citizens to officers, our system is designed for usability across devices — with clear workflows, live status tracking, and responsive design.</p>
                </div>
            </div>

            <div class="about-features">
                <h4><i class="fas fa-star"></i> Key Features</h4>
                <ul class="feature-list">
                    <li><i class="fas fa-check"></i> Submit crime reports online with full details and evidence descriptions</li>
                    <li><i class="fas fa-check"></i> Track report status (Pending / Accepted / Rejected) in real time</li>
                    <li><i class="fas fa-check"></i> Admin dashboard with filterable records and rejection reasons</li>
                    <li><i class="fas fa-check"></i> Live crime rate statistics by category and district</li>
                    <li><i class="fas fa-check"></i> Secure authentication and role-based access</li>
                    <li><i class="fas fa-check"></i> Mobile-friendly responsive interface</li>
                </ul>
            </div>

            <div class="about-stats-bar">
                <div class="about-stat"><i class="fas fa-file-shield"></i><h5>100%</h5><p>Secure Storage</p></div>
                <div class="about-stat"><i class="fas fa-clock"></i><h5>24/7</h5><p>Report Access</p></div>
                <div class="about-stat"><i class="fas fa-mobile-screen"></i><h5>Responsive</h5><p>All Devices</p></div>
                <div class="about-stat"><i class="fas fa-headset"></i><h5>Support</h5><p>For Citizens</p></div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- BUG REPORT SECTION                                             -->
        <!-- ============================================================ -->
        <div class="bug-section" id="bug_report">
            <div class="reports-header">
                <h2><i class="fas fa-bug"></i> Report a Bug</h2>
                <span class="reports-count"><i class="fas fa-triangle-exclamation" style="margin-right:6px;"></i> Help us improve</span>
            </div>

            <div class="bug-layout">
                <!-- Bug report form -->
                <div class="bug-form-card">
                    <div class="bug-form-header">
                        <i class="fas fa-paper-plane"></i>
                        <div>
                            <h4>Submit a Bug Report</h4>
                            <p>Found something broken? Let us know and we'll fix it.</p>
                        </div>
                    </div>

                    <?php if (!empty($bug_success)): ?>
                        <div class="toast success show" style="position:relative; transform:none; right:0; bottom:0; margin-bottom:1rem; display:flex;">
                            <i class="fas fa-circle-check"></i>
                            <span>Bug report submitted successfully. Thank you for helping us improve!</span>
                        </div>
                    <?php endif; ?>

                    <form id="bugReportForm" action="" method="POST" novalidate>
                        <div class="bug-field">
                            <label for="bugTitle">Bug Title <span class="required-star">*</span></label>
                            <input type="text" id="bugTitle" name="bug_title" placeholder="e.g. Accept button not working on mobile" maxlength="120" required>
                        </div>

                        <div class="bug-row">
                            <div class="bug-field">
                                <label for="bugCategory">Category <span class="required-star">*</span></label>
                                <select id="bugCategory" name="bug_category" required>
                                    <option value="">Select a category…</option>
                                    <option value="ui">UI / Design</option>
                                    <option value="functionality">Functionality</option>
                                    <option value="performance">Performance</option>
                                    <option value="data">Data / Reports</option>
                                    <option value="security">Security</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="bug-field">
                                <label for="bugSeverity">Severity <span class="required-star">*</span></label>
                                <select id="bugSeverity" name="bug_severity" required>
                                    <option value="">Select severity…</option>
                                    <option value="low">Low — Minor annoyance</option>
                                    <option value="medium">Medium — Affects usage</option>
                                    <option value="high">High — Blocks workflow</option>
                                    <option value="critical">Critical — Data loss / crash</option>
                                </select>
                            </div>
                        </div>

                        <div class="bug-field">
                            <label for="bugDescription">Description <span class="required-star">*</span></label>
                            <textarea id="bugDescription" name="bug_description" placeholder="Describe what happened, what you expected, and how to reproduce it…" maxlength="1000" required></textarea>
                            <div class="bug-hint"><i class="fas fa-lightbulb"></i> Include steps to reproduce if possible (min 15 characters).</div>
                        </div>

                        <div class="bug-field">
                            <label for="bugEmail">Your Email <span class="bug-optional">(optional)</span></label>
                            <input type="email" id="bugEmail" name="bug_email" placeholder="you@example.com">
                        </div>

                        <div class="bug-field bug-checkbox-field">
                            <label class="bug-checkbox-label">
                                <input type="checkbox" id="bugConsent" name="bug_consent" required>
                                <span>I confirm this is a genuine bug report and not spam.</span>
                            </label>
                        </div>

                        <div class="bug-error-msg" id="bugFormError">
                            <i class="fas fa-circle-exclamation"></i>
                            <span id="bugFormErrorText">Please fill in all required fields correctly.</span>
                        </div>

                        <button type="submit" class="bug-submit-btn">
                            <i class="fas fa-bug"></i> Submit Bug Report
                        </button>
                    </form>
                </div>

                <!-- Bug info column -->
                <div class="bug-info-column">
                    <div class="bug-info-card">
                        <h4><i class="fas fa-circle-question"></i> Before You Report</h4>
                        <ul class="bug-tips-list">
                            <li><i class="fas fa-check"></i> Try refreshing the page first.</li>
                            <li><i class="fas fa-check"></i> Check if the issue happens on other browsers.</li>
                            <li><i class="fas fa-check"></i> Note what you clicked right before the bug appeared.</li>
                            <li><i class="fas fa-check"></i> Include screenshots in the description if helpful.</li>
                        </ul>
                    </div>

                    <div class="bug-info-card">
                        <h4><i class="fas fa-list-check"></i> What Happens Next</h4>
                        <ol class="bug-steps-list">
                            <li><span class="step-num">1</span> We receive and log your report.</li>
                            <li><span class="step-num">2</span> Our team reproduces the issue.</li>
                            <li><span class="step-num">3</span> A fix is developed and tested.</li>
                            <li><span class="step-num">4</span> The update is deployed to production.</li>
                        </ol>
                    </div>

                    <div class="bug-info-card bug-status-card">
                        <h4><i class="fas fa-shield-heart"></i> System Status</h4>
                        <div class="bug-status-row">
                            <span>Report Submission</span>
                            <span class="status-pill online"><i class="fas fa-circle"></i> Operational</span>
                        </div>
                        <div class="bug-status-row">
                            <span>Admin Dashboard</span>
                            <span class="status-pill online"><i class="fas fa-circle"></i> Operational</span>
                        </div>
                        <div class="bug-status-row">
                            <span>Database</span>
                            <span class="status-pill online"><i class="fas fa-circle"></i> Operational</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div style="height: 3rem;"></div>
    </main>

    <!-- REJECT REASON MODAL -->
    <form action="" method="POST" id="rejectForm">
        <div class="modal-overlay" id="rejectModal">
            <div class="modal-box">
                <div class="modal-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h3>Reject Report</h3>
                <p class="modal-subtitle">
                    You are about to reject report <strong id="rejectReportId">#</strong>.<br>
                    Please provide a valid reason for rejection.
                </p>

                <input type="hidden" name="report_id" id="modalReportId" value="">

                <label for="rejectReason">
                    Reason for rejection <span class="required-star">*</span>
                </label>
                <textarea id="rejectReason" placeholder="Write the reason why this report is being rejected..." maxlength="500" name="regection_reason"></textarea>
                <div class="modal-error-msg" id="reasonError">
                    <i class="fas fa-circle-exclamation"></i> Please write a proper reason (at least 5 characters).
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeRejectModal()">
                        <i class="fas fa-xmark"></i> Cancel
                    </button>
                    <button type="submit" class="btn-confirm-reject" name="regect">
                        <i class="fas fa-check"></i> Confirm Reject
                    </button>
                </div>
            </div>
        </div>
    </form>

    <script src="home.js"></script>
</body>
</html>