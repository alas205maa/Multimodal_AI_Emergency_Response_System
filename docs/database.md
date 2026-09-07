# Database Documentation

> This document describes the database design for the Multimodal AI Emergency Response System.

---

## Database Overview

The system uses MySQL as its primary database, managed through Laravel's Eloquent ORM and migration system. The initial database foundation provides persistence for emergency reports submitted by citizens.

---

## Database Technology

| Property | Value |
|---|---|
| Database Engine | MySQL (via XAMPP) |
| Version | XAMPP bundled MySQL |
| Hosting / Provider | Local (XAMPP) |
| Connection Method | TCP/IP (127.0.0.1:3306) |
| ORM / Query Builder | Eloquent ORM (Laravel 12.x) |
| Database Name | `emergency_response` |
| Character Set | utf8mb4 |
| Collation | utf8mb4_unicode_ci |

---

## Schema

```
emergency_response (database)
├── emergency_reports
├── users (Laravel default)
├── cache (Laravel default)
├── cache_locks (Laravel default)
├── sessions (Laravel default)
├── jobs (Laravel default)
├── job_batches (Laravel default)
├── failed_jobs (Laravel default)
├── password_reset_tokens (Laravel default)
└── migrations (Laravel internal)
```

---

## Tables

| Table Name | Description | Status |
|---|---|---|
| `emergency_reports` | Stores emergency reports submitted by citizens | ✅ Implemented |
| `users` | Laravel default user table | ✅ Migrated (Laravel default) |
| `cache` / `cache_locks` | Laravel cache tables | ✅ Migrated (Laravel default) |
| `sessions` | Laravel session table | ✅ Migrated (Laravel default) |
| `jobs` / `job_batches` / `failed_jobs` | Laravel queue tables | ✅ Migrated (Laravel default) |
| `password_reset_tokens` | Laravel password reset table | ✅ Migrated (Laravel default) |

### emergency_reports

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | bigint unsigned (PK) | No | auto-increment | Primary key |
| `description` | text | No | — | Emergency description text submitted by citizen |
| `image` | varchar(255) | Yes | NULL | Path to stored emergency image |
| `latitude` | decimal(10,8) | No | — | Geographic latitude of the emergency |
| `longitude` | decimal(11,8) | No | — | Geographic longitude of the emergency |
| `status` | varchar(255) | No | `pending` | Current report status |
| `created_at` | timestamp | Yes | NULL | Record creation time |
| `updated_at` | timestamp | Yes | NULL | Record update time |

#### Planned Future Fields (Not Implemented)

> The following fields may be added in future phases when AI integration (S1/S2/S3) is implemented:
>
> - AI-derived emergency type
> - Risk/severity level
> - AI confidence score
> - Fusion decision result
> - User/citizen foreign key

---

## Relationships

> No relationships have been defined yet.  
> Future phases may add:
> - `emergency_reports` → `users` (citizen who submitted the report)
> - Additional tables for AI results, emergency centers, response assignments

---

## Indexes

> TBD — Index strategy will be defined as query patterns become clear.

| Table | Column(s) | Index Type | Purpose |
|---|---|---|---|
| `emergency_reports` | `id` | Primary | Primary key (auto-created) |

---

## Migrations

- Migration Tool: Laravel Migrations (`php artisan migrate`)
- Migration Strategy: Incremental, versioned migration files
- Rollback Strategy: `php artisan migrate:rollback`

### Migration Files

| File | Table | Status |
|---|---|---|
| `0001_01_01_000000_create_users_table.php` | users, password_reset_tokens, sessions | ✅ Ran |
| `0001_01_01_000001_create_cache_table.php` | cache, cache_locks | ✅ Ran |
| `0001_01_01_000002_create_jobs_table.php` | jobs, job_batches, failed_jobs | ✅ Ran |
| `2026_09_04_075449_create_emergency_reports_table.php` | emergency_reports | ✅ Ran |

---

## Data Security

> TBD — Data security requirements and implementation approach will be documented here once confirmed.  
> See also: [Security Documentation](security.md)

- Encryption at Rest: TBD
- Encryption in Transit: TBD
- PII Handling: TBD
- Data Retention Policy: TBD
