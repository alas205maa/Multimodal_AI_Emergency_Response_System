# Multimodal AI Emergency Response System

## Project Description

> TBD — A short description of the project will be added once the project scope is confirmed.

---

## Current Project Status

**Status:** Backend Foundation Phase  
**Phase:** Initial backend implementation — Emergency Report database foundation established.

### Implemented

- Laravel backend configured with MySQL
- `EmergencyReport` model and `emergency_reports` database table
- Database migration for the emergency report schema
- Feature tests for the EmergencyReport model

### Not Implemented Yet

- API endpoints
- Authentication / authorization
- AI integration (S1/S2/S3)
- S4 Geospatial integration
- Frontend / dashboard

---

## Documentation Index

| Document | Description |
|---|---|
| [Project Scope](docs/project-scope.md) | Overview, objectives, in-scope/out-of-scope, stakeholders, constraints |
| [S5 Scope](docs/s5-scope.md) | S5-specific responsibilities, boundaries, and open questions |
| [Backend Requirements](docs/backend-requirements.md) | Functional, non-functional, security, API, database, and integration requirements |
| [Architecture](docs/architecture.md) | System overview, high-level architecture, components, data flow |
| [API](docs/api.md) | API overview, standards, authentication, endpoints, error handling |
| [Database](docs/database.md) | Database technology, schema, tables, relationships, migrations |
| [Security](docs/security.md) | Authentication, authorization, JWT, input validation, secrets management |
| [Integration & Dependencies](docs/integration-dependencies.md) | Internal/external dependencies, AI services, integration status |
| [Roadmap](docs/roadmap.md) | Project phases, milestones, timelines, status |
| [Decisions](docs/decisions.md) | Approved technical and architectural decision log |
| [Changelog](CHANGELOG.md) | Record of notable project changes |

---

## Technology Stack

| Layer | Technology |
|---|---|
| Frontend | TBD |
| Backend | Laravel 12.x (PHP 8.2) |
| Database | MySQL (via XAMPP) |
| ORM | Eloquent ORM |
| AI / ML Services | TBD |
| Authentication | TBD |
| Deployment / Infrastructure | TBD |
| CI/CD | TBD |

---

## Setup Instructions

### Prerequisites

- PHP 8.2+
- Composer 2.x
- XAMPP (Apache + MySQL)

### Installation

1. Clone the repository.
2. Navigate to `backend/`:
   ```bash
   cd backend
   ```
3. Install PHP dependencies:
   ```bash
   composer install
   ```
4. Copy the environment file (if not already present):
   ```bash
   cp .env.example .env
   ```
5. Configure `.env` with your MySQL credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=emergency_response
   DB_USERNAME=root
   DB_PASSWORD=
   ```
6. Generate the application key:
   ```bash
   php artisan key:generate
   ```
7. Create the MySQL database:
   ```sql
   CREATE DATABASE IF NOT EXISTS emergency_response CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
8. Run migrations:
   ```bash
   php artisan migrate
   ```
9. Run tests:
   ```bash
   php artisan test
   ```

---

## Contributing

> TBD — Contribution guidelines will be defined during project planning.

---

## License

> TBD
