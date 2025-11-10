@extends('admin.layouts.master')

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <h2>Dashboard</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-calendar-check text-primary-custom"></i>
                    <h2>{{ \App\Models\Reservation::whereDate('reservation_date', today())->count() }}</h2>
                    <p>Today's Reservations</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-shopping-bag text-primary-custom"></i>
                    <h2>{{ \App\Models\Order::whereDate('created_at', today())->count() }}</h2>
                    <p>Today's Orders</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-utensils text-primary-custom"></i>
                    <h2>{{ \App\Models\Menu::count() }}</h2>
                    <p>Menu Items</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card stat-card">
                <div class="card-body">
                    <i class="fas fa-users text-primary-custom"></i>
                    <h2>{{ \App\Models\User::count() }}</h2>
                    <p>Total Customers</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Overview -->
    <div class="row mt-4">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Revenue Overview</h5>
                </div>
                <div class="card-body">
                    <div class="revenue-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Today's Revenue</span>
                            <strong class="text-primary-custom">
                                Rs. {{ \App\Models\Order::whereDate('created_at', today())->sum('total') ?? 0 }}
                            </strong>
                        </div>
                    </div>
                    <div class="revenue-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>This Month</span>
                            <strong class="text-primary-custom">
                                Rs. {{ \App\Models\Order::whereMonth('created_at', now()->month)->sum('total') ?? 0 }}
                            </strong>
                        </div>
                    </div>
                    <div class="revenue-item">
                        <div class="d-flex justify-content-between">
                            <span>Total Revenue</span>
                            <strong class="text-primary-custom">
                                Rs. {{ \App\Models\Order::sum('total') ?? 0 }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="order-status-item mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-warning-custom">Pending</span>
                            <span>{{ \App\Models\Order::where('status', 'pending')->count() }}</span>
                        </div>
                    </div>
                    <div class="order-status-item mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-info">Confirmed</span>
                            <span>{{ \App\Models\Order::where('status', 'confirmed')->count() }}</span>
                        </div>
                    </div>
                    <div class="order-status-item mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-primary-custom">Preparing</span>
                            <span>{{ \App\Models\Order::where('status', 'preparing')->count() }}</span>
                        </div>
                    </div>
                    <div class="order-status-item mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-success-custom">Ready</span>
                            <span>{{ \App\Models\Order::where('status', 'ready')->count() }}</span>
                        </div>
                    </div>
                    <div class="order-status-item">
                        <div class="d-flex justify-content-between">
                            <span class="badge bg-secondary">Completed</span>
                            <span>{{ \App\Models\Order::where('status', 'completed')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Reviews Summary</h5>
                </div>
                <div class="card-body">
                    <div class="review-summary-item mb-2">
                        <div class="d-flex justify-content-between">
                            <span>Average Rating</span>
                            <strong class="text-primary-custom">
                                {{--  {{ number_format(\App\Models\Review::avg('rating') ?? 0, 1) }} --}} / 5
                            </strong>
                        </div>
                    </div>
                    <div class="review-summary-item mb-2">
                        <div class="d-flex justify-content-between">
                            <span>5 Star Reviews</span>
                            <span>{{-- {{ \App\Models\Review::where('rating', 5)->count() }} --}}</span>
                        </div>
                    </div>
                    <div class="review-summary-item mb-2">
                        <div class="d-flex justify-content-between">
                            <span>4 Star Reviews</span>
                            <span>{{-- {{ \App\Models\Review::where('rating', 4)->count() }} --}}</span>
                        </div>
                    </div>
                    <div class="review-summary-item mb-2">
                        <div class="d-flex justify-content-between">
                            <span>3 Star Reviews</span>
                            <span>{{-- {{ \App\Models\Review::where('rating', 3)->count() }} --}}</span>
                        </div>
                    </div>
                    <div class="review-summary-item">
                        <div class="d-flex justify-content-between">
                            <span>New Reviews (Today)</span>
                            <span>{{-- {{ \App\Models\Review::whereDate('created_at', today())->count() }} --}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Reservations -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary-custom">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\Order::latest()->take(5)->get() as $order)
                                    <tr>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ ucfirst($order->order_type) }}</span>
                                        </td>
                                        <td>Rs. {{ $order->total }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->statusColor() }}-custom">
                                                {{ $order->statusText() }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Reservations</h5>
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-primary-custom">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Guests</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (\App\Models\Reservation::latest()->take(5)->get() as $reservation)
                                    <tr>
                                        <td>{{ $reservation->code }}</td>
                                        <td>{{ $reservation->reservation_date->format('M d, Y') }}</td>
                                        <td>{{ $reservation->reservation_time }}</td>
                                        <td>{{ $reservation->guest_count }}</td>
                                        <td>
                                            <span class="badge bg-{{ $reservation->statusColor() }}-custom">
                                                {{ ucfirst($reservation->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Menu Items & Recent Reviews -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Popular Menu Items</h5>
                    <a href="{{ route('admin.menus.index') }}" class="btn btn-sm btn-primary-custom">Manage Menu</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Orders</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $popularItems = \App\Models\OrderItem::selectRaw(
                                        'menu_id, menu_name, price, COUNT(*) as order_count, SUM(quantity) as total_quantity',
                                    )
                                        ->groupBy('menu_id', 'menu_name', 'price')
                                        ->orderBy('total_quantity', 'desc')
                                        ->take(5)
                                        ->get();
                                @endphp

                                @foreach ($popularItems as $item)
                                    <tr>
                                        <td>{{ $item->menu_name }}</td>
                                        <td>
                                            @if ($item->menu && $item->menu->category)
                                                {{ $item->menu->category->name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>Rs. {{ $item->price }}</td>
                                        <td>{{ $item->total_quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Reviews</h5>
                    <a href="#" class="btn btn-sm btn-primary-custom">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                        {{--     <tbody>
                                @foreach (\App\Models\Review::with('user')->latest()->take(5)->get() as $review)
                                    <tr>
                                        <td>{{ $review->user->name ?? 'Anonymous' }}</td>
                                        <td>
                                            <div class="text-warning">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="fas fa-star"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                                <small class="text-muted ms-1">({{ $review->rating }})</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                title="{{ $review->comment }}">
                                                {{ \Illuminate\Support\Str::limit($review->comment, 50) }}
                                            </span>
                                        </td>
                                        <td>{{ $review->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
