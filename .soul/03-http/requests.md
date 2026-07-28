---
type: form-requests-summary
path: app/Http/Requests
requests:
  - name: StoreDemographicsRequest
    authorizes: true
    rules_summary: [name required max:255, email required email max:255, contact required string max:10, gender required in:1,2, age required integer min:1 max:100, status required in:1,2, baptized required in:1,2, timeInFaith required in:1,2,3,4]
  - name: LoginRequest
    authorizes: true
    rules_summary: [email required email, password required string]
  - name: SendVerificationRequest
    authorizes: true
    rules_summary: [email required email unique:users, password required string confirmed min:8 with regex (uppercase+number+special)]
  - name: ChangePasswordRequest
    authorizes: true
    rules_summary: [current_password required current_password, new_password required string min:8 confirmed with regex (uppercase+number+special)]
last_updated: 2026-07-28
---

# Form Requests

| Name | Namespace | Validates | Used By |
|---|---|---|---|
| **StoreDemographicsRequest** | `App\Http\Requests\Assessment` | Assessment phase 1 demographic fields | `AssessmentController@storePhase1` |
| **LoginRequest** | `App\Http\Requests\Auth` | Email + password login | `LoginController@login` |
| **SendVerificationRequest** | `App\Http\Requests\Auth` | Registration email + password (confirmed, strong) | `RegisterController@sendVerification` |
| **ChangePasswordRequest** | `App\Http\Requests\Auth` | Current password + new password (confirmed, strong) | `SettingsController@updatePassword` |

All requests authorize to `true` (access is controlled by middleware instead).

## References
- [Laravel Validation](https://laravel.com/docs/validation)
- [Laravel Form Requests](https://laravel.com/docs/validation#form-request-validation)
- [Laravel Validation Rules](https://laravel.com/docs/validation#available-validation-rules)
- [Laravel Authorization — Form Requests](https://laravel.com/docs/authorization#via-form-requests)
