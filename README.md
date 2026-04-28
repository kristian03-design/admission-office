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

### Tools & Libraries

- Composer
- npm
- Axios
- PHPUnit
- Laravel Pint
- Laravel Tinker
- Concurrently
- XAMPP or any PHP/MySQL local server stack

## 📷 Screenshots

> Replace these placeholders with actual screenshots before publishing the repository.

### 🏠 Public Homepage

![Homepage Screenshot](public/screenshots/homepage-placeholder.png)

### 📝 Application Form

![Application Form Screenshot](public/screenshots/application-form-placeholder.png)

### 📊 Admin Dashboard

![Admin Dashboard Screenshot](public/screenshots/admin-dashboard-placeholder.png)

### 🗓️ Interview Management

![Interview Management Screenshot](public/screenshots/interview-management-placeholder.png)

## ⚙️ Installation Guide

### Prerequisites

Make sure the following are installed on your machine:

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL
- Git
- XAMPP, Laragon, Laravel Herd, or another local PHP environment

### 1. Clone the Repository

```bash
cd btech-admission-office
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

### 4. Create the Environment File

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

### 5. Generate the Application Key

```bash
php artisan key:generate
```

### 6. Configure the Database

Create a MySQL database, then update your `.env` file:

```env
APP_NAME="BTECH Admission Office"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=btech_admission_office
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Configure Mail Settings

Update the mail section in `.env` based on your provider:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="BTECH Admission Office"
```

For local testing, you may use Mailpit, Mailtrap, or Laravel log mail.

### 8. Run Migrations and Seeders

```bash
php artisan migrate
php artisan db:seed
```

If the project includes a specific admin seeder, you can also run:

```bash
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=ProgramSeeder
```

### 9. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 10. Start the Local Server

```bash
php artisan serve
```

Visit the application at:

```text
http://127.0.0.1:8000
```

## 🚀 Usage Guide

### Guest Workflow

1. Open the public website.
2. Browse school information, programs, announcements, news, and events.
3. Go to the application page.
4. Fill out the admission form.
5. Upload required documents if requested.
6. Submit the application.
7. Wait for status updates or interview notifications from the admission office.

### Admin Workflow

1. Visit the admin login page:

```text
/admin/login
```

2. Sign in using an authorized admin account.
3. Open the admin dashboard.
4. Review submitted applications.
5. Update application status as needed.
6. Manage program schedules and available slots.
7. Schedule interviews for applicants.
8. Publish or update announcements, news, events, testimonials, faculty, and staff content.
9. Monitor inquiries submitted by guests.

### Common Local Development Commands

```bash
php artisan serve
npm run dev
php artisan migrate
php artisan db:seed
php artisan test
php artisan optimize:clear
```

## 🗂️ Project Structure

```text
btech-admission-office/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Web and API controllers
│   │   └── Requests/           # Form request validation
│   ├── Mail/                   # Email notification classes
│   ├── Models/                 # Eloquent models
│   └── Providers/              # Application service providers
├── bootstrap/                  # Laravel bootstrap files
├── config/                     # Laravel configuration files
├── database/
│   ├── migrations/             # Database schema definitions
│   ├── seeders/                # Initial data seeders
│   └── factories/              # Model factories for testing
├── public/
│   ├── assets/                 # Public images and static assets
│   ├── css/                    # Public CSS files
│   ├── js/                     # Public JavaScript files
│   └── index.php               # Public entry point
├── resources/
│   ├── css/                    # Source CSS
│   ├── js/                     # Source JavaScript
│   └── views/                  # Blade views and email templates
├── routes/
│   ├── api.php                 # API routes
│   ├── auth.php                # Authentication routes
│   ├── console.php             # Console routes
│   └── web.php                 # Web routes
├── storage/                    # Logs, cache, sessions, and uploaded files
├── tests/                      # Feature and unit tests
├── composer.json               # PHP dependencies and scripts
├── package.json                # JavaScript dependencies and scripts
└── vite.config.js              # Vite configuration
```

## 🔐 Authentication Flow

The system uses Laravel authentication features with protected admin routes.

1. Admin visits `/admin/login`.
2. Login credentials are submitted to the Laravel authentication controller.
3. Laravel validates the credentials.
4. If the account requires verification, the verified middleware protects dashboard access.
5. Authenticated admins can access `/dashboard` or `/admin/dashboard`.
6. Protected API routes use Laravel Sanctum authentication.
7. Admins may update their password through authenticated API endpoints.
8. OTP and password reset email templates are available for account verification and recovery workflows.

Guest users do not need to log in to browse public pages or submit public inquiries and applications.

## 💳 Payment Integration

Payment integration is not currently included in this project.

If future admission workflows require application fees, the system can be extended with payment providers such as:

- Stripe
- PayMongo
- PayPal
- GCash/Maya through a supported payment gateway

Recommended future payment logic:

- Store payment transactions in a dedicated `payments` table.
- Link each payment to an application record.
- Use webhook verification for payment status updates.
- Prevent manual approval unless the payment is confirmed.
- Provide downloadable receipts for applicants and admins.

## 📊 Database Design

The database is designed around admission office operations and public content management.

### Key Tables

- `users` - Stores admin user accounts, authentication details, OTP fields, and verification data.
- `programs` - Stores available programs, descriptions, schedules, interview settings, and slot information.
- `applications` - Stores applicant personal information, academic details, selected program, status, and related admission data.
- `interviews` - Stores interview schedules and program-based interview assignments.
- `announcements` - Stores public announcement content.
- `news_events` - Stores news and event records with image support.
- `contact_inquiries` - Stores messages submitted from the public contact form.
- `testimonials` - Stores testimonial content shown on the public website.
- `system_settings` - Stores configurable website and admission office settings.
- `personal_access_tokens` - Stores Laravel Sanctum API tokens.
- `cache` - Stores Laravel cache data.

## 🌐 Deployment Guide

This application is a Laravel project and should be deployed to a PHP-capable hosting platform.

### Recommended Hosting Options

- VPS hosting with Nginx or Apache
- Shared hosting with PHP 8.2+ and MySQL support
- Render
- Railway
- DigitalOcean
- Laravel Forge
- cPanel-based hosting

> Vercel is best suited for static sites and serverless frontend projects. For this Laravel application, use a PHP-compatible platform unless the backend is deployed separately.

### Production Deployment Steps

1. Upload or clone the project to the server.
2. Install backend dependencies:

```bash
composer install --optimize-autoloader --no-dev
```

3. Install and build frontend assets:

```bash
npm install
npm run build
```

4. Create and configure the production `.env` file:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

5. Generate the application key if needed:

```bash
php artisan key:generate
```

6. Run migrations:

```bash
php artisan migrate --force
```

7. Optimize the application:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

8. Point the web server document root to:

```text
public/
```

9. Set correct permissions for:

```text
storage/
bootstrap/cache/
```

10. Configure cron for Laravel scheduled tasks if needed:

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

## 🧪 Testing

Run the automated test suite:

```bash
php artisan test
```

Run PHPUnit directly:

```bash
vendor/bin/phpunit
```

Clear configuration before testing if cached config causes issues:

```bash
php artisan config:clear
php artisan cache:clear
```

Recommended testing areas:

- Public application submission
- Admin login and dashboard access
- Application status updates
- Program slot updates
- Interview scheduling
- Public content management
- Email notification delivery
- Validation for required applicant fields

## 📈 Future Improvements

- Add role-based permissions for super admins, admission staff, and reviewers.
- Add applicant accounts so students can track application status online.
- Add payment integration for application fees.
- Add document review and approval status per uploaded file.
- Add export features for PDF, Excel, and CSV reports.
- Add audit logs for admin actions.
- Add SMS notifications for interview schedules and application updates.
- Add analytics for application trends by program, date, and status.
- Add full-text search and advanced filters for applicant records.
- Add automated backup and restore workflows.
- Add accessibility and localization improvements.

## 🤝 Contributing

Contributions are welcome for improving reliability, user experience, documentation, and maintainability.

### Contribution Guidelines

1. Fork the repository.
2. Create a feature branch:

```bash
git checkout -b feature/your-feature-name
```

3. Make your changes.
4. Run tests and formatting checks.
5. Commit your changes:

```bash
git commit -m "Add your feature description"
```

6. Push your branch:

```bash
git push origin feature/your-feature-name
```

7. Open a pull request with a clear description of your changes.

### Code Quality Notes

- Follow Laravel naming conventions.
- Keep controllers focused and readable.
- Use validation for incoming requests.
- Avoid committing `.env`, logs, cache files, or local machine configuration.
- Add tests for important business logic and admission workflows.

## 📄 License

This project is open-source and may be used for educational and portfolio purposes.

If you plan to use this system in production, review the license, security, privacy, and data protection requirements of your organization before deployment.
