<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode - {{ setting('site_name', 'FutureGrowth.tech') }}</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #030712;
            color: #f3f4f6;
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-hidden: hidden;
            position: relative;
        }
        .bg-grid {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            z-index: 1;
        }
        .glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            z-index: 2;
            filter: blur(50px);
        }
        .glow-1 { top: -10%; left: -10%; }
        .glow-2 { bottom: -10%; right: -10%; }
        
        .content-card {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 50px 40px;
            text-align: center;
            max-width: 600px;
            z-index: 3;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            position: relative;
        }
        
        .icon-box {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 50%;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 48px;
            color: #6366f1;
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.2);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 30px rgba(99, 102, 241, 0.2); }
            50% { transform: scale(1.05); box-shadow: 0 0 50px rgba(99, 102, 241, 0.4); }
            100% { transform: scale(1); box-shadow: 0 0 30px rgba(99, 102, 241, 0.2); }
        }
        
        h1 {
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -1px;
            margin-bottom: 20px;
        }
        
        p {
            color: #9ca3af;
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .btn-telegram {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }
        
        .btn-telegram:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(59, 130, 246, 0.4);
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="bg-grid"></div>
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>
    
    <div class="content-card">
        <div class="icon-box">
            <i class="bi bi-tools"></i>
        </div>
        <h1>System Upgrades in Progress</h1>
        <p>We are currently performing scheduled maintenance to upgrade our servers and improve security protocols. We will be back online shortly to continue delivering premium investment solutions.</p>
        
        @if(setting('telegram_link'))
        <a href="{{ setting('telegram_link') }}" target="_blank" class="btn btn-telegram px-4 py-3">
            <i class="bi bi-telegram me-2"></i> Join Our Telegram Community
        </a>
        @endif
        
        <div class="mt-4 pt-3 border-top border-secondary border-opacity-25 text-muted small">
            &copy; {{ date('Y') }} {{ setting('site_name', 'FutureGrowth.tech') }}. Keep in touch.
        </div>
    </div>
</body>
</html>
