<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-auth" content="{{ auth()->check() ? 'true' : 'false' }}">
    

    <title>Dynamic Family Restaurant | Authentic Sri Lankan Cuisine</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
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
