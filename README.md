# Laravel Authentication System (Jetstream & Fortify)

This project implements a secure authentication system in Laravel using **Jetstream** and **Fortify**. It supports user registration, login, email verification, password reset, and optional two-factor authentication (2FA).

## 🚀 Features

- **User Authentication** (Login, Logout, Registration)
- **Email Verification** (Customized verification emails)
- **Password Reset**
- **Two-Factor Authentication (2FA)**
- **Session Management**
- **Social Authentication (Optional)**
- **Livewire Components for Authentication UI**

## 🛠️ Tech Stack

- Laravel 12
- Jetstream (Livewire)
- Fortify
- Tailwind CSS version 4
- Mailtrap (for email testing)
- MySQL / SQLite / PostgreSQL

## 📌 Installation

### 1️⃣ Clone the Repository

git clone https://github.com/macqueen006/auth-livewire.git

composer install

npm install && npm run build

cp .env.example .env

Update the .env file with your database and email settings:
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="your@example.com"
MAIL_FROM_NAME="Your App Name"

## Generate App Key & Migrate Database
php artisan key:generate
php artisan migrate
php artisan serve

🔑 Authentication Flow

Users can register with their email.
After registration, a verification email is sent.
Once verified, users can log in.
If 2FA is enabled, they must enter a one-time code.
Password reset is available for forgotten passwords.
Users can manage sessions and authentication settings in their profile.

all settings to customize the authentication
- config/language.php
- config/settings.php
- config/appearance.php

🛡️ Security Enhancements

Rate limiting for authentication routes (ThrottleRequests)
Password strength validation
Enforced email verification

📜 License

This project is licensed under the MIT License.
Enjoy!!!
