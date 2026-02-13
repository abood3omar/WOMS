# 🛠️ Shabakat WOMS (Work Order Management System)

A full-stack internal system for managing enterprise maintenance work orders.  
This project simulates a **real-world enterprise environment**, focusing on:

- Role-Based Access Control (RBAC)
- Workflow Management
- Clean System Architecture
- Secure API Design

---

## 🚀 Overview

Shabakat WOMS is an internal maintenance management platform that allows organizations to create, manage, assign, and monitor work orders through a structured approval workflow.

The system demonstrates backend architecture best practices using Laravel and a modern reactive frontend built with Vue.js.

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
- Modern responsive interface
- Built with Tailwind CSS

---

## 🛠 Tech Stack

### Backend
- PHP 8.2
- Laravel 10
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

### 1️⃣ Clone Repository

```bash
git clone https://github.com/abood3omar/shabakat-assessment.git
cd shabakat-assessment
```

---

## 🔙 Backend Setup

Navigate to backend folder:

```bash
cd backend
```

Install dependencies:

```bash
composer install
```

Create environment file:

```bash
cp .env.example .env
```

Configure database inside `.env`:

```
DB_DATABASE=shabakat_db
DB_USERNAME=root
DB_PASSWORD=
```

Generate application key:

```bash
php artisan key:generate
```

Run migrations & seeders:

```bash
php artisan migrate:fresh --seed
```

Start backend server:

```bash
php artisan serve
```

Backend will run at:

```
http://127.0.0.1:8000
```

---

## 🎨 Frontend Setup

Open a new terminal:

```bash
cd frontend
```

Install dependencies:

```bash
npm install
```

Run development server:

```bash
npm run dev
```

Frontend will run at:

```
http://localhost:5173
```

---

## 🔑 Demo Login Credentials (Seeded Data)

| Role     | Email                    | Password | Permissions |
|----------|--------------------------|----------|-------------|
| Admin    | admin@shabakat.com       | password | Full system access |
| Manager  | manager@shabakat.com     | password | Approve/Reject & manage operations |
| Operator | operator@shabakat.com    | password | View and manage assigned orders |

---

## 🏗 Architecture Overview

### Backend Structure

- **Controllers** → Handle request & response logic
- **Middleware** → `CheckPermission.php` manages dynamic RBAC authorization
- **Models** → Define relationships and query scopes
- **Form Requests** → Dedicated validation layer
- **API Resources** → Structured JSON responses

### Database Design

The database follows **Third Normal Form (3NF)** principles.

Core tables:

- `users` — Authentication & role assignment
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

---

## 🚧 Upcoming Features (Planned Enhancements)

The system is actively evolving, and the following features are planned for future releases:

### 🔔 Real-Time Notifications
- In-app notification system for important events:
  - Work order assignment
  - Status updates
  - Approval or rejection actions
- Instant alerts to improve response time and operational awareness.

### 📋 My Orders (Technician Workspace)
A dedicated workspace for technicians to manage their assigned tasks.

**This page is dedicated for technicians to view, accept, or reject their assigned tasks.**

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
- Advanced reporting & analytics
- Mobile-friendly optimizations
- Performance and caching improvements

---

## 👨‍💻 Developer

**Abdalrhman Hamed**

Full Stack Developer (Laravel & Vue.js)

---

## 📄 License

This project is created for technical assessment and educational purposes.
