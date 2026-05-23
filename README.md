# BTECH Admission Office Website

> A Laravel-based admission management system built to reduce manual encoding errors and help school administrators manage applications more efficiently.

## 📌 Overview

The **BTECH Admission Office Website** is a web application designed for school admission offices that need a more reliable way to collect, process, and manage student applications. Instead of relying heavily on manual encoding, paper forms, and scattered files, the system provides a centralized online platform for applicants, guests, and administrators.

In a real admission workflow, guests can browse available programs, read announcements, view news and events, and submit an online application. Administrators can review applications, update applicant status, manage program information, schedule interviews, publish announcements, and maintain public website content from a protected dashboard.

The main goal of the project is to give the admission office a system that improves accuracy, speeds up processing, and helps prevent common human errors during data entry and record management.

## ✨ Features

### 👤 Guest & Applicant Features

- View the public homepage, school information, news, events, and announcements.
- Browse available programs and course details.
- Submit admission applications through an online form.
- Upload required application documents.
- Send contact inquiries to the admission office.
- Receive email notifications for submitted applications and scheduled interviews.

### 🛡️ Admin Features

- Secure admin login and authenticated dashboard access.
- View admission dashboard statistics and application summaries.
- Review submitted applications and applicant information.
- Update application status.
- Manage program schedules, available slots, and program details.
- Schedule and synchronize interviews by program.
- Manage announcements, news, events, testimonials, faculty, and staff records.
- Review and remove contact inquiries.
- Update system settings used by the public website.
- Clear cached public content when website data changes.

### ⚙️ System Features

- Laravel MVC architecture.
- REST-style API routes for admin and public workflows.
- MySQL database with Laravel migrations.
- Laravel Sanctum authentication for protected API endpoints.
- Email templates for OTP, application, inquiry, and interview notifications.
- Tailwind CSS-powered responsive interface.
- Vite asset bundling for frontend resources.
- PHPUnit test support.

## 🛠️ Tech Stack

### Frontend

- Blade templates
- Tailwind CSS
- JavaScript
- Alpine.js
- Vite

### Backend

- PHP 8.2+
- Laravel 12
- Laravel Breeze
- Laravel Sanctum
- Laravel Mail

### Database

- MySQL
- Laravel Eloquent ORM
- Laravel database migrations and seeders

