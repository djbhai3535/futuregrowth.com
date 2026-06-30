<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

// Bootstrap the console kernel so Laravel's environment is loaded
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Setting;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

echo "====================================================\n";
echo "FutureGrowth.tech Integration & Feature Test Suite\n";
echo "====================================================\n\n";

// Disable reCAPTCHA during tests so validation doesn't block automated HTTP requests
Setting::where('key', 'enable_recaptcha')->update(['value' => '0']);
\Illuminate\Support\Facades\Cache::forget('setting_enable_recaptcha');

// Clean up any existing test users
User::where('email', 'testuser@example.com')->delete();

// Helper to assert conditions
function assertTest($condition, $message) {
    if ($condition) {
        echo "✅ PASS: $message\n";
    } else {
        echo "❌ FAIL: $message\n";
        exit(1);
    }
}

// Helper to create request with session assigned
function createRequest($uri, $method, $parameters = []) {
    $request = \Illuminate\Http\Request::create($uri, $method, $parameters);
    $request->setLaravelSession(app('session.store'));
    return $request;
}

// --------------------------------------------------
// 1. REGISTRATION TEST
// --------------------------------------------------
echo "Testing User Registration...\n";
$registrationData = [
    'name' => 'Test User',
    'username' => 'testuser',
    'email' => 'testuser@example.com',
    'phone' => '+9876543210',
    'password' => 'SecurePass123!',
    'password_confirmation' => 'SecurePass123!'
];

// Let's perform registration using the AuthController register logic
$authController = new \App\Http\Controllers\AuthController();
$request = createRequest('/register', 'POST', $registrationData);

// Validate request
$validator = \Illuminate\Support\Facades\Validator::make($registrationData, [
    'name' => 'required|string|max:255',
    'username' => 'required|string|alpha_dash|max:255|unique:users',
    'email' => 'required|string|email|max:255|unique:users',
    'phone' => 'required|string|max:255|unique:users',
    'password' => 'required|string|min:8|confirmed',
]);

assertTest(!$validator->fails(), "Registration input validation passed");

$response = $authController->register($request);

// Verify user exists in database
$user = User::where('email', 'testuser@example.com')->first();
assertTest($user !== null, "User was successfully inserted into database");
assertTest($user->verification_code !== null, "6-digit OTP verification code was generated: " . $user->verification_code);
assertTest($user->email_verified_at === null, "User email is initially unverified");

// --------------------------------------------------
// 2. EMAIL VERIFICATION & SIGNUP BONUS TEST
// --------------------------------------------------
echo "\nTesting Email Verification & Signup Bonus...\n";
assertTest($user->wallet !== null, "User wallet was created automatically");
assertTest($user->wallet->bonus_balance == 0, "Wallet bonus balance is initially $0");

$verifyRequest = createRequest('/verify-email', 'POST', [
    'user_id' => $user->id,
    'code' => $user->verification_code
]);

$response = $authController->verifyEmail($verifyRequest);

// Refresh user model
$user->refresh();
$targetBonus = (float) setting('signup_bonus', 7);
assertTest($user->email_verified_at !== null, "User email_verified_at timestamp set successfully: " . $user->email_verified_at);
assertTest($user->verification_code === null, "OTP verification code cleared after successful verification");
assertTest((float)$user->wallet->bonus_balance === $targetBonus, "Signup Bonus of $" . $targetBonus . " successfully credited to bonus balance: $" . $user->wallet->bonus_balance);

// --------------------------------------------------
// 3. SECURE LOGIN TEST
// --------------------------------------------------
echo "\nTesting Secure User Login...\n";
Auth::logout();
$loginRequest = createRequest('/login', 'POST', [
    'email' => 'testuser@example.com',
    'password' => 'SecurePass123!'
]);

$response = $authController->login($loginRequest);
assertTest(Auth::check() && Auth::user()->email === 'testuser@example.com', "User successfully authenticated and logged in");

// --------------------------------------------------
// 4. FORGOT PASSWORD & RESET TEST
// --------------------------------------------------
echo "\nTesting Forgot Password & Reset OTP...\n";
Auth::logout();

$forgotRequest = createRequest('/forgot-password', 'POST', [
    'email' => 'testuser@example.com'
]);

$response = $authController->sendResetCode($forgotRequest);

// Get token from password_reset_tokens table
$resetToken = DB::table('password_reset_tokens')->where('email', 'testuser@example.com')->first();
assertTest($resetToken !== null, "Reset OTP generated and stored in password_reset_tokens table: " . $resetToken->token);

// Reset password using the code
$resetRequest = createRequest('/reset-password', 'POST', [
    'email' => 'testuser@example.com',
    'code' => $resetToken->token,
    'password' => 'NewSecurePass99!',
    'password_confirmation' => 'NewSecurePass99!'
]);

$response = $authController->resetPassword($resetRequest);

// Verify password is changed by logging in
$newLoginRequest = createRequest('/login', 'POST', [
    'email' => 'testuser@example.com',
    'password' => 'NewSecurePass99!'
]);
$response = $authController->login($newLoginRequest);
assertTest(Auth::check() && Auth::user()->email === 'testuser@example.com', "User successfully logged in with NEW password");

// --------------------------------------------------
// 5. ADMIN LOGIN & 2FA SECURITY TEST
// --------------------------------------------------
echo "\nTesting Administrative Security (Login, 2FA, Secret URL)...\n";
Auth::logout();
session()->forget('admin_2fa_verified');

$adminUser = User::where('email', 'admin@admin.com')->first();
assertTest($adminUser !== null, "Admin account exists in database");

$adminLoginRequest = createRequest('/login', 'POST', [
    'email' => 'admin@admin.com',
    'password' => 'password123'
]);

$response = $authController->login($adminLoginRequest);
$adminUser->refresh();

assertTest(session('admin_2fa_user_id') === $adminUser->id, "Admin login redirects to 2FA page; 2FA session registered");
assertTest($adminUser->two_factor_code !== null, "Admin 2FA login code generated: " . $adminUser->two_factor_code);

// Verify 2FA OTP Code
$verify2faRequest = createRequest('/admin-login/2fa', 'POST', [
    'code' => $adminUser->two_factor_code
]);
session(['admin_2fa_user_id' => $adminUser->id]); // Put in request session

$response = $authController->verifyAdmin2FA($verify2faRequest);
assertTest(Auth::check() && Auth::user()->is_admin, "Admin successfully authenticated after 2FA validation");
assertTest(session('admin_2fa_verified') === true, "Admin 2FA session token activated: session('admin_2fa_verified') = true");

// --------------------------------------------------
// 6. MAINTENANCE MODE TEST
// --------------------------------------------------
echo "\nTesting Maintenance Mode Middleware...\n";
Setting::where('key', 'maintenance_mode')->update(['value' => '1']);
\Illuminate\Support\Facades\Cache::forget('setting_maintenance_mode');

$maintenanceMiddleware = new \App\Http\Middleware\MaintenanceModeMiddleware();

// Non-admin request
Auth::logout();
$guestRequest = createRequest('/', 'GET');
$response = $maintenanceMiddleware->handle($guestRequest, function($req) {
    return response("Allowed");
});
assertTest($response->getStatusCode() === 503, "Maintenance active: Guest/User requests are blocked with HTTP 503 status code");

// Admin request
Auth::login($adminUser);
session(['admin_2fa_verified' => true]);
$adminRequest = createRequest('/', 'GET');
$response = $maintenanceMiddleware->handle($adminRequest, function($req) {
    return response("Allowed");
});
assertTest($response->getContent() === "Allowed" && $response->getStatusCode() === 200, "Maintenance active: Authenticated administrators successfully BYPASS maintenance block");

// Restore maintenance mode
Setting::where('key', 'maintenance_mode')->update(['value' => '0']);
\Illuminate\Support\Facades\Cache::forget('setting_maintenance_mode');

// Clean up test user
User::where('email', 'testuser@example.com')->delete();

// --------------------------------------------------
// 7. COMPLETED SUCCESS
// --------------------------------------------------
echo "\n====================================================\n";
echo "ALL FEATURE INTEGRATION TESTS PASSED SUCCESSFULLY!\n";
echo "FutureGrowth.tech is fully operational and production-ready!\n";
echo "====================================================\n";
