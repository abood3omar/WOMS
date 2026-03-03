# ðŸ› ï¸ WOMS (Work Order Management System)

A full-stack internal system for managing enterprise maintenance work orders.  
This project simulates a **real-world enterprise environment**, focusing on:

- Role-Based Access Control (RBAC)
- Workflow Management
- Clean System Architecture
- Secure API Design

---

## ðŸš€ Overview

WOMS is an internal maintenance management platform that allows organizations to create, manage, assign, and monitor work orders through a structured approval workflow.

The system demonstrates backend architecture best practices using Laravel and a modern reactive frontend built with Vue.js.

---

## âœ¨ Key Features

### ðŸ” Advanced Security (RBAC)
- Dynamic permission system (**Modules â†’ Entities â†’ Actions**)
- Three predefined roles:
  - **Admin**
  - **Manager**
  - **Operator**
- Middleware-based authorization for all API routes
- Token-based authentication using Laravel Sanctum

### ðŸ”§ Work Order Management
- Create, assign, edit, and track maintenance tickets
- Enforced workflow lifecycle:

```
Pending â†’ Approved â†’ In Progress â†’ Completed / Rejected
```

- Role-specific actions and permissions

### ðŸ“Š Interactive Dashboard
- Real-time statistics
- Visual charts and summaries
- Recent activity monitoring

### ðŸ“œ Activity Logging
- Full audit trail for system actions:
  - Create
  - Update
  - Delete
  - Login events

### ðŸ“± Responsive UI
- Modern responsive interface
- Built with Tailwind CSS

---

## ðŸ›  Tech Stack

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

## âš™ï¸ Installation Guide (Local Setup)

### 1ï¸âƒ£ Clone Repository

```bash
git clone https://github.com/abood3omar/woms-assessment.git
cd woms-assessment
```

---

## ðŸ”™ Backend Setup

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
DB_DATABASE=woms_db
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

## ðŸŽ¨ Frontend Setup

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

## ðŸ”‘ Demo Login Credentials (Seeded Data)

| Role     | Email                    | Password | Permissions |
|----------|--------------------------|----------|-------------|
| Admin    | admin@woms.com       | password | Full system access |
| Manager  | manager@woms.com     | password | Approve/Reject & manage operations |
| Operator | operator@woms.com    | password | View and manage assigned orders |

---

## ðŸ— Architecture Overview

### Backend Structure

- **Controllers** â†’ Handle request & response logic
- **Middleware** â†’ `CheckPermission.php` manages dynamic RBAC authorization
- **Models** â†’ Define relationships and query scopes
- **Form Requests** â†’ Dedicated validation layer
- **API Resources** â†’ Structured JSON responses

### Database Design

The database follows **Third Normal Form (3NF)** principles.

Core tables:

- `users` â€” Authentication & role assignment
- `roles` / `permissions` â€” Dynamic access control system
- `work_orders` â€” Main business entity
- `activity_logs` â€” System audit trail

---

## ðŸ§  System Design Concepts Demonstrated

- Clean layered architecture
- RESTful API design
- Role-based authorization
- Workflow state management
- Audit logging
- Separation of concerns

---

---

## ðŸš§ Upcoming Features (Planned Enhancements)

The system is actively evolving, and the following features are planned for future releases:

### ðŸ”” Real-Time Notifications
- In-app notification system for important events:
  - Work order assignment
  - Status updates
  - Approval or rejection actions
- Instant alerts to improve response time and operational awareness.

### ðŸ“‹ My Orders (Technician Workspace)
A dedicated workspace for technicians to manage their assigned tasks.

**This page is dedicated for technicians to view, accept, or reject their assigned tasks.**

Features include:
- View personally assigned work orders
- Accept or reject incoming assignments
- Track task progress
- Update work order status directly
- Improved accountability and task ownership

### âš¡ Workflow Improvements
- Automatic assignment notifications
- Status transition validation enhancements
- Role-based action visibility improvements

### ðŸ“ˆ Future Enhancements
- Email notifications integration
- Real-time updates using WebSockets
- Advanced reporting & analytics
- Mobile-friendly optimizations
- Performance and caching improvements

---

## ðŸ‘¨â€ðŸ’» Developer

**Abdalrhman Hamed**

Full Stack Developer (Laravel & Vue.js)

---

## ðŸ“„ License

This project is created for technical assessment and educational purposes.


