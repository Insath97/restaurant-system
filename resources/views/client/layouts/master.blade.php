<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Family Restaurant | Authentic Sri Lankan Cuisine</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --spice-red: #B22222;
            --golden-yellow: #FFD700;
            --coconut-white: #FFF8DC;
            --deep-green: #006400;
            --charcoal: #333333;
            --light-spice: rgba(178, 34, 34, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--charcoal);
            overflow-x: hidden;
            background-color: var(--coconut-white);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Playfair Display', serif;
            color: var(--spice-red);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url('https://images.unsplash.com/photo-1585032226651-759b368d7246?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1592&q=80');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            position: relative;
        }

        .hero-content {
            animation: fadeIn 1.5s ease-in-out;
        }

        

        .btn-spice {
            background-color: var(--spice-red);
            color: white;
            font-weight: 600;
            padding: 12px 30px;
            height: max-content;
            border-radius: 30px;
            transition: all 0.3s;
            border: none;
        }

        .btn-spice:hover {
            background-color: var(--golden-yellow);
            color: var(--charcoal);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-outline-spice {
            border: 2px solid var(--golden-yellow);
            color: var(--golden-yellow);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 30px;
            transition: all 0.3s;
            background-color: transparent;
        }

        .btn-outline-spice:hover {
            background-color: var(--golden-yellow);
            color: var(--charcoal);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* About Section */
        .about-section {
            padding: 100px 0;
            background-color: white;
        }

        .about-img {
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .about-img:hover {
            transform: scale(1.03);
        }

        .about-feature {
            display: flex;
            margin-bottom: 30px;
        }

        .about-icon {
            font-size: 2rem;
            color: var(--spice-red);
            margin-right: 20px;
            flex-shrink: 0;
        }

        /* Special Offers */
        .offers-section {
            padding: 80px 0;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)),
                url('https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-attachment: fixed;
            color: white;
        }

        .offer-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 215, 0, 0.3);
            transition: all 0.3s;
        }

        .offer-card:hover {
            transform: translateY(-10px);
            background-color: rgba(178, 34, 34, 0.2);
        }

        .offer-badge {
            background-color: var(--golden-yellow);
            color: var(--charcoal);
            padding: 5px 15px;
            border-radius: 30px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }

        /* Menu Section */
        .menu-section {
            padding: 100px 0;
            background-color: var(--coconut-white);
        }

        .section-title {
            position: relative;
            margin-bottom: 50px;
            text-align: center;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background-color: var(--golden-yellow);
            border-radius: 2px;
        }

        .menu-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            margin-bottom: 30px;
            height: 100%;
            border: 1px solid rgba(178, 34, 34, 0.1);
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .menu-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .menu-card-body {
            padding: 25px;
        }

        .menu-card-title {
            font-family: 'Playfair Display', serif;
            color: var(--spice-red);
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .menu-card-price {
            color: var(--deep-green);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .menu-card-rating {
            color: var(--golden-yellow);
            margin-bottom: 10px;
        }

        .btn-add-to-cart {
            background-color: var(--spice-red);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 30px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .btn-add-to-cart:hover {
            background-color: var(--golden-yellow);
            color: var(--charcoal);
            transform: translateY(-3px);
        }

        /* Chef Specials */
        .chef-section {
            padding: 80px 0;
            background-color: white;
        }

        .chef-card {
            text-align: center;
            padding: 30px;
            transition: all 0.3s;
        }

        .chef-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--golden-yellow);
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        /* Reservation Section */
        .reservation-section {
            padding: 100px 0;
            background: linear-gradient(rgba(255, 248, 220, 0.9), rgba(255, 248, 220, 0.9)),
                url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
        }

        .reservation-form {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(178, 34, 34, 0.1);
        }

        .reservation-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .reservation-header i {
            font-size: 2.5rem;
            color: var(--golden-yellow);
            margin-bottom: 15px;
        }

        .form-control,
        .form-select {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: var(--spice-red);
        }

        /* Gallery Section */
        .gallery-section {
            padding: 80px 0;
            background-color: var(--coconut-white);
        }

        .gallery-item {
            margin-bottom: 30px;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .gallery-item img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.7));
            color: white;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        /* Testimonials */
        .testimonial-section {
            padding: 100px 0;
            background-color: var(--spice-red);
            color: white;
        }

        .testimonial-card {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 30px;
            border-radius: 10px;
            margin: 15px;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 215, 0, 0.3);
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            background-color: rgba(255, 255, 255, 0.15);
        }

        .testimonial-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--golden-yellow);
            margin-bottom: 15px;
        }

        .stars {
            color: var(--golden-yellow);
            margin-bottom: 15px;
        }

        /* Events Section */
        .events-section {
            padding: 80px 0;
            background-color: white;
        }

        .event-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: all 0.3s;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .event-date {
            background-color: var(--spice-red);
            color: white;
            padding: 10px 20px;
            display: inline-block;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        /* Cart Sidebar */
        .cart-sidebar {
            position: fixed;
            right: -400px;
            top: 0;
            width: 400px;
            height: 100vh;
            background-color: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1050;
            transition: right 0.3s ease;
            overflow-y: auto;
        }

        .cart-sidebar.show {
            right: 0;
        }

        .cart-header {
            background-color: var(--spice-red);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .cart-item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }

        .cart-total {
            padding: 20px;
            background-color: var(--light-spice);
            font-weight: bold;
            font-size: 1.1rem;
        }

        .cart-toggle {
            position: fixed;
            right: 30px;
            bottom: 30px;
            background-color: var(--spice-red);
            color: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            z-index: 1040;
            transition: all 0.3s;
        }

        .cart-toggle:hover {
            transform: scale(1.1);
            background-color: var(--golden-yellow);
            color: var(--charcoal);
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--golden-yellow);
            color: var(--charcoal);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            background-color: var(--charcoal);
            color: white;
            padding: 80px 0 0;
        }

        .footer-title {
            color: var(--golden-yellow);
            margin-bottom: 25px;
            font-size: 1.3rem;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--golden-yellow);
            padding-left: 5px;
        }

        .social-icons a {
            color: var(--golden-yellow);
            font-size: 22px;
            margin-right: 18px;
            transition: all 0.3s;
            display: inline-block;
        }

        .social-icons a:hover {
            color: white;
            transform: translateY(-3px);
        }

        .footer-bottom {
            background-color: #222;
            padding: 25px 0;
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Adjustments */
        @media (max-width: 1199px) {
            .cart-sidebar {
                width: 350px;
            }
        }

        @media (max-width: 991px) {
            .hero-section {
                min-height: 80vh;
                text-align: center;
            }

            .reservation-form {
                padding: 30px;
            }

            .about-img {
                margin-bottom: 40px;
            }
        }

        @media (max-width: 767px) {
            .hero-section {
                min-height: 70vh;
            }

            .section-title {
                font-size: 2rem;
            }

            .cart-sidebar {
                width: 100%;
            }

            .cart-toggle {
                width: 60px;
                height: 60px;
                right: 20px;
                bottom: 20px;
            }
        }

        @media (max-width: 575px) {
            .hero-section {
                min-height: 60vh;
            }

            .btn-spice,
            .btn-outline-spice {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .reservation-form {
                padding: 20px;
            }
        }

        /* Toast Notification */
        .toast-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1100;
        }

        .toast {
            background-color: var(--spice-red);
            color: white;
            padding: 12px 24px;
            border-radius: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100px) translateX(-50%);
                opacity: 0;
            }

            to {
                transform: translateY(0) translateX(-50%);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    @include('client.layouts.navbar')

    @yield('content')

    <!-- Footer -->
    @include('client.layouts.footer')

    <!-- Shopping Cart Sidebar -->
    @include('client.layouts.shippingCartSidebar')

    <!-- Cart Toggle Button -->
    @include('client.layouts.cartToggle')

    <!-- Scripts -->
    @include('client.layouts.scripts')
</body>

</html>
