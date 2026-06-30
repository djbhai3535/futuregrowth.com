<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Verification</title>
    <style>
        body {
            background-color: #0b0f19;
            color: #f3f4f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: linear-gradient(145deg, #111827, #1f2937);
            border: 1px solid #374151;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .logo {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #fbbf24;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }
        h2 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 10px;
        }
        p {
            line-height: 1.6;
            color: #9ca3af;
        }
        .code-box {
            display: inline-block;
            background-color: #0b0f19;
            border: 1px dashed #fbbf24;
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 4px;
            padding: 15px 30px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #374151;
            padding-top: 20px;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            {{ setting('site_name', 'FutureGrowth.tech') }}
        </div>
        <h2>Welcome to the Future of Wealth!</h2>
        <p>Thank you for registering. Please verify your email address to unlock your account and claim your free <strong>${{ setting('signup_bonus', 7) }} Signup Bonus</strong>.</p>
        <p>Your 6-digit email verification code is:</p>
        <div style="text-align: center;">
            <div class="code-box">{{ $code }}</div>
        </div>
        <p>This code will expire in 30 minutes. If you did not request this, you can safely ignore this email.</p>
        <div class="footer">
            &copy; {{ date('Y') }} {{ setting('site_name', 'FutureGrowth.tech') }}. All rights reserved.<br>
            If you need any support, contact us at {{ setting('support_email', 'support@futuregrowth.tech') }}.
        </div>
    </div>
</body>
</html>
