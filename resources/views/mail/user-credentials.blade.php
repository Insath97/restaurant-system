<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Login Credentials</title>
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
                                <h1 style="color: #2c3e50; margin-top: 0; font-size: 28px; font-weight: 700; margin-bottom: 20px;">Welcome to {{ config('app.name') }}!</h1>

                                <p style="margin-bottom: 20px; font-size: 16px;">Hello,</p>

                                <p style="margin-bottom: 20px; font-size: 16px;">Your {{ $role }} account has been successfully created. Here are your login credentials:</p>

                                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #ecf0f1; padding: 20px; border-radius: 8px; margin: 20px 0;">
                                    <tr>
                                        <td>
                                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                                <tr>
                                                    <td width="100" style="font-weight: 600; padding: 5px 0;">Email:</td>
                                                    <td style="padding: 5px 0;">{{ $mail }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="100" style="font-weight: 600; padding: 5px 0;">Password:</td>
                                                    <td style="padding: 5px 0;">{{ $password }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="100" style="font-weight: 600; padding: 5px 0;">Role:</td>
                                                    <td style="padding: 5px 0; text-transform: capitalize;">{{ $role }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <div style="text-align: center; margin: 25px 0;">
                                    <a href="{{ route('admin.login') }}" style="display: inline-block; padding: 15px 30px; background: #d35400; background: linear-gradient(to right, #d35400 0%, #e67e22 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; box-shadow: 0 4px 15px rgba(211, 84, 0, 0.3);">Login to Your Account</a>
                                </div>

                                <p style="margin-bottom: 20px; font-size: 16px; color: #e74c3c; font-weight: 600;">⚠️ For security reasons, we recommend changing your password after first login.</p>

                                <hr style="height: 1px; background-color: rgba(0, 0, 0, 0.1); margin: 30px 0; border: none;">

                                <p style="margin-bottom: 20px; font-size: 16px;">If you didn't request this account, please contact our support team immediately.</p>
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
