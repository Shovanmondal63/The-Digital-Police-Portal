# 🚓 Online Police System

<p align="center">
  <b>A Digital Platform for Online Complaint Registration and Police Administration</b>
  <br>
  <br>
  <img src="https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/HTML5-Frontend-E34F26?style=for-the-badge&logo=html5&logoColor=white" />
  <img src="https://img.shields.io/badge/CSS3-Styling-1572B6?style=for-the-badge&logo=css3&logoColor=white" />
  <img src="https://img.shields.io/badge/JavaScript-Interactive-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black" />
</p>

---

## 📌 About the Project

**Online Police System** is a web-based application developed using PHP and MySQL to digitize the traditional police complaint registration and administration process.

The system provides two independent modules:

| Module | Description | Entry Point |
|:---|:---|:---|
| 👤 User Panel | Citizens can register, log in, and submit complaints online. | `User_panel/index.php` |
| 🛡️ Admin Panel | Administrators can review, accept, reject, and manage complaints. | `Admin_panel/index.php` |

The main objective is to make complaint registration more accessible, reduce paperwork, and improve communication between citizens and police administration.

---

## 🌍 Real-Life Problem

In traditional complaint registration systems, citizens often need to visit a police station, wait in queues, complete paperwork, and spend valuable time reporting incidents.

This creates several challenges:

- 📝 Time-consuming manual complaint registration.
- 🚶 Unnecessary travel to police stations.
- 📂 Difficulty maintaining and organizing paper records.
- ⏳ Delays in reviewing and processing complaints.
- ♿ Accessibility challenges for elderly and physically disabled citizens.
- 📊 Difficulty analyzing complaint data and identifying crime patterns.

## 💡 How This Project Solves the Problem

The Online Police System provides a digital platform where citizens can register and submit complaints through a web browser.

### Key Solutions

1. **Online Registration:** Citizens can create accounts without visiting a police station.
2. **Secure Login:** Registered users can access their accounts through authentication.
3. **Digital Complaint Submission:** Complaint information is stored in a structured MySQL database.
4. **Centralized Administration:** Administrators can view and manage submitted complaints.
5. **Complaint Status Management:** Administrators can accept or reject complaints.
6. **Crime Analytics:** Dashboard charts help visualize complaint statistics by category and district.
7. **Responsive Interface:** The application is designed for desktop, tablet, and mobile screens.

> **Project Goal:** To make the complaint registration process more accessible, organized, and efficient through digital technology.

---

## ✨ Features

### 👤 User Panel

- 🔐 Secure user login with session management.
- 📝 User registration with profile image upload.
- 👁️ Password visibility toggle.
- ✅ Real-time password matching validation.
- 🔒 Minimum 8-character password validation.
- 🎉 Animated success and error notifications.
- 📋 Online complaint registration.
- 📱 Responsive user interface.

### 🛡️ Admin Panel

- 🔐 Separate administrator login system.
- 📊 Dashboard with complaint statistics.
- 📁 View all submitted complaints.
- ✔️ Accept submitted complaints.
- ❌ Reject complaints with a rejection reason.
- 📈 Crime statistics and analytics.
- 📊 Category-wise and district-wise visualizations.
- 🐞 Built-in bug reporting form.
- 📱 Responsive sidebar navigation.
- 🔄 Automatic page redirection after administrative actions.

---

## 🛠️ Technology Stack

| Category | Technology |
|:---|:---|
| Backend | PHP 7.4+ / PHP 8.x |
| Database | MySQL / MariaDB |
| Frontend | HTML5 |
| Styling | CSS3 |
| Client-side Programming | Vanilla JavaScript |
| Icons | Font Awesome 6 |
| Fonts | Google Fonts (Inter) |
| Local Server | Apache |
| Development Environment | XAMPP / WAMP |

**Architecture:** Procedural PHP with MySQLi. No PHP frameworks or Composer dependencies are required.

---

## 📂 Project Structure

```text
Online-Police-System/
│
├── User_panel/
│   │
│   ├── index.php
│   ├── style.css
│   ├── script.js
│   │
│   ├── uplodes/
│   │
│   ├── README.md
│   │
│   ├── home/
│   │   ├── home.php
│   │   ├── home.css
│   │   └── home.js
│   │
│   └── register/
│       ├── register.php
│       ├── register.css
│       └── register.js
│
├── Admin_panel/
│   │
│   ├── index.php
│   ├── style.css
│   ├── script.js
│   │
│   └── home_page/
│       ├── home.php
│       ├── home.css
│       └── home.js
│
└── README.md
```

### 📄 Important Files

| File | Responsibility |
|:---|:---|
| `User_panel/index.php` | User login and authentication |
| `User_panel/register/register.php` | New user registration |
| `User_panel/home/home.php` | User dashboard |
| `Admin_panel/index.php` | Administrator login |
| `Admin_panel/home_page/home.php` | Admin dashboard and complaint management |
| `style.css` | Login page styling |
| `home.css` | Dashboard styling |
| `script.js` | Login page interactions |
| `home.js` | Dashboard interactions |

---

## 🗄️ Database Setup

The application uses **MySQL** to store user information, complaints, and administrative data.

### Database Name

```sql
CREATE DATABASE OPS;
```

### Main Database Tables

| Table Name | Purpose |
|:---|:---|
| `civilians_data` | Stores registered citizen information. |
| `dairy_data` | Stores submitted complaints and their details. |

**Note:** The complaint table is named `dairy_data` in the current project implementation.

### Database Configuration

Update your database connection settings in the PHP database connection file.

Example:

```php
<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "OPS";

$conn = mysqli_connect(
    $host,
    $username,
    $password,
    $database
);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>
```

Make sure your database name, username, password, and table structures match your local configuration.

---

## ⚙️ Installation & Configuration

Follow these steps to run the project locally.

### Step 1: Install XAMPP

Download and install XAMPP.

https://www.apachefriends.org/

### Step 2: Clone the Repository

Open your terminal and execute:

```bash
git clone https://github.com/Shovanmondal63/The-Digital-Police-Portal.git
```

### Step 3: Move the Project

Move the downloaded project folder into the XAMPP `htdocs` directory.

```text
C:/xampp/htdocs/Online-Police-System/
```

### Step 4: Start Apache and MySQL

Open XAMPP Control Panel and start:

- Apache
- MySQL

### Step 5: Create the Database

1. Open `http://localhost/phpmyadmin/`.
2. Create a database named `OPS`.
3. Import your project SQL file if available.
4. Ensure all required tables and columns are created.

### Step 6: Configure Database Connection

Update the database credentials in your PHP connection file.

### Step 7: Run the Application

Open your browser and visit the following URLs.

**User Panel:**

```text
http://localhost/Online-Police-System/User_panel/
```

**Admin Panel:**

```text
http://localhost/Online-Police-System/Admin_panel/
```

---

## 🚀 Module Walkthrough

### 1. User Panel

The User Panel allows citizens to access the complaint registration system.

**Registration Process**

1. Open the registration page.
2. Enter the required personal information.
3. Upload a profile image.
4. Create and confirm a password.
5. Submit the registration form.

**Login Process**

1. Enter your registered User ID.
2. Enter your password.
3. Complete authentication.
4. Access the user dashboard.

**Complaint Registration**

Citizens can submit complaint information, including:

- Full Name
- Contact Information
- District
- Police Station
- Type of Complaint
- Date and Time
- Incident Location
- Subject
- Detailed Description

### 2. Admin Panel

The Admin Panel provides administrative control over submitted complaints.

**Dashboard Features**

- View total complaints.
- View accepted complaints.
- View pending complaints.
- View rejected complaints.
- Access complaint details.
- Accept or reject complaints.
- Provide rejection reasons.
- Analyze complaint statistics.

---

## 🔐 Security Notes

Security is an important consideration for applications handling citizen information.

The project includes session management and authentication-related functionality.

For a production-ready deployment, the following security measures should also be implemented and verified:

- Use `password_hash()` and `password_verify()` for password storage and verification.
- Use prepared statements to prevent SQL injection.
- Regenerate session IDs after successful login.
- Validate uploaded image types, sizes, and file contents.
- Protect administrative actions against CSRF attacks.
- Enforce server-side authorization checks.
- Use HTTPS for secure communication.
- Avoid exposing database credentials or sensitive information in public repositories.

> **Disclaimer:** This is an educational project. It should not be treated as an officially approved police complaint registration platform or used for real emergency reporting.

---

## ⚠️ Known Limitations & Future Improvements

The project can be extended with additional functionality in future versions.

| Feature | Description | Status |
|:---|:---|:---|
| Online User Registration | Citizen account creation | Implemented |
| User Authentication | Login and session management | Implemented |
| Complaint Submission | Digital complaint registration | Implemented |
| Admin Dashboard | Complaint management | Implemented |
| Accept / Reject System | Complaint decision management | Implemented |
| Crime Analytics | Statistical visualizations | Implemented |
| Email Notifications | Automatic status notifications | Planned |
| Complaint Tracking ID | Unique complaint tracking system | Planned |
| PDF Complaint Receipt | Downloadable complaint receipt | Planned |
| Advanced Security | Additional production-level protections | Planned |

---

## 🐞 Troubleshooting

**1. Database Connection Failed**

- Check whether MySQL is running.
- Verify database credentials.
- Confirm that the `OPS` database exists.

**2. Page Not Found (404 Error)**

- Ensure Apache is running.
- Check the project folder inside `htdocs`.
- Verify the URL and folder names.

**3. Images Are Not Displaying**

- Check the image upload directory.
- Verify the image file path.
- Check folder permissions.

**4. Login Not Working**

- Verify that the user exists in the database.
- Check the authentication query.
- Verify password handling and session configuration.

---

## 👨‍💻 Developer

**Shovan Mondal**

BCA Student | Aspiring Software Developer

Interested in:
- PHP & MySQL Development
- Java Programming
- Data Structures & Algorithms
- Object-Oriented Programming
- Database Management Systems
- Software Engineering

GitHub: [@Shovanmondal63](https://github.com/Shovanmondal63)

---

## 🎓 Project Information

| Details | Information |
|:---|:---|
| Project Name | Online Police System |
| Project Type | Web-Based Application |
| Backend | PHP |
| Database | MySQL |
| Architecture | Two-Module System |
| Development Purpose | Academic & Learning Project |

---

## ⭐ Support

If you find this project interesting or useful, consider giving the repository a ⭐ on GitHub.

Your support is appreciated!

---

<p align="center">
  <b>🚓 Online Police System</b>
  <br>
  <i>Making complaint registration more accessible through technology.</i>
  <br><br>
  Developed by <b>Shovan Mondal</b>
</p>
