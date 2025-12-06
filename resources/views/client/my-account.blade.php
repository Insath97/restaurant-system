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
                        {{ $reservations->total() }} Reservations | {{ $reviews->total() }} Reviews | {{ $orders->total() }} Orders</p>
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
                                @if($reservations->total() > 0)
                                    <span class="badge bg-danger ms-2">{{ $reservations->total() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">
                                <i class="fas fa-history me-2"></i>Order History
                                @if($orders->total() > 0)
                                    <span class="badge bg-danger ms-2">{{ $orders->total() }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab">
                                <i class="fas fa-star me-2"></i>My Reviews
                                @if($reviews->total() > 0)
                                    <span class="badge bg-danger ms-2">{{ $reviews->total() }}</span>
                                @endif
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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0">My Reservations</h3>
                                @if($reservations->total() > 0)
                                    <span class="text-muted">Showing {{ $reservations->firstItem() }} to {{ $reservations->lastItem() }} of {{ $reservations->total() }} reservations</span>
                                @endif
                            </div>

                            @if ($reservations->count() > 0)
                                @foreach ($reservations as $reservation)
                                    <div class="reservation-card">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <h5>Reservation #{{ $reservation->code }}</h5>
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
                                                    <p><strong>Code:</strong> {{ $reservation->code ?? 'N/A' }}</p>
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
                                                            $reservationDateTime = \Carbon\Carbon::parse(
                                                                $reservation->reservation_date .
                                                                    ' ' .
                                                                    $reservation->reservation_time,
                                                            );
                                                            $now = \Carbon\Carbon::now();
                                                            $canCancel = $reservationDateTime->gt($now);
                                                        } catch (Exception $e) {
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

                                                <!-- Review Button for Reservation -->
                                                @if ($reservation->status == 'completed')
                                                    @php
                                                        $hasReview = $reservation
                                                            ->reviews()
                                                            ->where('user_id', auth()->id())
                                                            ->exists();
                                                    @endphp
                                                    @if (!$hasReview)
                                                        <button class="btn btn-sm btn-spice add-review-btn ms-2"
                                                            data-bs-toggle="modal" data-bs-target="#reviewModal"
                                                            data-type="reservation" data-id="{{ $reservation->id }}"
                                                            data-title="Reservation #{{ $reservation->code }}">
                                                            <i class="fas fa-star me-1"></i>Add Review
                                                        </button>
                                                    @else
                                                        <span class="badge bg-success ms-2">
                                                            <i class="fas fa-check me-1"></i>Reviewed
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div>
                                                <small class="text-muted">Created:
                                                    {{ $reservation->created_at->format('M d, Y g:i A') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Pagination for Reservations -->
                                <div class="mt-4">
                                    {{ $reservations->withQueryString()->fragment('reservations')->links('pagination::bootstrap-5') }}
                                </div>
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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0">Order History</h3>
                                @if($orders->total() > 0)
                                    <span class="text-muted">Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders</span>
                                @endif
                            </div>

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

                                        <!-- Review Button for Order -->
                                        <div class="mt-3">
                                            @if ($order->status == 'completed')
                                                @php
                                                    $hasReview = $order
                                                        ->reviews()
                                                        ->where('user_id', auth()->id())
                                                        ->exists();
                                                    $hasReservation = $order->reservation_id != null;
                                                    $canReview = !$hasReview && !$hasReservation;
                                                    $alreadyReviewed = $hasReview;
                                                    $reviewWithReservation = $hasReservation;
                                                @endphp

                                                @if ($canReview)
                                                    <button class="btn btn-sm btn-spice add-review-btn"
                                                        data-bs-toggle="modal" data-bs-target="#reviewModal"
                                                        data-type="order" data-id="{{ $order->id }}"
                                                        data-title="Order #{{ $order->order_number }}">
                                                        <i class="fas fa-star me-1"></i>Add Review
                                                    </button>
                                                @elseif ($alreadyReviewed)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Reviewed
                                                    </span>
                                                @elseif ($reviewWithReservation)
                                                    <small class="text-muted">Review available with reservation</small>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Pagination for Orders -->
                                <div class="mt-4">
                                    {{ $orders->withQueryString()->fragment('orders')->links('pagination::bootstrap-5') }}
                                </div>
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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0">Your Reviews</h3>
                                @if($reviews->total() > 0)
                                    <span class="text-muted">Showing {{ $reviews->firstItem() }} to {{ $reviews->lastItem() }} of {{ $reviews->total() }} reviews</span>
                                @endif
                            </div>

                            @if ($reviews->count() > 0)
                                @foreach ($reviews as $review)
                                    <div class="review-card">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h5 class="mb-1">{{ $review->review_title }}</h5>
                                                <div class="stars mb-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="fas fa-star active"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                    <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
                                                </div>
                                            </div>
                                            <span
                                                class="review-date text-muted">{{ $review->created_at->format('F d, Y') }}</span>
                                        </div>
                                        <p class="mb-3">{{ $review->comment }}</p>
                                        <div class="d-flex align-items-center flex-wrap">
                                            @php
                                                $reviewType = class_basename($review->reviewable_type ?? '');
                                                $reviewReference = 'N/A';

                                                if ($review->relationLoaded('reviewable') && $review->reviewable) {
                                                    if ($review->reviewable_type === 'App\Models\Order') {
                                                        $reviewReference = $review->reviewable->order_number ?? 'N/A';
                                                    } elseif ($review->reviewable_type === 'App\Models\Reservation') {
                                                        $reviewReference = $review->reviewable->code ?? 'N/A';
                                                    }
                                                }
                                            @endphp

                                            <span
                                                class="badge {{ $review->reviewable_type === 'App\Models\Order' ? 'bg-success' : 'bg-primary' }} me-2 mb-1">
                                                {{ $reviewType }} #{{ $reviewReference }}
                                            </span>

                                            <small class="text-muted">
                                                @if ($review->reviewable_type === 'App\Models\Order' && $review->food_quality)
                                                    Food Quality: {{ $review->food_quality }}/5
                                                @elseif ($review->reviewable_type === 'App\Models\Reservation')
                                                    @if ($review->service_quality)
                                                        Service: {{ $review->service_quality }}/5
                                                    @endif
                                                    @if ($review->service_quality && $review->ambiance)
                                                        •
                                                    @endif
                                                    @if ($review->ambiance)
                                                        Ambiance: {{ $review->ambiance }}/5
                                                    @endif
                                                @endif
                                                @if ($review->would_recommend)
                                                    • <i class="fas fa-thumbs-up text-success"></i> Recommended
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Pagination for Reviews -->
                                <div class="mt-4">
                                    {{ $reviews->withQueryString()->fragment('reviews')->links('pagination::bootstrap-5') }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-star fa-3x text-muted mb-3"></i>
                                    <h4>No Reviews Yet</h4>
                                    <p class="text-muted">You haven't written any reviews yet.</p>
                                    <p class="text-muted">Complete your orders or reservations to leave reviews.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Write a Review</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="reviewForm" method="POST" action="{{ route('reviews.store') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="reviewable_type" id="reviewable_type">
                        <input type="hidden" name="reviewable_id" id="reviewable_id">

                        <div class="mb-4">
                            <h6 id="review-title" class="text-muted">Review for: <span id="review-item-title"></span>
                            </h6>
                        </div>

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label">Overall Rating <span class="text-danger">*</span></label>
                            <div class="rating-container">
                                <div class="stars-input">
                                    <!-- REVERSED ORDER: 5 is first, 1 is last -->
                                    <input type="radio" id="star5" name="rating" value="5" required>
                                    <label for="star5" class="star-label"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" class="star-label"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" class="star-label"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" class="star-label"><i class="fas fa-star"></i></label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" class="star-label"><i class="fas fa-star"></i></label>
                                </div>
                                <div class="rating-text mt-2">
                                    <small class="text-muted" id="rating-text">Select your rating</small>
                                </div>
                            </div>
                        </div>

                        <!-- Review Title -->
                        <div class="mb-3">
                            <label for="review_title" class="form-label">Review Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="review_title" name="review_title"
                                placeholder="Give your review a title" required>
                        </div>

                        <!-- Review Comment -->
                        <div class="mb-3">
                            <label for="comment" class="form-label">Your Review <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="comment" name="comment" rows="5"
                                placeholder="Share your experience with us..." required></textarea>
                        </div>

                        <!-- Would Recommend -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="would_recommend"
                                    name="would_recommend" value="1">
                                <label class="form-check-label" for="would_recommend">
                                    I would recommend to others
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-spice">
                            <i class="fas fa-paper-plane me-1"></i>Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .account-nav .nav-link.active,
        .account-nav .nav-link:hover {
            background: #dc3545;
            color: white;
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
            color: white;
        }

        /* Rating Stars Styles - RESTORED TO ORIGINAL ALIGNMENT */
        .rating-container {
            display: block;
        }

        .stars-input {
            display: flex;
            gap: 5px;
            justify-content: flex-start;
            margin-bottom: 5px;
        }

        .stars-input input[type="radio"] {
            display: none;
        }

        .stars-input .star-label {
            font-size: 2rem;
            color: #e9ecef;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-block;
        }

        /* REVERSED THE ORDER - Stars are now 5-1 from left to right */
        .stars-input {
            display: flex;
            flex-direction: row-reverse;
            /* This is the key fix! */
            justify-content: flex-end;
            gap: 5px;
        }

        /* Now the CSS sibling selectors work correctly */
        .stars-input input[type="radio"]:checked~.star-label i,
        .stars-input input[type="radio"]:checked+.star-label i {
            color: #ffc107 !important;
        }

        /* Hover effect */
        .stars-input .star-label:hover i,
        .stars-input .star-label:hover~.star-label i {
            color: #ffd700 !important;
        }

        /* Rating text styling */
        .rating-text {
            font-size: 0.9rem;
            color: #6c757d;
            min-height: 20px;
            text-align: left;
            width: 100%;
            margin-top: 8px;
        }

        .rating-text small {
            font-weight: 500;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
        }

        .order-items img {
            border-radius: 8px;
        }

        /* Pagination styling */
        .pagination {
            margin-bottom: 0;
        }

        .page-item.active .page-link {
            background: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .page-link {
            color: #dc3545;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
        }

        .page-link:hover {
            color: #dc3545;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
        }

        /* Card header styling */
        .account-card > .d-flex {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        /* Badge styling in nav */
        .account-nav .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            let currentReviewData = null;

            // Function to properly cleanup modal backdrop
            function cleanupModalBackdrop() {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
                $('body').css({
                    'overflow': '',
                    'padding-right': ''
                });
            }

            // Review Modal Setup
            $(document).on('click', '.add-review-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Get data from button
                const type = $(this).data('type');
                const id = $(this).data('id');
                const title = $(this).data('title');

                // Clean up any existing backdrop first
                cleanupModalBackdrop();

                // Store data
                currentReviewData = {
                    type: type,
                    id: id,
                    title: title
                };

                // Set hidden form values
                $('#reviewable_type').val('App\\Models\\' +
                    (type === 'order' ? 'Order' : 'Reservation'));
                $('#reviewable_id').val(id);
                $('#review-item-title').text(title);

                // Reset form
                $('#reviewForm')[0].reset();
                $('#rating-text').text('Select your rating');
                $('input[name="rating"]').prop('checked', false);

                // Show modal
                $('#reviewModal').modal('show');
            });

            // Simple star click handling
            $(document).on('click', '#reviewModal .star-label', function() {
                const radioId = $(this).attr('for');
                const value = $('#' + radioId).val();
                const ratingTexts = {
                    5: 'Excellent',
                    4: 'Very Good',
                    3: 'Good',
                    2: 'Fair',
                    1: 'Poor'
                };

                $('#rating-text').text(ratingTexts[value] || 'Select your rating');
                $('#' + radioId).prop('checked', true).trigger('change');
            });

            // Update text when rating changes
            $(document).on('change', '#reviewModal input[name="rating"]', function() {
                const value = $(this).val();
                const ratingTexts = {
                    5: 'Excellent',
                    4: 'Very Good',
                    3: 'Good',
                    2: 'Fair',
                    1: 'Poor'
                };
                $('#rating-text').text(ratingTexts[value] || 'Select your rating');
            });

            // Form submission
            $('#reviewForm').on('submit', function(e) {
                e.preventDefault();

                if (!currentReviewData) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'No review item selected. Please try again.'
                    });
                    return;
                }

                // Get form values
                const rating = $('input[name="rating"]:checked').val();
                const reviewTitle = $('#review_title').val().trim();
                const comment = $('#comment').val().trim();

                // Validate required fields
                let errors = [];

                if (!rating) errors.push('Please select an overall rating');
                if (!reviewTitle) errors.push('Please enter a review title');
                if (!comment) errors.push('Please enter your review comment');
                if (comment && comment.length < 10) errors.push(
                    'Review comment must be at least 10 characters');

                if (errors.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: errors.join('<br>')
                    });
                    return;
                }

                // Prepare form data
                const formData = {
                    _token: $('input[name="_token"]').val(),
                    reviewable_type: $('#reviewable_type').val(),
                    reviewable_id: $('#reviewable_id').val(),
                    review_title: reviewTitle,
                    comment: comment,
                    rating: rating,
                    would_recommend: $('#would_recommend').is(':checked') ? 1 : 0,
                    food_quality: null,
                    service_quality: null,
                    ambiance: null
                };

                // Show loading
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...'
                );

                // Send AJAX request
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Properly hide modal and cleanup
                            $('#reviewModal').modal('hide');
                            setTimeout(cleanupModalBackdrop, 300);

                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message
                            });
                            submitBtn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to submit review. Please try again.';
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.message) errorMessage = xhr.responseJSON
                                .message;
                            if (xhr.responseJSON.errors) {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = errors.join('<br>');
                            }
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            html: errorMessage
                        });
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Reset modal when closed
            $('#reviewModal').on('hidden.bs.modal', function() {
                currentReviewData = null;
                $('#reviewForm')[0].reset();
                $('input[name="rating"]').prop('checked', false);
                $('#rating-text').text('Select your rating');

                // Cleanup backdrop
                cleanupModalBackdrop();
            });

            // Also cleanup on cancel button click
            $(document).on('click', '.btn-secondary[data-bs-dismiss="modal"], .btn-close', function() {
                setTimeout(cleanupModalBackdrop, 150);
            });

            // Tab functionality with localStorage persistence
            $('#accountTabs a').on('click', function(e) {
                e.preventDefault();
                const target = $(this).attr('href');
                $(this).tab('show');
                localStorage.setItem('activeAccountTab', target);
            });

            $('#accountTabs a').on('shown.bs.tab', function(e) {
                localStorage.setItem('activeAccountTab', $(e.target).attr('href'));
            });

            // Restore active tab on page load
            const activeTab = localStorage.getItem('activeAccountTab');
            if (activeTab) {
                $('#accountTabs a[href="' + activeTab + '"]').tab('show');
            }

            // Handle pagination links - they should maintain tab state via fragment (#reservations, #orders, #reviews)
            $(document).on('click', '.pagination a', function(e) {
                // No need to prevent default - let the fragment identifier handle tab switching
                // The fragment in the URL (#reservations, #orders, #reviews) will activate the correct tab
            });

            // Cancel reservation functionality
            $(document).on('click', '.cancel-reservation', function() {
                const reservationId = $(this).data('reservation-id');
                Swal.fire({
                    title: 'Cancel Reservation?',
                    text: "Are you sure you want to cancel this reservation?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, cancel it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/reservation/' + reservationId + '/cancel',
                            method: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Cancelled!',
                                        text: response.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                    setTimeout(() => window.location.reload(), 1500);
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Failed to cancel reservation. Please try again.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
