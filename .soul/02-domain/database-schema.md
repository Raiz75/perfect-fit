---
type: schema
migrations_path: database/migrations
tables:
  - name: users
    primary_key: id
    indexes: [email (unique), church_code (unique)]
    foreign_keys: []
  - name: password_reset_tokens
    primary_key: email
    indexes: []
    foreign_keys: []
  - name: sessions
    primary_key: id
    indexes: [user_id, ip_address, last_activity]
    foreign_keys: []
  - name: cache
    primary_key: key
    indexes: [expiration]
    foreign_keys: []
  - name: cache_locks
    primary_key: key
    indexes: []
    foreign_keys: []
  - name: jobs
    primary_key: id
    indexes: [queue]
    foreign_keys: []
  - name: job_batches
    primary_key: id
    indexes: []
    foreign_keys: []
  - name: failed_jobs
    primary_key: id
    indexes: [uuid (unique)]
    foreign_keys: []
  - name: ministry_categories
    primary_key: id
    indexes: []
    foreign_keys: []
  - name: ministries
    primary_key: id
    indexes: []
    foreign_keys: [ministry_category_id -> ministry_categories]
  - name: skills
    primary_key: id
    indexes: []
    foreign_keys: []
  - name: demographic_restrictions
    primary_key: id
    indexes: [user_id, ministry_id (unique composite)]
    foreign_keys: [user_id -> users, ministry_id -> ministries]
  - name: skill_restrictions
    primary_key: id
    indexes: [user_id, ministry_id (unique composite)]
    foreign_keys: [user_id -> users, ministry_id -> ministries]
  - name: skill_questions
    primary_key: id
    indexes: [user_id, skill_id, question_number (unique composite)]
    foreign_keys: [user_id -> users, skill_id -> skills]
  - name: interest_and_passion_questions
    primary_key: id
    indexes: [user_id, ministry_category_id, question_number (unique composite)]
    foreign_keys: [user_id -> users, ministry_category_id -> ministry_categories]
  - name: behavioral_questions
    primary_key: id
    indexes: [user_id, ministry_id, question_number (unique composite)]
    foreign_keys: [user_id -> users, ministry_id -> ministries]
  - name: user_reports
    primary_key: id
    indexes: [church_code]
    foreign_keys: []
seeders_present: true
factories_present: true
last_updated: 2026-07-28
---

# Database Schema

18 tables total. 12 created by application migrations, 6 by framework defaults.

## Application Tables

### ministry_categories
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| name | string(255) | |
| created_at / updated_at | timestamp | nullable |

### ministries
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| name | string(255) | |
| ministry_category_id | bigInteger, unsigned | FK → ministry_categories.id, ON DELETE SET NULL |
| created_at / updated_at | timestamp | nullable |

### skills
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| name | string(255) | |
| created_at / updated_at | timestamp | nullable |

### demographic_restrictions
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| user_id | bigInteger, unsigned | FK → users.id, ON DELETE CASCADE |
| ministry_id | bigInteger, unsigned | FK → ministries.id, ON DELETE CASCADE |
| gender | tinyInteger | default 0 |
| age_min | integer | default 1 |
| age_max | integer | default 99 |
| marital_status | tinyInteger | default 0 |
| baptized | tinyInteger | default 2 |
| time_in_faith | tinyInteger | default 1 |
| created_at / updated_at | timestamp | nullable |
| UNIQUE | (user_id, ministry_id) | |

### skill_restrictions
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| user_id | bigInteger, unsigned | FK → users.id, ON DELETE CASCADE |
| ministry_id | bigInteger, unsigned | FK → ministries.id, ON DELETE CASCADE |
| music, technology, writing, technical, speaking, accounting, mentoring, bible_knowledge | boolean | default false |
| created_at / updated_at | timestamp | nullable |
| UNIQUE | (user_id, ministry_id) | |

### skill_questions
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| user_id | bigInteger, unsigned | FK → users.id, ON DELETE CASCADE |
| skill_id | bigInteger, unsigned | FK → skills.id, ON DELETE CASCADE |
| question_number | integer | |
| question_en | text | |
| question_tl | text | |
| created_at / updated_at | timestamp | nullable |
| UNIQUE | (user_id, skill_id, question_number) | |

### interest_and_passion_questions
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| user_id | bigInteger, unsigned | FK → users.id, ON DELETE CASCADE |
| ministry_category_id | bigInteger, unsigned | FK → ministry_categories.id, ON DELETE CASCADE |
| question_number | integer | |
| question_en | text | |
| question_tl | text | |
| created_at / updated_at | timestamp | nullable |
| UNIQUE | (user_id, ministry_category_id, question_number) | |

### behavioral_questions
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| user_id | bigInteger, unsigned | FK → users.id, ON DELETE CASCADE |
| ministry_id | bigInteger, unsigned | FK → ministries.id, ON DELETE CASCADE |
| question_number | integer | |
| question_en | text | |
| question_tl | text | |
| created_at / updated_at | timestamp | nullable |
| UNIQUE | (user_id, ministry_id, question_number) | |

### user_reports
| Column | Type | Constraints |
|---|---|---|
| id | bigInteger, autoIncrement | PK |
| church_code | string(9) | indexed |
| email | string(255) | |
| name | string(255) | |
| contact_no | string(20) | |
| music, technology, writing, technical, speaking, accounting, mentoring, bible_knowledge | boolean | |
| eligible_ministry | text | nullable |
| ai_interpretation | text | nullable |
| gender | tinyInteger | |
| age | integer | |
| marital_status | tinyInteger | |
| baptized | tinyInteger | |
| time_in_faith | tinyInteger | |
| time_of_submission | dateTime | nullable |
| created_at / updated_at | timestamp | nullable |

## Seeders & Factories
- **Seeders**: `DatabaseSeeder`, `DefaultDataSeeder`, `MinistryCategorySeeder` (6 categories), `MinistrySeeder` (29 ministries), `SkillSeeder` (8 skills)
- **Factories**: `UserFactory` only (no domain model factories exist)
- Seeder IDs are **frozen** — ministries (1-29), categories (1-6), and skills (1-8) have hardcoded IDs that must not change.

## References
- [Laravel Migrations](https://laravel.com/docs/migrations)
- [Laravel Seeders](https://laravel.com/docs/seeding)
- [Laravel Factories](https://laravel.com/docs/eloquent-factories)
- [Laravel Schema Builder](https://laravel.com/docs/migrations#creating-tables)
- [MySQL Docs](https://dev.mysql.com/doc/) | [PostgreSQL Docs](https://www.postgresql.org/docs/)
