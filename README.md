# MX100 Job Portal API

A RESTful API built with **Laravel** that connects Companies (Employers) with Expert Freelancers. This project focuses on separation of concerns, secure authentication, and strict validation logic.

## Features

-   **Authentication:** Role-based registration and login (Employer vs. Freelancer) using Laravel Sanctum.
-   **Job Management:** Employers can create, edit, and publish job postings.
-   **Draft System:** Jobs can be saved as 'draft' (hidden) or 'published' (visible).
-   **Application System:** Freelancers can apply to published jobs by uploading a CV (PDF/Doc).
-   **Strict Logic:** Enforces a "One CV per Job" rule for freelancers.
-   **Architecture:** Implements **Service-Repository Pattern** for clean, maintainable code.

## Tech Stack

-   **Framework:** PHP - Laravel 11+
-   **Database:** MySQL
-   **Auth:** Laravel Sanctum (Bearer Token)
-   **Architecture:** MVC + Service-Repository Layer

## Project Structure (Service-Repository)

This project avoids putting business logic in Controllers.

-   **Controllers:** Handle HTTP requests and responses.
-   **Services:** Handle business logic, validation rules, and authorization checks.
-   **Repositories:** Handle direct database queries (Eloquent).

<!-- end list -->

```text
app/
├── Http/Controllers/   # Entry point
├── Services/           # Business Logic (e.g., JobPostService)
├── Repositories/       # Database Logic (e.g., JobPostRepository)
└── Models/             # Eloquent Models
```

---

## Installation & Setup

Follow these steps to run the application locally.

### 1\. Clone & Install Dependencies

```bash
git clone <repository_url>
cd mx100-api
composer install
```

### 2\. Environment Configuration

Copy the `.env.example` file and configure your database settings.

```bash
cp .env.example .env
```

Open `.env` and set your database credentials:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

### 3\. Generate Key & Run Migrations

```bash
php artisan key:generate
php artisan migrate
```

### 4\. Link Storage (Crucial for CV Uploads)

Since this API handles file uploads, you must link the storage folder.

```bash
php artisan storage:link
```

### 5\. Run the Server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`.

---

## 🔌 API Endpoints Documentation

**Important Header:**
All requests should have the header: `Accept: application/json`

### 1\. Authentication

| Method | Endpoint        | Description                                          | Auth Required |
| :----- | :-------------- | :--------------------------------------------------- | :------------ |
| `POST` | `/api/register` | Register new user. Role: `employer` or `freelancer`. | No            |
| `POST` | `/api/login`    | Login and receive `access_token`.                    | No            |
| `POST` | `/api/logout`   | Revoke token.                                        | Yes           |

**Sample Register Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "role": "employer"
}
```

### 2\. Jobs (Employer)

| Method | Endpoint                      | Description                                   | Auth Required  |
| :----- | :---------------------------- | :-------------------------------------------- | :------------- |
| `POST` | `/api/jobs`                   | Create a job. Status: `draft` or `published`. | Yes (Employer) |
| `PUT`  | `/api/jobs/{id}`              | Update job details.                           | Yes (Employer) |
| `GET`  | `/api/jobs/{id}/applications` | View all applicants and CV links.             | Yes (Owner)    |

**Sample Job Body:**

```json
{
    "title": "Backend Developer",
    "description": "Expert in Laravel needed.",
    "status": "published"
}
```

### 3\. Applications (Freelancer)

| Method | Endpoint            | Description                   | Auth Required    |
| :----- | :------------------ | :---------------------------- | :--------------- |
| `GET`  | `/api/jobs`         | List **only** published jobs. | Yes/No\*         |
| `POST` | `/api/applications` | Apply for a job (Upload CV).  | Yes (Freelancer) |

**Application Rules:**

-   **Body Type:** `form-data`
-   **Fields:** `job_post_id` (text), `cv_file` (file).
-   **Constraint:** A freelancer cannot apply twice for the same `job_post_id`.

---

### Author

**Aprilian Adha Eka Pasha**

-   Technical Test for Kopnuspos.

---
