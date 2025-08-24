<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; color: #2c3e50; line-height: 1.6;">
    <center>
        <table cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width: 600px; margin: 0 auto;">
            <tr>
                <td align="center" style="padding: 20px 0;">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #2c3e50; background: linear-gradient(to bottom, #2c3e50 0%, #34495e 100%); padding: 30px; text-align: center; color: white; border-radius: 12px 12px 0 0;">
                        <tr>
                            <td>
                                <h2 style="color: white; margin: 0; font-size: 24px;">{{ config('app.name') }}</h2>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td align="center">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: white; padding: 40px;">
                        <tr>
                            <td>
                                <h1 style="color: #2c3e50; margin-top: 0; font-size: 28px; font-weight: 700; margin-bottom: 20px;">Reset Your Password</h1>

                                <p style="margin-bottom: 20px; font-size: 16px;">Hello <strong>{{ $name }}</strong>,</p>

                                <p style="margin-bottom: 20px; font-size: 16px;">We received a request to reset your password for your {{ config('app.name') }} account. Click the button below to proceed with resetting your password.</p>

                                <div style="text-align: center; margin: 25px 0;">
                                    <a href="{{ route('admin.reset-password', ['token' => $token, 'email' => $email]) }}" target="_blank" style="display: inline-block; padding: 15px 30px; background: #d35400; background: linear-gradient(to right, #d35400 0%, #e67e22 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(211, 84, 0, 0.3);">Reset Password</a>
                                </div>

                                <p style="margin-bottom: 20px; font-size: 16px;">If you didn't request this password reset, please ignore this email or contact our support team if you have any concerns.</p>

                                <hr style="height: 1px; background-color: rgba(0, 0, 0, 0.1); margin: 30px 0; border: none;">

                                <p style="margin-bottom: 20px; font-size: 16px; color: #e74c3c; font-weight: 600;">⚠️ This password reset link will expire in 60 minutes.</p>

                                <p style="margin-bottom: 20px; font-size: 16px;">Having trouble with the button? Copy and paste this URL into your browser:</p>

                                <div style="background: #ecf0f1; padding: 15px; border-radius: 6px; font-family: monospace; word-break: break-all; margin: 20px 0;">
                                    {{ route('admin.reset-password', ['token' => $token, 'email' => $email]) }}
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td align="center" style="padding-bottom: 20px;">
                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #ecf0f1; padding: 20px; text-align: center; font-size: 14px; color: #7f8c8d; border-radius: 0 0 12px 12px;">
                        <tr>
                            <td>
                                <p style="margin: 0 0 10px 0;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                                <p style="margin: 0;">
                                    <a href="#" style="color: #d35400; text-decoration: none;">Visit our website</a> |
                                    <a href="#" style="color: #d35400; text-decoration: none;">Contact Support</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
