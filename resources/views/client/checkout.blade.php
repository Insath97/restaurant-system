@extends('client.layouts.master')

@section('content')
    <!-- Checkout Header -->
    <section class="checkout-header">
        <div class="container">
            <h1 class="display-4 mb-3">Complete Your Order</h1>
            <p class="lead">Review your items and enter your details to complete your purchase</p>
        </div>
    </section>

    <!-- Checkout Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Order Form -->
                <div class="col-lg-8 mb-5 mb-lg-0">
                    <div class="checkout-card">
                        <h2 class="mb-4">Customer Information</h2>

                        <form id="checkoutForm">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="customer_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                        value="{{ Auth::user()->name }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="customer_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="customer_email" name="customer_email"
                                        value="{{ Auth::user()->email }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="customer_phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone"
                                    required>
                            </div>

                            @if ($orderType === 'takeaway')
                                <div class="mb-4">
                                    <label for="delivery_address" class="form-label">Delivery Address *</label>
                                    <textarea class="form-control" id="delivery_address" name="delivery_address" rows="3" required
                                        placeholder="Enter your complete delivery address"></textarea>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    You have selected Dine-In. Your order will be prepared for pickup at the restaurant.
                                </div>
                            @endif

                            <div class="mb-4">
                                <label for="special_instructions" class="form-label">Special Instructions</label>
                                <textarea class="form-control" id="special_instructions" name="special_instructions" rows="2"
                                    placeholder="Any special instructions for your order?"></textarea>
                            </div>

                            <h2 class="mb-4 mt-5">Payment Method</h2>

                            <div class="mb-4">
                                <div class="payment-method" onclick="selectPayment('cash_on_delivery')">
                                    <input type="radio" name="payment_method" value="cash_on_delivery"
                                        id="cash_on_delivery" class="d-none" checked>
                                    <i class="fas fa-money-bill-wave"></i>
                                    <div>
                                        <h5 class="mb-1">Cash on Delivery</h5>
                                        <p class="mb-0 small">Pay when you receive your order</p>
                                    </div>
                                </div>

                                <div class="payment-method" onclick="selectPayment('credit_card')">
                                    <input type="radio" name="payment_method" value="credit_card" id="credit_card"
                                        class="d-none">
                                    <i class="fas fa-credit-card"></i>
                                    <div>
                                        <h5 class="mb-1">Credit/Debit Card</h5>
                                        <p class="mb-0 small">Pay securely with your card</p>
                                    </div>
                                </div>

                                <div class="payment-method" onclick="selectPayment('paypal')">
                                    <input type="radio" name="payment_method" value="paypal" id="paypal"
                                        class="d-none">
                                    <i class="fab fa-paypal"></i>
                                    <div>
                                        <h5 class="mb-1">PayPal</h5>
                                        <p class="mb-0 small">Pay with your PayPal account</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Credit Card Form (hidden by default) -->
                            <div class="card-form" id="cardForm" style="display: none;">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="cardNumber" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="cardNumber"
                                            placeholder="1234 5678 9012 3456">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cardName" class="form-label">Name on Card</label>
                                        <input type="text" class="form-control" id="cardName" placeholder="John Doe">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cardExpiry" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="cardCvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cardCvv" placeholder="123">
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">
                                    I agree to the <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#termsModal">Terms
                                        & Conditions</a>
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-spice btn-lg" id="submitOrderBtn">
                                    <i class="fas fa-shopping-bag me-2"></i> Complete Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="checkout-card">
                        <h2 class="mb-4">Your Order</h2>

                        <div class="order-summary mb-4">
                            <h5 class="mb-3">Items ({{ $cart->items->sum('quantity') }})</h5>

                            @foreach ($cart->items as $item)
                                <div class="order-item">
                                    <div>
                                        <h6 class="mb-1">{{ $item->menu->name }}</h6>
                                        <small class="text-muted">{{ $item->quantity }} x Rs.
                                            {{ number_format($item->price, 2) }}</small>
                                    </div>
                                    <span>Rs. {{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Rs. {{ number_format($cart->subtotal, 2) }}</span>
                            </div>
                            @if ($orderType === 'takeaway')
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Delivery Fee:</span>
                                    <span>Rs. 300.00</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span>Service Charge (10%):</span>
                                <span>Rs. {{ number_format($cart->service_charge, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total:</span>
                                <span>Rs.
                                    {{ number_format($cart->total + ($orderType === 'takeaway' ? 300 : 0), 2) }}</span>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            @if ($orderType === 'takeaway')
                                Delivery time: 30-45 minutes
                            @else
                                Preparation time: 20-30 minutes
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
    <script>
        function selectPayment(method) {
            // Remove selected class from all payment methods
            document.querySelectorAll('.payment-method').forEach(pm => {
                pm.classList.remove('selected');
            });

            // Add selected class to clicked payment method
            event.currentTarget.classList.add('selected');

            // Set the radio button value
            document.getElementById(method).checked = true;

            // Show/hide card form
            if (method === 'credit_card') {
                document.getElementById('cardForm').style.display = 'block';
            } else {
                document.getElementById('cardForm').style.display = 'none';
            }
        }

        // Handle checkout form submission
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitOrderBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
            submitBtn.disabled = true;

            const formData = new FormData(this);

            fetch('{{ route('checkout.process') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                        submitBtn.innerHTML = '<i class="fas fa-shopping-bag me-2"></i> Complete Order';
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing your order. Please try again.'
                    });
                    submitBtn.innerHTML = '<i class="fas fa-shopping-bag me-2"></i> Complete Order';
                    submitBtn.disabled = false;
                });
        });

        // Initialize first payment method as selected
        document.querySelector('.payment-method').classList.add('selected');
    </script>
@endpush
