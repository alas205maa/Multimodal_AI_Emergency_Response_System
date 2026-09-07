# Backend Requirements

> This document records the backend requirements for the Multimodal AI Emergency Response System.

---

## Functional Requirements

| ID | Requirement | Priority | Status |
|---|---|---|---|
| FR-001 | Citizens can submit emergency reports with text description, optional image, and location | High | In Progress |
| FR-002 | Emergency reports are persisted in MySQL database | High | Done |
| FR-003 | API endpoints for creating and retrieving emergency reports | High | TBD |
| FR-004 | AI service integration for text analysis (S1) | Medium | TBD |
| FR-005 | AI service integration for image analysis (S2) | Medium | TBD |
| FR-006 | Fusion/decision integration (S3) | Medium | TBD |
| FR-007 | Geospatial integration (S4) | Medium | TBD |

---

## Non-Functional Requirements

> TBD — List performance, scalability, availability, and other non-functional requirements once confirmed.

| ID | Requirement | Target | Status |
|---|---|---|---|
| NFR-001 | TBD | TBD | TBD |

---

## Security Requirements

> TBD — List specific security requirements once confirmed.  
> See also: [Security Documentation](security.md)

| ID | Requirement | Priority | Status |
|---|---|---|---|
| SR-001 | TBD | TBD | TBD |

---

## API Requirements

> TBD — List API-specific requirements once confirmed.  
> See also: [API Documentation](api.md)

| ID | Requirement | Priority | Status |
|---|---|---|---|
| AR-001 | TBD | TBD | TBD |

---

## Database Requirements

> See also: [Database Documentation](database.md)

| ID | Requirement | Priority | Status |
|---|---|---|---|
| DR-001 | Use MySQL as the primary database engine | High | Done |
| DR-002 | Use Eloquent ORM for database access | High | Done |
| DR-003 | Use Laravel migrations for schema management | High | Done |
| DR-004 | `emergency_reports` table stores citizen-submitted reports | High | Done |

---

## Integration Requirements

> TBD — List integration requirements once confirmed.  
> See also: [Integration & Dependencies](integration-dependencies.md)

| ID | Requirement | Priority | Status |
|---|---|---|---|
| IR-001 | TBD | TBD | TBD |

---

## Requirement Status

| Status Label | Meaning |
|---|---|
| TBD | Not yet defined |
| Draft | Written but not reviewed |
| Confirmed | Reviewed and approved by the team |
| In Progress | Currently being implemented |
| Done | Implemented and verified |

---

## Open Questions

> Record any unresolved questions related to backend requirements.

| # | Question | Owner | Status |
|---|---|---|---|
| 1 | Should emergency reports require user authentication? | S5 | Open |
| 2 | What image storage strategy should be used (local disk vs cloud)? | S5 | Open |
| 3 | What are the valid status transitions for emergency reports? | S5 | Open |
