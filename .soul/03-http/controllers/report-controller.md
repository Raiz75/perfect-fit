---
type: controller
name: ReportController
namespace: App\Http\Controllers\Admin
route: POST /admin/dashboard/report
route_name: admin.dashboard.report
middleware: admin
form_request: App\Http\Requests\Admin\GenerateReportRequest
services: [ChartImageService, Pdf (Barryvdh\DomPDF\Facade\Pdf)]
last_updated: 2026-07-30
---

# ReportController

## Purpose
Generates a downloadable PDF report of the admin dashboard data, including server-side rendered chart images, filtered data tables, and summary statistics. Mirrors the legacy `php-generateAdminReport.php` format.

## Route
- **POST** `/admin/dashboard/report` — `generate()` — Protected by `admin` middleware.

## Dependencies
- `GenerateReportRequest` — validates 11 optional filter params (search, startDate, endDate, gender, marital, baptized, faith, age, skills, ministries).
- `ChartImageService` — renders pie/doughnut/bar charts as PNG images via pChart (CpChart).
- `Barryvdh\DomPDF\Facade\Pdf` — converts Blade template to PDF.

## Flow
1. Read filter params from request (mirrors `DashboardController::getData()`)
2. Query `UserReport::where('church_code', Auth::user()->church_code)` with same filter chain
3. Compute chart aggregations (7 sections: gender, age, baptized, faith, skills, ministry, marital)
4. Generate chart images via `ChartImageService` (skip all-zero charts)
5. Build filters list and table rows for the PDF
6. Register Dompdf `end_document` callback for page numbering
7. Render `admin.reports.dashboard-pdf` Blade view
8. Download PDF with filename `{ChurchName}_Dashboard_Report.pdf`
9. Clean up temp chart images

## Constants
- `GENDER_MAP`, `MARITAL_MAP`, `BAPTIZED_MAP`, `FAITH_MAP` — duplicate of `DashboardController` constants (field mappings)
- `SKILL_ORDER`, `SKILL_LABELS` — 8 skill column names and display labels
- `AGE_BUCKETS`, `FAITH_ORDER` — ordered bucket labels for charts

## Key Methods
- `generate()` — main entry point
- `computeChartData($reports)` — aggregates 7 chart datasets from filtered UserReport collection
- `generateChartImages($chartData, $chartService)` — renders chart images via pChart with matching color palettes
- `buildFiltersList($request)` — creates filters display table for PDF
- `buildTableRows($reports)` — maps report fields to display format
- `bucketAge($age)` — buckets age into 'Under 18', '18-25', '26-35', '36-50', '51+'
- `hslToHex($h, $s, $l)` / `hslColors($count)` — generates HSL-distributed colors for ministry chart

## Color Palettes
All match `admin-dashboard.js` Chart.js colors exactly. Hardcoded in `getChartPalettes()`.

## References
- [DomPDF Callbacks](https://github.com/dompdf/dompdf#callbacks)
- [pChart/CpChart docs](https://github.com/szymach/c-pchart)