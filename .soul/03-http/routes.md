---
type: routes
files:
  - routes/web.php
route_groups:
  - prefix: /admin (protected)
    middleware: [admin]
route_count: ~40
last_updated: 2026-07-28
---

# Routes

No API routes exist. All routes are web routes in `routes/web.php`. Console routes in `routes/console.php` contain only the default `inspire` command.

## Web Routes

### Public pages (no auth)
| URI | Controller Action | Route Name |
|---|---|---|
| `/` | `FrontendController@index` | `home` |
| `/ministries` | `FrontendController@ministries` | `ministries` |
| `/privacy-policy` | `FrontendController@privacyPolicy` | `privacy-policy` |

### Assessment flow (session-only, no auth)
| URI | Controller Action | Route Name |
|---|---|---|
| `/assessment` | `AssessmentController@show` | `assessment.index` |
| `/assessment/set-church-code` | `AssessmentController@setChurchCode` | `assessment.set-church-code` |
| `/assessment/phase1` | `AssessmentController@storePhase1` | `assessment.phase1.store` |
| `/assessment/phase2` | `AssessmentController@storePhase2` | `assessment.phase2.store` |
| `/assessment/phase3` | `AssessmentController@storePhase3` | `assessment.phase3.store` |
| `/assessment/phase4` | `AssessmentController@storePhase4` | `assessment.phase4.store` |
| `/assessment/reset` | `AssessmentController@reset` | `assessment.reset` |
| `/assessment/done` | `AssessmentController@done` | `assessment.done` |

### Auth (public)
| URI | Controller Action | Route Name |
|---|---|---|
| `/admin/login` | `LoginController@showLoginForm` | `admin.login` |
| `/admin/register` | `RegisterController@showRegisterForm` | `admin.register` |
| `/admin/login` POST | `LoginController@login` | — |
| `/admin/send-verification` POST | `RegisterController@sendVerification` | `admin.send-verification` |
| `/admin/verify-registration` POST | `RegisterController@verifyRegistration` | `admin.verify-registration` |
| `/admin/forgot-password` POST | `ForgotPasswordController@sendTempPassword` | `admin.forgot-password` |
| `/admin/validate-church-code` POST | `RegisterController@validateChurchCode` | — |

### Admin dashboard (protected by `admin` middleware)
| URI | Controller Action | Route Name |
|---|---|---|
| `/admin/dashboard` | `DashboardController@index` | `admin.dashboard` |
| `/admin/dashboard/data` | `DashboardController@getData` | `admin.dashboard.data` |
| `/admin/restrictions` | Redirect to demographics | `admin.restrictions` |
| `/admin/restrictions/demographics` | `RestrictionController@demographics` | `admin.restrictions.demographics` |
| `/admin/restrictions/demographics/update` POST | `RestrictionController@updateDemographics` | `admin.restrictions.demographics.update` |
| `/admin/restrictions/demographics/reset` POST | `RestrictionController@resetDemographics` | `admin.restrictions.demographics.reset` |
| `/admin/restrictions/skills` | `RestrictionController@skills` | `admin.restrictions.skills` |
| `/admin/restrictions/skills/update` POST | `RestrictionController@updateSkills` | `admin.restrictions.skills.update` |
| `/admin/restrictions/skills/reset` POST | `RestrictionController@resetSkills` | `admin.restrictions.skills.reset` |
| `/admin/questions` | Redirect to skill | `admin.questions` |
| `/admin/questions/skill` | `QuestionController@skill` | `admin.questions.skill` |
| `/admin/questions/skill/update` POST | `QuestionController@updateSkill` | `admin.questions.skill.update` |
| `/admin/questions/skill/reset` POST | `QuestionController@resetSkill` | `admin.questions.skill.reset` |
| `/admin/questions/interest` | `QuestionController@interest` | `admin.questions.interest` |
| `/admin/questions/interest/update` POST | `QuestionController@updateInterest` | `admin.questions.interest.update` |
| `/admin/questions/interest/reset` POST | `QuestionController@resetInterest` | `admin.questions.interest.reset` |
| `/admin/questions/behavioral` | `QuestionController@behavioral` | `admin.questions.behavioral` |
| `/admin/questions/behavioral/update` POST | `QuestionController@updateBehavioral` | `admin.questions.behavioral.update` |
| `/admin/questions/behavioral/reset` POST | `QuestionController@resetBehavioral` | `admin.questions.behavioral.reset` |
| `/admin/settings` | `SettingsController@index` | `admin.settings` |
| `/admin/settings/church-name` POST | `SettingsController@updateChurchName` | `admin.settings.church-name` |
| `/admin/settings/password` POST | `SettingsController@updatePassword` | `admin.settings.password` |
| `/admin/logout` POST | `LogoutController@logout` | `admin.logout` |

## References
- [Laravel Routing](https://laravel.com/docs/routing)
- [Laravel Route Groups](https://laravel.com/docs/routing#route-groups)
- [Laravel Route Model Binding](https://laravel.com/docs/routing#route-model-binding)
- [Laravel Rate Limiting](https://laravel.com/docs/routing#rate-limiting)
