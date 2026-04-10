# Student Management System (SMS)

A dynamic, web-based Student Management System designed to streamline and automate day-to-day educational operations. Built with a robust PHP backend and an intuitive, modern frontend, this application provides distinct, secure, and fully-featured portals for Administrators, Teachers, and Students.
The dashboard UI is based on a design created by [maryinparis].
I modified and extended the original design to fit this project's requirements.

Original Source: [Duralux - PHP Admin & Dashboard Bootstrap Template](https://themeforest.net/item/duralux-php-admin-dashboard-template/55822753)

## 🚀 Key Features

### 👑 Administrator Portal
* **User Management:** Complete registration, approval, and profile management for staff and students.
* **Academic Organization:** Manage classes, sections, subjects, and assign teachers.
* **Fee Management:** Create fee types, issue dues, record incoming payments, and securely track the financial standing of students.
* **Announcements:** Broadcast important updates institution-wide or restricted to specific classes.

### 👩‍🏫 Teacher Portal
* **Attendance Tracking:** Easily capture and log daily student attendance for assigned classes.
* **Results & Grading:** Secure interface to enter exam marks, compute totals, and submit student performance.
* **Class Overview:** Monitor student rosters and track overall academic progress seamlessly.

### 🎓 Student Portal
* **Performance Dashboard:** Visual insights into academic standing, subject grades, and overall percentages.
* **Result Breakdowns:** Review detailed exam performances (marks obtained vs. total marks) across all subjects.
* **Fee Status Tracking:** Transparently check pending dues, payment history, and due dates.
* **Announcements Feed:** Stay up to date with immediate access to important class notifications and school announcements.

## 🛠 Tech Stack

* **Backend:** Vanilla PHP natively interconnected with modern structure
* **Database:** MySQL
* **Frontend:** HTML5, CSS3 (Bootstrap alongside custom, modern stylesheets), and JavaScript

## ⚙️ Installation & Local Setup

1. **Prerequisites:** Ensure you have a local web development environment installed such as *Laragon*, *XAMPP*, or *WAMP*.
2. **Setup the files:** 
   Place the `SMS` repository folder directly into your server's root directory:
   * **Laragon:** `C:\laragon\www\SMS`
   * **XAMPP:** `C:\xampp\htdocs\SMS`
3. **Database Setup:**
   * Open your database manager (e.g., phpMyAdmin at `http://localhost/phpmyadmin`).
   * Create a new database named `student_management_system` (or whatever your backup SQL file assumes).
   * Import the project's SQL dump into this new database to construct tables and mock data.
4. **Environment Configuration:** 
   * Open `includes/conn.php` in a text editor to ensure that the database credentials match your local MySQL configuration (usually `root` for user and an empty password).
5. **Launch Application:**
   Open your preferred web browser and navigate to `http://localhost/SMS` to load the application.

## 🔒 Security & Role-Based Access
Authentication is meticulously handled out-of-the-box leveraging PHP Sessions. The platform dynamically routes and enforces specific layouts based on the `admin`, `teacher`, or `student` role—ensuring isolated environments and uncompromising data integrity.

---
