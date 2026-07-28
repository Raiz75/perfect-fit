@extends('_layouts.master')

@section('title', 'Privacy Policy — PERFIT')

@push('head')
<style>
    .pp-hero {
        padding-top: 120px;
        padding-bottom: 60px;
        background: linear-gradient(135deg, #faf8ff 0%, #f0e6ff 50%, #faf8ff 100%);
    }

    .pp-card {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 24px;
        border: 1px solid rgba(140,82,255,0.06);
        box-shadow: 0 4px 24px rgba(0,0,0,0.04);
        padding: 3rem;
        margin-bottom: 3rem;
    }

    .pp-card h2 {
        color: #8c52ff;
        font-weight: 700;
        font-size: 1.15rem;
        margin-bottom: 0.75rem;
    }

    .pp-card p {
        color: #4a3a6e;
        line-height: 1.8;
        margin-bottom: 0.75rem;
    }

    .pp-card ul {
        margin: 0.5rem 0 1rem 1.5rem;
        padding: 0;
    }

    .pp-card li {
        color: #4a3a6e;
        line-height: 1.8;
        margin: 0.4rem 0;
    }

    .pp-card li ul {
        margin-top: 0.25rem;
    }

    .pp-card strong {
        color: #5a35b0;
    }

    .pp-meta {
        color: #888;
        font-size: 0.9rem;
        margin-top: 0.25rem;
    }

    .pp-contact {
        color: #8c52ff;
        font-weight: 600;
    }

    .pp-footer-line {
        border-top: 1px solid rgba(140,82,255,0.1);
        padding-top: 1.5rem;
        margin-top: 1.5rem;
        color: #888;
        font-size: 0.9rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media (max-width: 768px) {
        .pp-card { padding: 1.5rem; }
    }
</style>
@endpush

@section('content')
<section class="pp-hero text-center">
    <div class="container">
        <span class="d-inline-block px-3 py-1 rounded-pill fw-semibold text-uppercase mb-3" style="font-size: .75rem; letter-spacing: .1em; background: #ebdefb; color: #8c52ff;">
            Legal
        </span>
        <h1 class="display-4 fw-bold mb-3">
            <span style="color: #8c52ff;">Privacy</span> Policy
        </h1>
        <p class="text-muted" style="font-size: 0.95rem;">Last Updated: July 28, 2026</p>
    </div>
</section>

<div class="container">
    <div class="pp-card">
        <p>PERFIT values your privacy. This Privacy Policy explains how we collect, use, and protect the information you provide when using our platform.</p>
    </div>

    <div class="pp-card">
        <h2>1. Information We Collect</h2>
        <p>When you use PERFIT, we collect the following information:</p>
        <ul>
            <li><strong>Personal Data:</strong> name, email address, contact number, age, gender, marital status, baptism status, and time in faith.</li>
            <li><strong>Assessment Data:</strong> your responses to skill profiling, interest & passion, and behavioral profiling questions.</li>
            <li><strong>Church Information:</strong> church code and church name associated with your assessment.</li>
            <li><strong>Technical Data:</strong> IP address, browser type and version, operating system, and user agent string (stored in session records).</li>
            <li><strong>Admin Account Data:</strong> if you register as an admin, we collect your name, email, and hashed password.</li>
        </ul>
    </div>

    <div class="pp-card">
        <h2>2. How We Use Your Information</h2>
        <ul>
            <li>Generate assessment results and recommendations for ministry fit.</li>
            <li>Improve the accuracy and personalization of PERFIT's assessments.</li>
            <li>Ensure the security and functionality of the platform.</li>
            <li>Process assessment responses with the help of the DeepSeek API for AI-generated analysis and insights (no personal identifiers are sent).</li>
            <li>Send verification emails and temporary passwords during admin registration and password recovery.</li>
            <li>Provide church administrators with access to submitted assessment reports.</li>
        </ul>
    </div>

    <div class="pp-card">
        <h2>3. Data Sharing and Disclosure</h2>
        <ul>
            <li>We do not sell your personal information.</li>
            <li>Data may only be shared with:
                <ul>
                    <li>Authorized church administrators of PERFIT, who can view assessment reports submitted under their church code.</li>
                    <li>DeepSeek API, strictly for processing and generating assessment result interpretations (only ranked ministry data, no personal identifiers).</li>
                    <li>Email delivery service (SMTP via Gmail) for sending verification codes and temporary passwords.</li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="pp-card">
        <h2>4. Data Storage and Security</h2>
        <ul>
            <li>Assessment data is stored <strong>temporarily in server-side sessions</strong> during the assessment process (120-minute session lifetime).</li>
            <li>Upon completing the assessment, a <strong>report is saved to our database</strong> (<code>user_reports</code> table) containing your personal and assessment data.</li>
            <li>Admin accounts are stored in the <code>users</code> table with hashed passwords.</li>
            <li>Session data (including IP address and user agent) is stored in a database-backed <code>sessions</code> table.</li>
            <li>We implement security measures including password hashing, HTTP-only cookies, and CSRF protection.</li>
        </ul>
    </div>

    <div class="pp-card">
        <h2>5. Data Retention</h2>
        <ul>
            <li>Assessment session data is automatically cleared from the session after completion or after 120 minutes of inactivity.</li>
            <li>Completed assessment reports (<code>user_reports</code>) are retained indefinitely for church record-keeping and administrative reference.</li>
            <li>Admin account records are retained until the account is deleted.</li>
            <li>Session records are automatically pruned from the database over time.</li>
        </ul>
    </div>

    <div class="pp-card">
        <h2>6. Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li><strong>Access:</strong> request a copy of your personal data stored in our database.</li>
            <li><strong>Correction:</strong> request that inaccurate data be corrected.</li>
            <li><strong>Deletion:</strong> request deletion of your assessment report and associated data.</li>
            <li><strong>Restart:</strong> restart or cancel your assessment at any time before completion, discarding all session data.</li>
        </ul>
        <p>To exercise these rights, contact us at <span class="pp-contact">raizeningalla@gmail.com</span>.</p>
    </div>

    <div class="pp-card">
        <h2>7. Cookies and Tracking</h2>
        <ul>
            <li>PERFIT sets a single <strong>session cookie</strong> (<code>perfit-session</code>) that is strictly necessary for platform functionality. This cookie is HTTP-only (not accessible to JavaScript) and expires after 120 minutes.</li>
            <li>Assessment phase transitions use <strong>sessionStorage</strong> in your browser temporarily for debugging purposes; this data is automatically removed after each page load.</li>
            <li>We do not use any analytics, advertising, or third-party tracking cookies.</li>
        </ul>
    </div>

    <div class="pp-card">
        <h2>8. Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. Updates will be posted on this page with a new "Last Updated" date.</p>
    </div>

    <div class="pp-card">
        <h2>9. Contact Us</h2>
        <p>If you have questions about this Privacy Policy, contact us at:</p>
        <p class="pp-contact">raizeningalla@gmail.com</p>

        <div class="pp-footer-line">
            <span>&copy; PERFIT — Privacy Policy</span>
        </div>
    </div>
</div>
@endsection
