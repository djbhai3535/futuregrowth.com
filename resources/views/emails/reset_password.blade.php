<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Verification</title>
</head>
<body style="background-color: #09090b; color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 40px 10px; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <div class="container" style="max-width: 580px; margin: 0 auto; background-color: #18181b; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
        <!-- Top Tech Accent line -->
        <div style="height: 4px; background: linear-gradient(90deg, #3b82f6, #8b5cf6);"></div>
        
        <!-- Header -->
        <div style="padding: 32px 32px 20px 32px; text-align: center;">
            @if(setting('site_logo'))
                <img src="{{ url(Storage::url(setting('site_logo'))) }}" alt="{{ setting('site_name', 'FutureGrowth.tech') }}" style="max-height: 45px; display: block; margin: 0 auto;">
            @else
                <span style="font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: 1px;">
                    <span style="color: #3b82f6;">FUTURE</span><span style="color: #ffffff; font-weight: 300;">GROWTH</span>
                </span>
            @endif
        </div>
        
        <!-- Body -->
        <div style="padding: 0 32px 32px 32px;">
            <h2 style="color: #ffffff; font-size: 20px; font-weight: 700; margin-top: 0; margin-bottom: 12px; text-align: center;">Password Reset Requested</h2>
            <p style="color: #a1a1aa; font-size: 15px; line-height: 1.6; margin-bottom: 24px; text-align: center;">
                We received a request to reset the password for your account. Please use the following 6-digit verification code to complete the reset process:
            </p>
            
            <p style="color: #ffffff; font-size: 14px; font-weight: 600; text-align: center; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Your Password Reset Code</p>
            <div style="text-align: center; margin: 20px 0;">
                <div style="display: inline-block; background-color: #09090b; border: 1px solid #3b82f6; color: #3b82f6; font-size: 32px; font-weight: bold; letter-spacing: 6px; padding: 16px 36px; border-radius: 8px; box-shadow: 0 0 15px rgba(59, 130, 246, 0.15);">{{ $token }}</div>
            </div>
            
            <p style="color: #a1a1aa; font-size: 14px; line-height: 1.6; text-align: center; margin-top: 24px; margin-bottom: 0;">
                This code is valid for 60 minutes. If you did not request a password reset, you can safely ignore this email.
            </p>
        </div>
        
        <!-- Footer -->
        <div style="padding: 24px 32px; background-color: #111113; border-top: 1px solid rgba(255, 255, 255, 0.06); text-align: center; font-size: 12px; color: #a1a1aa; line-height: 1.5;">
            &copy; {{ date('Y') }} {{ setting('site_name', 'FutureGrowth.tech') }}. All rights reserved.<br>
            <span style="display: inline-block; margin-top: 6px;">For support, contact us at <a href="mailto:{{ setting('support_email', 'support@futuregrowth.tech') }}" style="color: #3b82f6; text-decoration: none;">{{ setting('support_email', 'support@futuregrowth.tech') }}</a></span>
        </div>
    </div>
</body>
</html>
