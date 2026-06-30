# Changelog - FutureGrowth.tech Production Update

All changes implemented on the codebase of **FutureGrowth.tech** for the final production update.

## [1.1.0] - 2026-06-30

### Added
- **Email Verification (OTP System):**
  - Created mailable `App\Mail\VerificationMail` and HTML template `resources/views/emails/verification.blade.php`.
  - Added [verify-email.blade.php](file:///d:/THAIR%20PLATRFORM/resources/views/auth/verify-email.blade.php) form view.
  - Implemented `showVerificationNotice`, `verifyEmail`, and `resendVerificationCode` methods in `AuthController`.
  - Added `verification_code` and `verification_code_expires_at` columns to the `users` table via migration.
- **Forgot Password (OTP Reset System):**
  - Created mailable `App\Mail\ResetPasswordMail` and HTML template `resources/views/emails/reset_password.blade.php`.
  - Added [forgot-password.blade.php](file:///d:/THAIR%20PLATRFORM/resources/views/auth/forgot-password.blade.php) and [reset-password.blade.php](file:///d:/THAIR%20PLATRFORM/resources/views/auth/reset-password.blade.php) forms.
  - Implemented `sendResetCode` and `resetPassword` methods in `AuthController` validating tokens inside the `password_reset_tokens` table.
- **Admin Two-Factor Authentication (2FA):**
  - Created mailable `App\Mail\Admin2FACodeMail` and HTML template `resources/views/emails/admin_2fa.blade.php`.
  - Added [admin-2fa.blade.php](file:///d:/THAIR%20PLATRFORM/resources/views/auth/admin-2fa.blade.php) form view.
  - Implemented `showAdmin2FAForm` and `verifyAdmin2FA` methods in `AuthController`.
  - Added `two_factor_code` and `two_factor_expires_at` columns to the `users` table via migration.
- **Maintenance Mode:**
  - Created `App\Http\Middleware\MaintenanceModeMiddleware` and registered it globally inside `bootstrap/app.php`.
  - Created [maintenance.blade.php](file:///d:/THAIR%20PLATRFORM/resources/views/errors/maintenance.blade.php) page.
- **Migration & Seeder Variables:**
  - Created migration `2026_06_30_000001_add_verification_and_2fa_to_users_table.php`.
  - Updated `DatabaseSeeder.php` to seed SMTP, dynamic community buttons, reCAPTCHA, maintenance, and dynamic plans.

### Changed
- **Dynamic SMTP Configuration:**
  - Modified `AppServiceProvider.php` to override mail configurations on bootstrap using settings table values.
- **Dynamic Plans Display:**
  - Redesigned landing page plans inside `welcome.blade.php` and user dashboard investments inside `dashboard/investments/index.blade.php` using glassmorphism, soft glow, and dynamic loops fetching data from database.
- **Dynamic Community Button:**
  - Replaced hardcoded WhatsApp community CTA with dynamic CTA supporting text, link, and visibility state configured from the Admin settings.
- **Admin Secret URL Prefix:**
  - Updated prefix path in `routes/web.php` to fetch `admin_secret_path` dynamically, blocking standard `/admin` route with a 404 response.
  - Updated `AdminMiddleware.php` to check `session('admin_2fa_verified')` and abort with 404 for unauthorized attempts.
- **Google reCAPTCHA v2 support:**
  - Embedded checkbox container and script tags inside login, registration, email-verification, forgot password, and reset password views, validating input in `AuthController`.
- **Signup Bonus Credit:**
  - Shifted sign-up bonus distribution to occur automatically after successful email verification.
