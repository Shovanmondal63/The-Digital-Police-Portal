# 🚓 Online Police System

A secure, role-based web application built with **PHP** and **MySQL** that digitizes
police complaint registration, tracking, and administration. The system is split into
two independent modules:

| Module | Purpose | Default Entry Point |
|--------|---------|---------------------|
| **User Panel** | Citizens register, log in, and file complaints | `User_panel/index.php` |
| **Admin Panel** | Police administrators review, accept, or reject complaints | `Admin_panel/index.php` |

---
REAL LIFE
---------
In many places, a citizen must travel to a police station, stand in a queue,
fill a paper form, and sometimes wait hours just to report a stolen phone or
a minor incident. Many people give up because it's too much hassle.

This creates a chain of knock-on problems:

  * People stop reporting minor crimes entirely (theft, harassment, fraud).
  * Police records become incomplete, so crime statistics are inaccurate.
  * Criminals get away because victims are too tired to file a complaint.
  * People who work long hours, the elderly, and people with disabilities
    are effectively locked out of the reporting system.
  * Travel costs (bus fare, lost wages) discourage reporting small losses.
  * Reports made verbally at a counter are sometimes mis-recorded or lost.

HOW THIS PROJECT SOLVES IT
--------------------------
A citizen can register and file a report from a phone or laptop at home,
24/7. The report is stored in the database, and the admin (police) sees it
on their dashboard immediately.

Concretely, the system provides:

  1. ONLINE REGISTRATION (no visit needed)
     File:     register/register.php
     A citizen fills User ID, Name, Phone, Password, Confirm Password, and
     uploads a profile image -- all from a browser. The data is inserted into
     the `civilians_data` table. On success, a popup with confetti appears and
     the citizen is redirected to the login page.

  2. ONLINE LOGIN (secure access from anywhere)
     File:     index.php
     The citizen enters User ID + password. PHP checks it against the database
     and starts a session on success. A welcome popup confirms login and
     redirects to the dashboard.

  3. REPORT IS STORED IN THE DATABASE (no paper form)
     Table:    dairy_data
     Each report (Full Name, Contact, District, Police Station, Type of
     Report, Date/Time, Place, Subject, Description, etc.) is saved as a
     structured row -- not a handwritten paper slip that can be lost.

  4. ADMIN SEES IT IMMEDIATELY (no delay)
     File:     home/home.php
     The admin dashboard queries `dairy_data` on every page load, so any new
     report appears on the "All Reports" grid as soon as the page refreshes.

  5. WORKS ON ANY DEVICE, ANY TIME
     File:     style.css, home.css (responsive media queries)
     The layout adapts to phones, tablets, and desktops. The system runs
     as long as the web server (Apache/XAMPP) is online -- 24/7.

WHY THIS MATTERS IN REAL LIFE
-----------------------------
  * A stolen phone can be reported at 11 PM from home.
  * An elderly person can file a complaint without travelling.
  * Police stations receive structured data (no handwriting to decipher).
  * Crime statistics become complete and trustworthy.
  * The barrier to reporting drops, so more crimes are actually reported.

## 📑 Table of Contents

1. [Features](#-features)
2. [Tech Stack](#-tech-stack)
3. [Project Structure](#-project-structure)
4. [Database Setup](#-database-setup)
5. [Installation & Configuration](#-installation--configuration)
6. [Running the Application](#-running-the-application)
7. [Module Walkthroughs](#-module-walkthroughs)
   - [User Panel](#-user-panel)
   - [Admin Panel](#-admin-panel)
8. [Default Credentials](#-default-credentials)
9. [Security Notes](#-security-notes)
10. [Known Limitations & Roadmap](#-known-limitations--roadmap)
11. [Troubleshooting](#-troubleshooting)
12. [Credits](#-credits)

---

## ✨ Features

### User Panel
- 🔐 **Secure login** with session regeneration (`session_regenerate_id`)
- 📝 **Self-registration** with profile image upload and live preview
- 👁️ **Password visibility toggle** (eye icon) on all password fields
- ✅ **Real-time password match validation** (≥ 8 characters)
- 🎨 **Animated welcome popup** on successful login before redirect
- ❌ **Animated error popup** with auto-dismiss on invalid credentials
- 🛡️ **Police-themed, responsive UI** with information side panels

### Admin Panel
- 🔐 **Separate admin login** with secure popups for success/failure
- 📊 **Dashboard with live statistics** (total / accepted / pending / rejected)
- 📁 **Report cards** showing full civilian complaint details
- ✔️ **Accept / ✖️ Reject** actions with a **rejection-reason modal**
- 📈 **Crime rate analytics** — grouped by category and district (animated bar charts)
- 🐞 **Built-in bug report form** with validation and DB logging
- 📱 **Fully responsive sidebar** that collapses on mobile
- 🔄 **Auto-refresh after actions** via `header("Location: ...")`

---

## 🛠 Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 7.4+ / 8.x |
| Database | MySQL / MariaDB |
| Frontend | HTML5, CSS3 (custom), Vanilla JavaScript |
| Icons | Font Awesome 6 |
| Fonts | Google Fonts — Inter |
| Server | Apache (XAMPP / WAMP / LAMP) |

> ⚠️ No frameworks, no Composer — 100% procedural PHP with `mysqli`.

---

## 📂 Project Structure
```text
Online-Police-System/
│
├── User_panel/
│ │
│ ├── index.php ← User login page
│ ├── style.css ← Login styles + welcome/error popups
│ ├── script.js ← Password toggle, validation, popups
| ├── uplodes/
│ ├── README.md ← This file
│ │
│ ├── home/
│ │ ├── home.php ← User dashboard (post-login)
│ │ ├── home.css
│ │ └── home.js
│ │
│ └── register/
│ ├── register.php ← User registration form
│ ├── register.css ← Registration styles + popup modal
│ └── register.js ← Live preview, password match, validation
│
├── Admin_panel/
│ │
│ ├── index.php ← Admin login page
│ ├── style.css ← Admin login styles
│ ├── script.js ← Success/error popup logic
│ │
│ └── home_page/
│ ├── home.php ← Admin dashboard (reports, stats, bug form)
│ ├── home.css ← Dashboard styles (cards, charts, modal)
│ └── home.js ← Nav scroll, reject modal, bug validation
│
```
