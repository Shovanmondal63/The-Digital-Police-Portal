🚓 Online Police System
<div align="center">
A secure, role-based web application that digitizes police complaint registration, tracking, and administration.

https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=flat-square&logo=php&logoColor=white
https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white
https://img.shields.io/badge/JavaScript-ES6-F7DF1E?style=flat-square&logo=javascript&logoColor=black
https://img.shields.io/badge/CSS3-Custom-1572B6?style=flat-square&logo=css3&logoColor=white
https://img.shields.io/badge/Apache-XAMPP-D22128?style=flat-square&logo=apache&logoColor=white

</div>
📖 Overview
The Online Police System is a full-stack web application split into two independent modules that work together:

Module	Who Uses It	Purpose	Entry Point
🧑‍💼 User Panel	Citizens	Register, log in, and file complaints	User_panel/index.php
👮 Admin Panel	Police Administrators	Review, accept, or reject complaints	Admin_panel/index.php
🌍 Real-Life Problem It Solves
🚨 The Problem
"I have to physically go to the police station to file a complaint."

In many places, a citizen must travel to a police station, stand in a queue, fill a paper form, and sometimes wait hours just to report a stolen phone or a minor incident. Many people give up because it's too much hassle.

This creates a chain of knock-on problems:

Impact	Description
🚫	People stop reporting minor crimes entirely (theft, harassment, fraud)
📉	Police records become incomplete, so crime statistics are inaccurate
🏃	Criminals get away because victims are too tired to file a complaint
🔒	People who work long hours, the elderly, and people with disabilities are effectively locked out of the reporting system
💸	Travel costs (bus fare, lost wages) discourage reporting small losses
📝	Reports made verbally at a counter are sometimes mis-recorded or lost
✅ How This Project Solves It
A citizen can register and file a report from a phone or laptop at home, 24/7. The report is stored in the database, and the admin (police) sees it on their dashboard immediately.

Concretely, the system provides:

1️⃣ Online Registration — No visit needed
File: register/register.php

A citizen fills User ID, Name, Phone, Password, Confirm Password, and uploads a profile image — all from a browser. The data is inserted into the civilians_data table. On success, a popup with confetti appears and the citizen is redirected to the login page.

2️⃣ Online Login — Secure access from anywhere
File: index.php

The citizen enters User ID + password. PHP checks it against the database and starts a session on success. A welcome popup confirms login and redirects to the dashboard.

3️⃣ Report Is Stored in the Database — No paper form
Table: dairy_data

Each report (Full Name, Contact, District, Police Station, Type of Report, Date/Time, Place, Subject, Description, etc.) is saved as a structured row — not a handwritten paper slip that can be lost.

4️⃣ Admin Sees It Immediately — No delay
File: home/home.php

The admin dashboard queries dairy_data on every page load, so any new report appears on the "All Reports" grid as soon as the page refreshes.

5️⃣ Works on Any Device, Any Time
Files: style.css, home.css (responsive media queries)

The layout adapts to phones, tablets, and desktops. The system runs as long as the web server (Apache/XAMPP) is online — 24/7.

💡 Why This Matters in Real Life
Real-World Impact	What It Means
🌙 11 PM reporting	A stolen phone can be reported at 11 PM from home
👴 No travel barrier	An elderly person can file a complaint without travelling
📊 Structured data	Police stations receive structured data (no handwriting to decipher)
📈 Trustworthy stats	Crime statistics become complete and trustworthy
🚪 Lower barrier	The barrier to reporting drops, so more crimes are actually reported
📑 Table of Contents
<details> <summary><strong>Click to expand</strong></summary>
Features

Tech Stack

Project Structure

Database Setup

Installation & Configuration

Running the Application

Module Walkthroughs

Default Credentials

Security Notes

Known Limitations & Roadmap

Troubleshooting

Credits

</details>
✨ Features
🧑‍💼 User Panel
Feature	Description
🔐 Secure login	Session regeneration (session_regenerate_id) on every login
📝 Self-registration	Profile image upload with live preview
👁️ Password toggle	Eye icon on all password fields
✅ Real-time validation	Password match check (≥ 8 characters)
🎨 Welcome popup	Animated success popup before redirect
❌ Error popup	Animated auto-dismiss on invalid credentials
🛡️ Police-themed UI	Responsive layout with information side panels
👮 Admin Panel
Feature	Description
🔐 Separate admin login	Secure popups for success / failure
📊 Live dashboard	Total / Accepted / Pending / Rejected counters
📁 Report cards	Full civilian complaint details in card layout
✔️ Accept / Reject	Rejection requires a written reason (modal)
📈 Crime analytics	Animated bar charts grouped by category & district
🐞 Bug reporting	Built-in form with validation + DB logging
📱 Responsive sidebar	Collapses to icons on mobile
🔄 Auto-refresh	header("Location: ...") after every action
🛠 Tech Stack
<div align="center">
Layer	Technology
Backend	PHP 7.4+ / 8.x
Database	MySQL / MariaDB
Frontend	HTML5 · CSS3 (custom) · Vanilla JavaScript
Icons	Font Awesome 6
Fonts	Google Fonts — Inter
Server	Apache (XAMPP / WAMP / LAMP)
</div>
⚠️ No frameworks, no Composer — 100% procedural PHP with mysqli.

📂 Project Structure
text
Online-Police-System/
│
├── 📁 User_panel/
│   │
│   ├── 📄 index.php           ← User login page
│   ├── 🎨 style.css           ← Login styles + welcome/error popups
│   ├── ⚙️  script.js          ← Password toggle, validation, popups
│   ├── 📁 uplodes/            ← Uploaded profile images
│   ├── 📄 README.md           ← This file
│   │
│   ├── 📁 home/
│   │   ├── 📄 home.php        ← User dashboard (post-login)
│   │   ├── 🎨 home.css
│   │   └── ⚙️  home.js
│   │
│   └── 📁 register/
│       ├── 📄 register.php    ← User registration form
│       ├── 🎨 register.css    ← Registration styles + popup modal
│       └── ⚙️  register.js    ← Live preview, password match, validation
│
├── 📁 Admin_panel/
│   │
│   ├── 📄 index.php           ← Admin login page
│   ├── 🎨 style.css           ← Admin login styles
│   ├── ⚙️  script.js          ← Success/error popup logic
│   │
│   └── 📁 home_page/
│       ├── 📄 home.php        ← Admin dashboard (reports, stats, bug form)
│       ├── 🎨 home.css        ← Dashboard styles (cards, charts, modal)
│       └── ⚙️  home.js        ← Nav scroll, reject modal, bug validation
│
└── 📄 README.md
🗄 Database Setup
<details> <summary><strong>Click to view full SQL schema</strong></summary>
1️⃣ civilians_data — Registered Citizens
sql
CREATE TABLE civilians_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(120) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    u_password VARCHAR(255) NOT NULL,
    c_password VARCHAR(255) NOT NULL,
    user_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
2️⃣ dairy_data — Complaints / Reports
sql
CREATE TABLE dairy_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    civilians_id INT NOT NULL,
    Full_Name VARCHAR(120),
    Contact_Number VARCHAR(20),
    Email_Address VARCHAR(120),
    Identification_Type VARCHAR(50),
    ID_Number VARCHAR(60),
    Relationship_Incident VARCHAR(80),
    Present_Address TEXT,
    District VARCHAR(80),
    Police_Station VARCHAR(120),
    Type_Report VARCHAR(80),
    Date_Time DATETIME,
    Place VARCHAR(150),
    Subject VARCHAR(200),
    Description TEXT,
    accept VARCHAR(20) DEFAULT NULL,
    regecter_reason TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
3️⃣ bug_reports — Admin Bug Submissions
sql
CREATE TABLE bug_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    category VARCHAR(50) NOT NULL,
    severity VARCHAR(20) NOT NULL,
    description TEXT NOT NULL,
    email VARCHAR(150) DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
</details>
⚙️ Installation & Configuration
Step 1 — Install XAMPP
Download from apachefriends.org → install → start Apache + MySQL (both must be green ✅).

Step 2 — Copy the Project
bash
# Windows
C:\xampp\htdocs\Online-Police-System\

# macOS
/Applications/XAMPP/htdocs/Online-Police-System/

# Linux
/opt/lampp/htdocs/Online-Police-System/
Step 3 — Create the Database
Open http://localhost/phpmyadmin

Click New → name it OPS → Create

Select OPS → SQL tab → paste the schema above → Go

Step 4 — Configure Database Credentials
Edit all three PHP files (User_panel/index.php, Admin_panel/index.php, Admin_panel/home_page/home.php):

php
$servername  = "localhost";
$username    = "root";
$db_password = "";        // ← blank in default XAMPP
$db_name     = "OPS";
Step 5 — Create the Uploads Folder
bash
mkdir User_panel/uplodes
chmod 755 User_panel/uplodes        # Linux / macOS
🚀 Running the Application
Page	URL
🧑‍💼 Citizen Login	http://localhost/Online-Police-System/User_panel/index.php
📝 Register	http://localhost/Online-Police-System/User_panel/register/register.php
👮 Admin Login	http://localhost/Online-Police-System/Admin_panel/index.php
📊 Admin Dashboard	http://localhost/Online-Police-System/Admin_panel/home_page/home.php
🧭 Module Walkthroughs
<details> <summary><strong>🧑‍💼 User Panel — click to expand</strong></summary>
Flow
text
Register → Login → Welcome Popup → Dashboard → File Complaint → Logout
Steps
Register — Fill User ID, Name, Phone, Password, Confirm Password, upload image → submit

Success popup (with confetti 🎊) → auto-redirect to login

Login — Enter User ID + password → welcome popup → dashboard

Dashboard — View your reports, file new complaints, track status

</details><details> <summary><strong>👮 Admin Panel — click to expand</strong></summary>
Flow
text
Admin Login → Success Popup → Dashboard → Review Reports → Accept/Reject → Logout
Dashboard Sections
Section	What You Can Do
🏠 Home Hero	See quick stats (total / accepted / pending / rejected)
📁 All Reports	Browse all citizen complaints as cards
✅ Accept	Mark a report as accepted
❌ Reject	Reject with a written reason (≥ 5 characters)
📈 Crime Rate	View bar charts grouped by category and district
ℹ️ About	System information and mission statement
🐞 Bug Report	Submit bug reports with validation
</details>
🔑 Default Credentials
Role	User ID	Password
👮 Admin	Shovan	1234
⚠️ Change these immediately in any real deployment.

