<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            max-width: 800px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        h1 {
            font-size: 3em;
            margin-bottom: 20px;
            color: #667eea;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 30px;
            color: #666;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 40px 0;
        }
        .feature {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .feature h3 {
            color: #764ba2;
            margin-bottom: 10px;
        }
        .buttons {
            margin-top: 30px;
        }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            margin: 10px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 Laravel LMS</h1>
        <p>Comprehensive Learning Management System</p>
        
        <div class="features">
            <div class="feature">
                <h3>👥 User Management</h3>
                <p>Roles, Groups, Profiles</p>
            </div>
            <div class="feature">
                <h3>📚 Courses</h3>
                <p>Modules, Lessons, Drip Content</p>
            </div>
            <div class="feature">
                <h3>✍️ Quizzes</h3>
                <p>7 Question Types, Banks</p>
            </div>
            <div class="feature">
                <h3>🏆 Gamification</h3>
                <p>Achievements, Points</p>
            </div>
            <div class="feature">
                <h3>💰 Monetization</h3>
                <p>Payments, Wallets</p>
            </div>
            <div class="feature">
                <h3>📊 Analytics</h3>
                <p>Progress, Reports</p>
            </div>
        </div>

        <div class="buttons">
            <a href="/dashboard" class="btn">Get Started</a>
            <a href="https://github.com/romchy222/-laravellm-sv1" class="btn btn-secondary" target="_blank">View on GitHub</a>
        </div>

        <p style="margin-top: 30px; font-size: 0.9em; color: #999;">
            Built with Laravel 11 | PHP 8.2+ | MySQL 8.0+
        </p>
    </div>
</body>
</html>
