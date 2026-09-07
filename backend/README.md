# Backend — Multimodal AI Emergency Response System

## Overview

This is the Laravel backend for the Multimodal AI Emergency Response System.

**Role:** S5 — Backend Development & Integration

---

## Technology

| Property | Value |
|---|---|
| Framework | Laravel 12.x |
| Language | PHP 8.2 |
| Database | MySQL (via XAMPP) |
| ORM | Eloquent |
| Testing | PHPUnit 11.x |

---

## Current Implementation Status

### Implemented

| Component | File | Status |
|---|---|---|
| EmergencyReport Model | `app/Models/EmergencyReport.php` | ✅ Done |
| emergency_reports Migration | `database/migrations/2026_09_04_075449_create_emergency_reports_table.php` | ✅ Done |
| EmergencyReport Tests | `tests/Feature/EmergencyReportTest.php` | ✅ Done |

### Not Implemented Yet

- Emergency Report API endpoints (controller, routes, validation)
- Authentication / authorization
- AI service integration (S1, S2, S3)
- Geospatial integration (S4)
- Image upload handling
- Report status workflow

---

## Database

### emergency_reports Table

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | bigint unsigned (PK) | No | auto-increment | Primary key |
| `description` | text | No | — | Emergency description text |
| `image` | varchar(255) | Yes | NULL | Stored image path |
| `latitude` | decimal(10,8) | No | — | Geographic latitude |
| `longitude` | decimal(11,8) | No | — | Geographic longitude |
| `status` | varchar(255) | No | `pending` | Report status |
| `created_at` | timestamp | Yes | NULL | Record creation time |
| `updated_at` | timestamp | Yes | NULL | Record update time |

---

## Commands

```bash
# Run migrations
php artisan migrate

# Check migration status
php artisan migrate:status

# Run tests
php artisan test

# Start development server
php artisan serve
```

---

## Project Structure

```
backend/
├── app/
│   ├── Http/Controllers/
│   └── Models/
│       ├── User.php
│       └── EmergencyReport.php
├── database/
│   └── migrations/
│       ├── 0001_01_01_000000_create_users_table.php
│       ├── 0001_01_01_000001_create_cache_table.php
│       ├── 0001_01_01_000002_create_jobs_table.php
│       └── 2026_09_04_075449_create_emergency_reports_table.php
├── routes/
│   └── web.php
├── tests/
│   ├── Feature/
│   │   ├── EmergencyReportTest.php
│   │   └── ExampleTest.php
│   └── Unit/
│       └── ExampleTest.php
├── .env
├── composer.json
└── phpunit.xml
```
