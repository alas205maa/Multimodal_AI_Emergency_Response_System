# Changelog

All notable changes to this project will be documented in this file.

This file follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) conventions.

---

## [Unreleased]

---

## [0.1.0] — 2026-09-04

### Added

- Configured Laravel backend to use MySQL (`emergency_response` database) via XAMPP.
- Created `EmergencyReport` Eloquent model (`app/Models/EmergencyReport.php`).
- Created `emergency_reports` database migration with the following fields:
  - `id` — primary key (auto-increment)
  - `description` — emergency description text
  - `image` — nullable stored image path
  - `latitude` — geographic latitude (decimal 10,8)
  - `longitude` — geographic longitude (decimal 11,8)
  - `status` — report status (default: `pending`)
  - `created_at` / `updated_at` — timestamps
- Ran all database migrations successfully (including Laravel defaults: users, cache, jobs).
- Generated application encryption key.
- Created feature tests for the EmergencyReport model (`tests/Feature/EmergencyReportTest.php`):
  - Report creation
  - Status default value
  - Nullable image field
  - Image path storage
- All 6 tests pass (4 new + 2 existing).

### Notes

- No API endpoints were created in this step.
- No AI integration was implemented.
- No authentication changes were made.
- This is the first backend implementation step — database foundation only.

---

## [0.0.1] — 2026-08-24

### Added

- Initial documentation structure established for the Multimodal AI Emergency Response System project.
- Created `README.md` as the project entry point.
- Created `docs/` directory containing the following placeholder documentation files:
  - `project-scope.md` — Project scope, objectives, stakeholders, and constraints.
  - `s5-scope.md` — S5-specific scope, responsibilities, and boundaries.
  - `backend-requirements.md` — Functional, non-functional, security, API, database, and integration requirements.
  - `architecture.md` — System architecture overview placeholder.
  - `api.md` — API documentation placeholder.
  - `database.md` — Database documentation placeholder.
  - `security.md` — Security documentation placeholder.
  - `integration-dependencies.md` — Integration and dependency documentation placeholder.
  - `roadmap.md` — Project roadmap placeholder.
  - `decisions.md` — Technical and architectural decision log.
- Created `CHANGELOG.md` (this file).

### Notes

- No application code was written.
- No dependencies were installed.
- No requirements or architecture decisions were invented.
- All unknown information is marked as TBD.
