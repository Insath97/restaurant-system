@extends('client.layouts.master')

@section('content')
    <!-- Success Header -->
    <section class="success-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h1 class="display-4 mb-3">Order Confirmed!</h1>
                    <p class="lead">Thank you for your order. We're preparing it with care.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Details -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="success-card">
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Order Information</h3>
                                <div class="order-info">
                                    <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                                    @if ($order->reservation)
                                        <p><strong>Reservation Code:</strong> {{ $order->reservation->code }}</p>
                                    @endif
                                    <p><strong>Order Type:</strong> {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                    </p>
                                    <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y g:i A') }}</p>
                                    <p><strong>Status:</strong> <span
                                            class="badge bg-{{ $order->statusColor() }}">{{ $order->status }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>Customer Details</h3>
                                <div class="customer-info">
                                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                                    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                                    @if ($order->order_type === 'takeaway')
                                        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h3>Order Items</h3>
                        <div class="order-items">
                            @foreach ($order->items as $item)
                                <div class="order-item">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($item->menu->image) }}" alt="{{ $item->menu_name }}"
                                            class="item-image me-3"
                                            onerror="this.src='{{ asset('default/dummy_600x400_ffffff_cccccc.png') }}'">
                                        <div>
                                            <h6 class="mb-1">{{ $item->menu_name }}</h6>
                                            <small class="text-muted">Rs. {{ number_format($item->price, 2) }} x
                                                {{ $item->quantity }}</small>
                                        </div>
                                    </div>
                                    <span class="item-total">Rs. {{ number_format($item->total, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <div class="order-totals">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Rs. {{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            @if ($order->order_type === 'takeaway')
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Delivery Fee:</span>
                                    <span>Rs. {{ number_format($order->delivery_fee, 2) }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span>Service Charge:</span>
                                <span>Rs. {{ number_format($order->service_charge, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span>
                                <span>Rs. {{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="fas fa-info-circle me-2"></i>
                            @if ($order->order_type === 'takeaway')
                                Your order will be delivered within 30-45 minutes. You'll receive a confirmation call
                                shortly.
                            @else
                                Your order will be ready for pickup in 20-30 minutes. Please arrive at the restaurant to
                                collect your order.
                            @endif
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('my-account') }}" class="btn btn-spice me-3">
                                <i class="fas fa-history me-2"></i> View Order History
                            </a>
                            <a href="{{ route('menu') }}" class="btn btn-outline-spice">
                                <i class="fas fa-utensils me-2"></i> Continue Ordering
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .success-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 80px 0 40px;
        }

        .success-icon {
            font-size: 5rem;
            color: #fff;
            margin-bottom: 20px;
        }

        .success-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-total {
            font-weight: 600;
            color: #28a745;
        }

        .btn-spice {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-spice:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .btn-outline-spice {
            border: 2px solid #28a745;
            color: #28a745;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-spice:hover {
            background: #28a745;
            color: white;
        }
    </style>
@endpush
