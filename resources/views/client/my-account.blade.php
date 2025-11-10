@extends('client.layouts.master')

@section('content')
    <!-- Account Header -->
    <section class="account-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 text-center text-lg-start">
                    <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="User Avatar" class="user-avatar">
                </div>
                <div class="col-lg-10 text-center text-lg-start">
                    <h1>Welcome Back, {{ Auth::user()->name }}</h1>
                    <p class="lead mb-0">Member since {{ Auth::user()->created_at->format('F Y') }} |
                        {{ count($reservations) }} Reservations</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Account Content -->
    <div class="container">
        <div class="row">
            <!-- Account Navigation -->
            <div class="col-lg-3">
                <div class="account-nav">
                    <ul class="nav flex-column" id="accountTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="reservations-tab" data-bs-toggle="tab" href="#reservations"
                                role="tab">
                                <i class="fas fa-calendar-alt me-2"></i>My Reservations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">
                                <i class="fas fa-history me-2"></i>Order History
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab">
                                <i class="fas fa-star me-2"></i>My Reviews
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">
                                <i class="fas fa-cog me-2"></i>Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="nav-link text-danger" href="#"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="tab-content" id="accountTabsContent">

                    <!-- Reservations Tab -->
                    <div class="tab-pane fade show active" id="reservations" role="tabpanel">
                        <div class="account-card">
                            <h3>My Reservations</h3>

                            @if ($reservations->count() > 0)
                                @foreach ($reservations as $reservation)
                                    <div class="reservation-card">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <h5>Reservation #RSV-{{ $reservation->id }}</h5>
                                                <p class="text-muted mb-0">
                                                    {{ $reservation->reservation_date->format('F d, Y') }} at
                                                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('g:i A') }}
                                                </p>
                                            </div>
                                            <div>
                                                <span class="reservation-status status-{{ $reservation->status }}">
                                                    {{ ucfirst($reservation->status) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="reservation-details mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Code:</strong> {{ $reservation->code ?? 'N/A' }}
                                                    </p>
                                                    <p><strong>Table:</strong> {{ $reservation->table->name }}
                                                        ({{ $reservation->table->code }})
                                                    </p>
                                                    <p><strong>Guests:</strong> {{ $reservation->guest_count }} people</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Capacity:</strong> {{ $reservation->table->capacity }}
                                                        people</p>
                                                    <p><strong>Table Type:</strong> {{ $reservation->table->description }}
                                                    </p>
                                                </div>
                                            </div>

                                            @if ($reservation->special_requests)
                                                <div class="special-requests">
                                                    <p><strong>Special Requests:</strong>
                                                        {{ $reservation->special_requests }}</p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if ($reservation->status == 'pending' || $reservation->status == 'confirmed')
                                                    @php
                                                        try {
                                                            // Ensure proper datetime parsing
                                                            $reservationDateTime = \Carbon\Carbon::parse(
                                                                $reservation->reservation_date .
                                                                    ' ' .
                                                                    $reservation->reservation_time,
                                                            );
                                                            $now = \Carbon\Carbon::now();
                                                            $canCancel = $reservationDateTime->gt($now);
                                                        } catch (Exception $e) {
                                                            // Fallback: assume it can be cancelled if there's a parsing error
                                                            $canCancel = true;
                                                        }
                                                    @endphp
                                                    @if ($canCancel)
                                                        <button class="btn btn-sm btn-outline-danger cancel-reservation"
                                                            data-reservation-id="{{ $reservation->id }}">
                                                            Cancel Reservation
                                                        </button>
                                                    @else
                                                        <span class="text-muted">Cannot cancel past reservation</span>
                                                    @endif
                                                @elseif ($reservation->status == 'cancelled')
                                                    <span class="text-muted text-danger">Reservation Cancelled</span>
                                                @endif
                                            </div>
                                            <div>
                                                <small class="text-muted">Created:
                                                    {{ $reservation->created_at->format('M d, Y g:i A') }}</small>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <h4>No Reservations Yet</h4>
                                    <p class="text-muted">You haven't made any reservations yet.</p>
                                    <a href="{{ route('index') }}#reservation" class="btn btn-spice">Make a Reservation</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-pane fade" id="orders" role="tabpanel">
                        <div class="account-card">
                            <h3>Order History</h3>

                            @if ($orders->count() > 0)
                                @foreach ($orders as $order)
                                    <div class="order-card">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <h5>{{ $order->order_number }}</h5>
                                                <p class="text-muted mb-0">Placed on
                                                    {{ $order->created_at->format('F d, Y g:i A') }}</p>
                                            </div>
                                            <div>
                                                <span class="order-status status-{{ $order->status }}">
                                                    {{ $order->statusText() }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="order-items mb-3">
                                            @foreach ($order->items->take(2) as $item)
                                                <div class="d-flex align-items-center mb-2">
                                                    <img src="{{ asset($item->menu->image) }}"
                                                        alt="{{ $item->menu_name }}" width="60" height="60"
                                                        style="object-fit: cover; border-radius: 5px;"
                                                        onerror="this.src='{{ asset('default/dummy_600x400_ffffff_cccccc.png') }}'">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">{{ $item->menu_name }}</h6>
                                                        <small>Rs. {{ number_format($item->price, 2) }} x
                                                            {{ $item->quantity }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if ($order->items->count() > 2)
                                                <div class="text-center">
                                                    <small class="text-muted">+{{ $order->items->count() - 2 }} more
                                                        items</small>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span
                                                    class="badge bg-light text-dark">{{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</span>
                                                @if ($order->table)
                                                    <span class="badge bg-info ms-2">Table:
                                                        {{ $order->table->name }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h5 class="mb-0">Total: Rs. {{ number_format($order->total, 2) }}</h5>
                                            </div>
                                        </div>

                                        @if ($order->special_instructions)
                                            <div class="special-requests mt-3">
                                                <p><strong>Special Instructions:</strong>
                                                    {{ $order->special_instructions }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h4>No Orders Yet</h4>
                                    <p class="text-muted">You haven't placed any orders yet.</p>
                                    <a href="{{ route('menu') }}" class="btn btn-spice">Start Ordering</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews Tab -->
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="account-card">
                            <h3>Your Reviews</h3>

                            <div class="review-card">
                                <div class="d-flex justify-content-between">
                                    <div class="stars">
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                        <i class="fas fa-star active"></i>
                                    </div>
                                    <span class="review-date">Posted on June 18, 2023</span>
                                </div>
                                <h5>Amazing Lamprais!</h5>
                                <p>The Lamprais was absolutely delicious - the flavors were perfectly balanced and it
                                    reminded
                                    me of my grandmother's cooking. The packaging was also very secure and the food arrived
                                    hot.
                                    Will definitely order again!</p>
                                <div class="d-flex">
                                    <img src="https://images.unsplash.com/photo-1559847844-5315695dadae?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1398&q=80"
                                        alt="Review Photo" class="me-2" width="80" height="80"
                                        style="object-fit: cover; border-radius: 5px;">
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-spice">View All Reviews</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .account-nav .nav-link {
            color: #333;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .account-nav .nav-link.active,
        .account-nav .nav-link:hover {
            background: #dc3545;
            color: white;
        }

        .account-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .reservation-card,
        .order-card,
        .review-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .reservation-card:hover,
        .order-card:hover,
        .review-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .reservation-status,
        .order-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d1edff;
            color: #0c5460;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #d4edda;
            color: #155724;
        }

        .stars {
            color: #ffc107;
        }

        .stars .far {
            color: #e9ecef;
        }

        .special-requests {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #dc3545;
        }

        .btn-spice {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-spice:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Cancel reservation functionality
            $('.cancel-reservation').on('click', function() {
                const reservationId = $(this).data('reservation-id');
                const button = $(this);

                console.log('Reservation ID:', reservationId);
                console.log('Route URL:', '{{ route('reservation.cancel', ['id' => ':id']) }}'.replace(
                    ':id', reservationId));

                Swal.fire({
                    title: 'Cancel Reservation?',
                    text: "Are you sure you want to cancel this reservation?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, cancel it!',
                    cancelButtonText: 'Keep Reservation'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        button.html('<i class="fas fa-spinner fa-spin me-2"></i>Cancelling...');
                        button.prop('disabled', true);

                        // Use simple URL construction
                        $.ajax({
                            url: '/reservation/' + reservationId + '/cancel',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            dataType: 'json',
                            success: function(response) {
                                console.log('Success response:', response);
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Cancelled!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        // Force page reload after successful cancellation
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message
                                    });
                                    button.html('Cancel Reservation');
                                    button.prop('disabled', false);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Cancellation error:', xhr);
                                console.error('Status:', status);
                                console.error('Error:', error);

                                let errorMsg = 'Failed to cancel reservation';
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.message) {
                                        errorMsg = response.message;
                                    }
                                } catch (e) {
                                    // Use default error message
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMsg
                                });
                                button.html('Cancel Reservation');
                                button.prop('disabled', false);
                            }
                        });
                    }
                });
            });

            // Tab functionality
            $('#accountTabs a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Update active tab in navigation
            $('#accountTabs a').on('shown.bs.tab', function(e) {
                localStorage.setItem('activeAccountTab', $(e.target).attr('href'));
            });

            // Restore active tab on page load
            var activeTab = localStorage.getItem('activeAccountTab');
            if (activeTab) {
                $('#accountTabs a[href="' + activeTab + '"]').tab('show');
            }
        });
    </script>
@endpush
