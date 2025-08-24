<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Family Restaurant Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .portal-container {
            width: 100%;
            max-width: 1200px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .portal-header {
            background: linear-gradient(135deg, var(--dark-color), #34495e);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .logo {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .logo i {
            color: var(--primary-color);
            background: white;
            padding: 15px;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .portal-header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .portal-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .portal-content {
            padding: 40px 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .card-icon {
            width: 80px;
            height: 80px;
            background: var(--light-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        .login-card:hover .card-icon {
            background: var(--primary-color);
            color: white;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
        }

        .card-description {
            color: #7f8c8d;
            margin-bottom: 25px;
            flex-grow: 1;
        }

        .btn-login {
            display: inline-block;
            padding: 12px 30px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid var(--primary-color);
        }

        .btn-login:hover {
            background: transparent;
            color: var(--primary-color);
        }

        .admin-card .card-icon {
            color: var(--primary-color);
        }

        .admin-card .btn-login {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .admin-card .btn-login:hover {
            color: var(--primary-color);
        }

        .customer-card .card-icon {
            color: var(--warning-color);
        }

        .customer-card .btn-login {
            background: var(--warning-color);
            border-color: var(--warning-color);
        }

        .customer-card .btn-login:hover {
            color: var(--warning-color);
        }

        .portal-footer {
            background: var(--light-color);
            padding: 20px;
            text-align: center;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .portal-header h1 {
                font-size: 1.8rem;
            }

            .logo {
                font-size: 2rem;
            }

            .portal-content {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="portal-container">
        <div class="portal-header">
            <div class="logo">
                <img src="{{ asset('default/Untitled design.png') }}" alt="" width="400">
            </div>
            <h1>Dynamic Family Restaurant Management System</h1>
            <p>Welcome to our management portal. Please select your role to continue.</p>
        </div>

        <div class="portal-content">
            <div class="login-card admin-card">
                <div class="card-icon">
                    <i class="fas fa-user-cog"></i>
                </div>
                <h3 class="card-title">Admin Portal</h3>
                <p class="card-description">Access the admin dashboard to manage menus, employees, inventory, and view
                    reports.</p>
                <a href="{{ route('admin.login') }}" target="_blank" class="btn-login">Admin Login</a>
            </div>

            <div class="login-card customer-card">
                <div class="card-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="card-title">Customer Portal</h3>
                <p class="card-description">Place orders, view menu, make reservations, and track your orders.</p>
                <a href="{{ route('index') }}" target="_blank" class="btn-login">Customer Login</a>
            </div>
        </div>

        <div class="portal-footer">
            <p>© {{ date('Y') }} Dynamic Family Restaurant Management System. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
