# 🎯 Lucky Draw Management System

A modern full-stack Lucky Draw Management Platform built with Laravel, designed for event organizers, businesses, and campaigns to manage participants, generate winners, track gifts, and automate lucky draw workflows.

---

# 🚀 Project Highlights

- Digital Lucky Draw System
- Participant Management
- Winner Selection Automation
- Gift Distribution Tracking
- Admin Dashboard
- User Management System
- Real-Time Dashboard Statistics
- Responsive Modern UI

---

# 🖼️ Project Screenshots

## 📊 Dashboard Statistics

<p align="center">
  <img src="screenshots/Dashboard%20stats.png" width="800"/>
</p>

---

## 👥 User Management

<p align="center">
  <img src="screenshots/User%20Management.png" width="800"/>
</p>

---

## 🎁 Winning Gift List

<p align="center">
  <img src="screenshots/Winning%20Gift%20List.png" width="800"/>
</p>

---

## 📋 Participant List

<p align="center">
  <img src="screenshots/Participant%20list.png" width="800"/>
</p>

---

## 🏆 Winner List

<p align="center">
  <img src="screenshots/Winner%20list.png" width="800"/>
</p>

---

## ✏️ Participant Edit Module

<p align="center">
  <img src="screenshots/Participant%20Edit.png" width="800"/>
</p>

---

# 🔎 Project Overview

This platform is designed to simplify lucky draw event management through a centralized digital system.

The application supports:

- Participant registration & tracking
- Automated winner generation
- Gift allocation management
- Admin dashboard analytics
- User & role management
- Event-based lucky draw workflows

---

# ✨ Key Features

## 🎯 Lucky Draw Features
- Random Winner Selection
- Gift Allocation System
- Winner Tracking
- Participant Management
- Event-Based Draw Management

## 👨‍💼 Admin Features
- Dashboard Analytics
- User Management
- Participant Edit System
- Draw History Tracking
- Winner Management

## 📊 Management Features
- Dashboard Statistics
- Real-Time Data Tables
- Search & Filtering
- CRUD Operations
- Responsive Data Management

---

# 🛠️ Technology Stack

## Backend
- Laravel
- PHP 8.2+
- REST APIs

## Frontend
- Blade Templates
- Tailwind CSS
- JavaScript
- Axios
- Vite

## Database
- MySQL

## Authentication
- Laravel Authentication

---

# 📦 Installation

## Clone Repository

```bash
git clone https://github.com/Sujeetatwe/digital-lucky-draw-platform.git
cd digital-lucky-draw-platform
```

---

## Install Dependencies

```bash
composer install
npm install
```

---

## Configure Environment

```bash
cp .env.example .env
```

Update database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lucky_draw
DB_USERNAME=root
DB_PASSWORD=
```

---

## Generate Application Key

```bash
php artisan key:generate
```

---

## Run Database Migrations

```bash
php artisan migrate --seed
```

---

## Build Frontend Assets

```bash
npm run build
```

---

## Start Development Server

```bash
php artisan serve
```

---

# 🌐 Application Access

```text
http://127.0.0.1:8000
```

---

# 📊 Core Modules

- Participant Management
- Winner Selection System
- Gift Distribution
- User Management
- Dashboard Analytics
- Reporting System

---

# 🔐 Security Features

- Authentication System
- Role-Based Access
- Secure Form Validation
- Protected Admin Routes
- Session Management

---

# 📁 Project Structure

```text
app/                    → Controllers & Models
resources/views/        → Blade Templates
routes/                 → Web & API Routes
database/               → Migrations & Seeders
public/                 → Public Assets
storage/                → Uploaded Files
tests/                  → PHPUnit Tests
```

---

# 📈 System Workflow

```text
Participant Registration
          ↓
Gift Assignment
          ↓
Lucky Draw Execution
          ↓
Winner Selection
          ↓
Result Management
          ↓
Dashboard Analytics
```

---

# 🧪 Testing

Run Laravel tests:

```bash
php artisan test
```

---

# 🚀 Deployment Notes

Optimize application before deployment:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

Ensure permissions:

```text
storage/
bootstrap/cache/
```

---

# 📄 License

This project is licensed under the MIT License.

---

# 👨‍💻 Author

## Sujeet Atwe

Full Stack & AI Developer focused on scalable enterprise systems, business automation platforms, and modern web applications.

---

# ⭐ Support

If you like this project, give it a ⭐ on GitHub.
