# 🚔 Online Police System

A secure, modern web-based **Police Administration and Citizen Services System** built with **PHP**, **MySQL**, **HTML**, **CSS**, and **JavaScript**. This platform empowers citizens to register, file General Diary (GD) entries online, track their complaint statuses, and access important police regulations. It also features a comprehensive admin dashboard for police officials to manage reports, view live crime analytics, and receive bug reports.

---

## 📸 Preview

| Login Page | Admin Dashboard |
|------------|-----------------|
| Secure login with Indian Police regulation excerpts | Full dashboard with reports, analytics, and bug reporting |

---

## ✨ Features

### 👤 Citizen Portal (User Side)
- **Secure Registration & Login:** Users can create accounts with profile picture uploads and securely log in.
- **Online General Diary (GD) Entry:** File non-cognizable complaints online with a detailed multi-step form.
- **Status Tracking:** Track the real-time status of filed complaints (Pending / Accepted / Rejected).
- **Rejection Reasons:** View specific reasons if a report is rejected by the police.
- **Withdraw Reports:** Option to withdraw a submitted diary entry.
- **Rules & Regulations:** Access comprehensive information on constitutional provisions, new criminal laws (BNS, BNSS, BSA), and citizen rights.
- **Help & Support:** Integrated helplines, FAQs, and support contact information.

### 🛡️ Admin Dashboard (Police Side)
- **Report Management:** View all citizen-submitted crime reports in a responsive card grid.
- **Accept / Reject Actions:** Accept reports instantly or reject them via a modal requiring a mandatory reason.
- **Live Crime Analytics:** Interactive bar charts showing reports grouped by **Category** and **District**.
- **Bug Reporting System:** In-dashboard form to report system bugs directly to developers.
- **Responsive UI:** A modern, dark navy + gold police-themed design that works perfectly on mobile, tablet, and desktop.

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript (Vanilla) |
| **Icons** | Font Awesome 6.5.1 |
| **Fonts** | Inter, Segoe UI, System UI |

---

## 📂 Project Structure

```text
online-police-system/
│
├── index.php                  # Admin/Citizen Login Page
├── style.css                  # Login page styles
├── script.js                  # Login popup & redirect logic
├── README.md                  # Project documentation
│
├── home/
│   ├── home.php               # Main Admin Dashboard
│   ├── home.css               # Dashboard styles
│   └── home.js                # Dashboard interactivity
│
└── register/
    ├── register.php           # Citizen Registration Form
    ├── register.css           # Registration page styles
    └── register.js            # Registration form validation & image preview
