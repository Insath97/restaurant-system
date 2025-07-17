<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        :root {
            --primary-color: #d35400;
            --secondary-color: #e67e22;
            --dark-color: #2c3e50;
            --light-color: #ecf0f1;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
        }

        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: linear-gradient(135deg, var(--dark-color), #34495e);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .email-logo {
            max-width: 180px;
            height: auto;
            margin-bottom: 15px;
        }

        .email-body {
            padding: 40px;
        }

        h1 {
            color: var(--dark-color);
            margin-top: 0;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 20px;
            font-size: 16px;
        }

        .reset-button {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0;
            box-shadow: 0 4px 15px rgba(211, 84, 0, 0.3);
            transition: all 0.3s ease;
        }

        .reset-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 84, 0, 0.4);
        }

        .divider {
            height: 1px;
            background-color: rgba(0, 0, 0, 0.1);
            margin: 30px 0;
        }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #7f8c8d;
            background-color: var(--light-color);
        }

        .footer a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .code-block {
            background: var(--light-color);
            padding: 15px;
            border-radius: 6px;
            font-family: monospace;
            word-break: break-all;
            margin: 20px 0;
        }

        .expiry-notice {
            color: var(--danger-color);
            font-weight: 600;
        }

        /* Responsive Styles */
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 25px;
            }

            h1 {
                font-size: 24px;
            }

            .reset-button {
                padding: 12px 25px;
                font-size: 15px;
            }

            .email-header {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            @if (isset($logo))
                <img src="{{ $logo }}" alt="{{ config('app.name') }}" class="email-logo">
            @else
                <h2 style="color: white; margin: 0;">{{ config('app.name') }}</h2>
            @endif
        </div>

        <div class="email-body">
            <h1>Reset Your Password</h1>

            <p>Hello <strong>{{ $name }}</strong>,</p>

            <p>We received a request to reset your password for your {{ config('app.name') }} account. Click the button
                below to proceed with resetting your password.</p>

            <div style="text-align: center;">
                <a href="{{ route('admin.reset-password', ['token' => $token, 'email' => $email]) }}" target="_blank"
                    class="reset-button">Reset
                    Password</a>
            </div>

            <p>If you didn't request this password reset, please ignore this email or contact our support team if you
                have any concerns.</p>

            <div class="divider"></div>

            <p class="expiry-notice">⚠️ This password reset link will expire in 60 minutes.</p>

            <p>Having trouble with the button? Copy and paste this URL into your browser:</p>

            <div class="code-block">
                {{ route('admin.reset-password', ['token' => $token, 'email' => $email]) }}
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>
                <a href="#">Visit our website</a> |
                <a href="#">Contact Support</a>
            </p>
        </div>
    </div>
</body>

</html>
