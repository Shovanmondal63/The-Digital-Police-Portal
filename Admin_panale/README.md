# 🚔 Online Police System — Admin Dashboard

A secure, modern web-based **Police Administration System** built with **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**. This system allows administrators to manage citizen crime reports, track case statuses (Accept / Reject), monitor live crime statistics, and submit bug reports — all from a single, responsive, beautifully designed dashboard.

---

## 📸 Preview

| Login Page | Admin Dashboard |
|------------|-----------------|
| Secure admin login with Indian Police regulation excerpts | Full dashboard with reports, analytics, and bug reporting |

---

## ✨ Features

### 🔐 Authentication
- Secure admin login page with credential validation
- Animated success & error popups
- Session-based access control

### 📁 Report Management
- View all citizen-submitted crime reports in a responsive card grid
- **Accept** or **Reject** reports with a single click
- Rejection requires a mandatory reason (min. 5 characters) via a modal
- Status badges (Accepted / Rejected / Pending) update in real time
- Displays rejection reasons for rejected reports

### 📊 Crime Rate Analytics
- Live statistics: Total, Accepted, Pending, and Rejected reports
- **Bar charts** showing reports grouped by **Category** and **District**
- Auto-calculated percentages and max-value scaling

### 🐞 Bug Report System
- In-dashboard bug reporting form with validation
- Category & severity selection
- Optional email field
- Confirmation checkbox to prevent spam
- Bug reports stored in a dedicated `bug_reports` table

### 🎨 UI / UX
- Modern **dark navy + gold** police-themed design
- Fully responsive (desktop, tablet, mobile)
- Animated sidebar navigation with smooth scrolling
- Dropdown menu for "Head Office" sub-links
- Toast notifications for success/error feedback
- Glassmorphism, gradients, and micro-interactions

### ℹ️ About Section
- Mission, vision, privacy, and feature highlights
- System status indicators

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL / MariaDB |
| **Frontend** | HTML5, CSS3, JavaScript (Vanilla) |
| **Icons** | Font Awesome 6.5.1 |
| **Fonts** | Inter, Segoe UI, System UI |

---

## 📂 Project Structure

```text
online-police-system/
│
├── index.php                  # Admin login page
├── style.css                  # Login page styles
├── script.js                  # Login popup & redirect logic
├── README.md                  # Project documentation
│
└── home_page/
    ├── home.php               # Main admin dashboard
    ├── home.css               # Dashboard styles
    └── home.js                # Dashboard interactivity
