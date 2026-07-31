# 🛠️ WOMS — Work Order Management System

A full-stack internal system for managing enterprise maintenance work orders.

This project simulates a **real-world enterprise environment**, focusing on:

- Role-Based Access Control (RBAC)
- Workflow Management
- Clean System Architecture
- Secure API Design

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?logo=vue.js&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-Educational-blue)

---

## 📑 Table of Contents

- [Overview](#-overview)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Installation Guide](#️-installation-guide-local-setup)
- [Demo Login Credentials](#-demo-login-credentials-seeded-data)
- [Architecture Overview](#-architecture-overview)
- [System Design Concepts Demonstrated](#-system-design-concepts-demonstrated)
- [Upcoming Features](#-upcoming-features-planned-enhancements)
- [Developer](#-developer)
- [License](#-license)

---

## 🚀 Overview

WOMS is an internal maintenance management platform that allows organizations to create, manage, assign, and monitor work orders through a structured approval workflow.

The system demonstrates backend architecture best practices using Laravel, paired with a modern, reactive frontend built with Vue.js.

---

## ✨ Key Features

### 🔐 Advanced Security (RBAC)
- Dynamic permission system (**Modules → Entities → Actions**)
- Three predefined roles:
  - **Admin**
  - **Manager**
  - **Operator**
- Middleware-based authorization for all API routes
- Token-based authentication using Laravel Sanctum

### 🔧 Work Order Management
- Create, assign, edit, and track maintenance tickets
- Enforced workflow lifecycle:

  ```
  Pending → Approved → In Progress → Completed / Rejected
  ```

- Role-specific actions and permissions

### 📊 Interactive Dashboard
- Real-time statistics
- Visual charts and summaries
- Recent activity monitoring

### 📜 Activity Logging
- Full audit trail for system actions:
  - Create
  - Update
  - Delete
  - Login events

### 📱 Responsive UI
- Modern, responsive interface
- Built with Tailwind CSS

---

## 🛠 Tech Stack

### Backend
- PHP 8.2
- Laravel 12
- MySQL

### Frontend
- Vue.js 3 (Composition API)
- Vite
- Pinia (State Management)

### Styling & Auth
- Tailwind CSS
- Laravel Sanctum (Token Authentication)

---

## ⚙️ Installation Guide (Local Setup)

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/abood3omar/woms-assessment.git
cd woms-assessment
```

### 2️⃣ Backend Setup

Navigate to the backend folder:

```bash
cd backend
```

Install dependencies:

```bash
composer install
```

Create the environment file:

```bash
cp .env.example .env
```

Configure the database inside `.env`:

```env
DB_DATABASE=woms_db
DB_USERNAME=root
DB_PASSWORD=
```

Generate the application key:

```bash
php artisan key:generate
```

Run migrations and seeders:

```bash
php artisan migrate:fresh --seed
```

Start the backend server:

```bash
php artisan serve
```

The backend will run at:

```
http://127.0.0.1:8000
```

### 3️⃣ Frontend Setup

Open a new terminal:

```bash
cd frontend
```

Install dependencies:

```bash
npm install
```

Run the development server:

```bash
npm run dev
```

The frontend will run at:

```
http://localhost:5173
```

---

## 🔑 Demo Login Credentials (Seeded Data)

| Role     | Email                 | Password | Permissions                          |
|----------|------------------------|----------|---------------------------------------|
| Admin    | admin@woms.com         | password | Full system access                    |
| Manager  | manager@woms.com       | password | Approve/reject and manage operations  |
| Operator | operator@woms.com      | password | View and manage assigned orders       |

> ⚠️ These credentials are for local development and demo purposes only. Do not use them in production.

---

## 🏗 Architecture Overview

### Backend Structure

- **Controllers** — Handle request and response logic
- **Middleware** — `CheckPermission.php` manages dynamic RBAC authorization
- **Models** — Define relationships and query scopes
- **Form Requests** — Dedicated validation layer
- **API Resources** — Structured JSON responses

### Database Design

The database follows **Third Normal Form (3NF)** principles.

Core tables:

- `users` — Authentication and role assignment
- `roles` / `permissions` — Dynamic access control system
- `work_orders` — Main business entity
- `activity_logs` — System audit trail

---

## 🧠 System Design Concepts Demonstrated

- Clean layered architecture
- RESTful API design
- Role-based authorization
- Workflow state management
- Audit logging
- Separation of concerns

---

## 🚧 Upcoming Features (Planned Enhancements)

The system is actively evolving. The following features are planned for future releases:

### 🔔 Real-Time Notifications
- In-app notification system for important events:
  - Work order assignment
  - Status updates
  - Approval or rejection actions
- Instant alerts to improve response time and operational awareness

### 📋 My Orders (Technician Workspace)
A dedicated workspace for technicians to manage their assigned tasks.

Features include:
- View personally assigned work orders
- Accept or reject incoming assignments
- Track task progress
- Update work order status directly
- Improved accountability and task ownership

### ⚡ Workflow Improvements
- Automatic assignment notifications
- Status transition validation enhancements
- Role-based action visibility improvements

### 📈 Future Enhancements
- Email notifications integration
- Real-time updates using WebSockets
- Advanced reporting and analytics
- Mobile-friendly optimizations
- Performance and caching improvements

---

## 👨‍💻 Developer

**Abdalrhman Hamed**
Full Stack Developer (Laravel & Vue.js)

---
