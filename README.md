<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


# Lavanderie – Laundry Appointment Management System

A web-based laundry appointment and management system designed to make laundry services more convenient for customers and easier to manage for administrators, staff, and delivery personnel.

## About the Project

Lavanderie is a Laravel-based Laundry Appointment Management System that allows customers to book laundry services online while providing an organized platform for managing appointments, laundry operations, payments, inventory, and deliveries.

The system supports both online bookings and walk-in transactions, helping streamline daily laundry shop operations.

## Features

### Customer
- Online laundry appointment booking
- View and manage bookings
- Track laundry order status
- View booking details
- Select available laundry services
- Pickup and delivery options

### Administrator
- Admin dashboard
- Manage customer orders and bookings
- Manage customers and staff
- Manage laundry services
- Monitor payments
- Inventory management
- Reports and expense management
- Manage walk-in transactions

### Staff
- Staff dashboard
- View and manage assigned orders
- Process laundry orders
- Update laundry processing statuses
- Manage walk-in transactions
- Assign delivery personnel
- Manage inventory

### Delivery Personnel
- Delivery dashboard
- View assigned deliveries
- Track delivery information
- Update delivery statuses
- View delivery history
- Manage delivery profile

## Technology Stack

| Technology | Purpose |
|---|---|
| PHP | Backend Programming |
| Laravel | Web Application Framework |
| Blade | Frontend Templating |
| Tailwind CSS | UI Styling |
| JavaScript | Frontend Interactions |
| Vite | Asset Bundling |
| MYSQL | Database Management and Data Storage |
| PayPal Sandbox | Test Payment Integration |

## System Workflow

1. Customers create an account and book a laundry appointment.
2. Administrators review online bookings and assign staff.
3. Staff process laundry orders and update their statuses.
4. Staff assign delivery personnel when delivery is required.
5. Delivery personnel handle assigned deliveries and update delivery statuses.
6. Administrators monitor orders, payments, inventory, and business records.

Walk-in transactions can also be recorded directly by staff and reflected in the management system.

## Installation and Setup

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js and npm
- Compatible database
- Git

### 1. Clone the Repository

```bash
git clone https://github.com/Syntaxdevv/Laundry-Appointment-Management-System.git
```

### 2. Navigate to the Project Directory

```bash
cd Laundry-Appointment-Management-System
```

### 3. Install PHP Dependencies

```bash
composer install
```

### 4. Install Frontend Dependencies

```bash
npm install
```

### 5. Configure Environment Variables

```bash
copy .env.example .env
```

Configure your database credentials and other required environment variables inside the `.env` file.

### 6. Generate Application Key

```bash
php artisan key:generate
```

### 7. Run Database Migrations and Seeders

```bash
php artisan migrate --seed
```

### 8. Build Frontend Assets

```bash
npm run build
```

### 9. Start the Development Server

```bash
php artisan serve
```

Open your browser and visit:

http://127.0.0.1:8000

## Payment Integration

Lavanderie includes PayPal Sandbox integration for testing payment transactions.

**Note:** The payment integration is configured for sandbox testing and does not process real payments.

## Project Limitations

- Payment transactions use the PayPal Sandbox environment.
- SMS notifications are not currently implemented.
- Laundry processing statuses are manually updated by staff.
- An internet connection is required to access the application.
- The system is designed for a single laundry shop branch.

---


## 📄 License

This project and its original source code are protected by applicable copyright laws. Unauthorized copying, reproduction, modification, distribution, or use of this project without permission from the rightful owner is prohibited.
