# 🚓 Online Police System

A secure, role-based web application built with **PHP** and **MySQL** that digitizes
police complaint registration, tracking, and administration. The system is split into
two independent modules:

| Module | Purpose | Default Entry Point |
|--------|---------|---------------------|
| **User Panel** | Citizens register, log in, and file complaints | `User_panel/index.php` |
| **Admin Panel** | Police administrators review, accept, or reject complaints | `Admin_panel/index.php` |

---

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
