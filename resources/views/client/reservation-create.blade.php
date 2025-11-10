@extends('client.layouts.master')

@section('content')
    <!-- Reservation Header -->
    <section class="reservation-header">
        <div class="container">
            <h1 class="display-4 mb-3">Book Your Table</h1>
            <p class="lead">Reserve your spot for an authentic dining experience</p>
        </div>
    </section>

    <!-- Reservation Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="reservation-card">
                        <h2 class="mb-4">Your Reservation Details</h2>
                        <p class="mb-4">You're reserving a table for your dine-in order. Please provide the following
                            details:</p>

                        <form id="reservationForm">
                            @csrf
                            @auth
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="reservationName" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="reservationName"
                                            value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="reservationEmail" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="reservationEmail"
                                            value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                </div>
                                <!-- Add this after the email field in your reservation form -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="customerPhone" class="form-label">Phone Number *</label>
                                        <input type="tel" class="form-control" id="customerPhone" name="customer_phone"
                                            required placeholder="Enter your phone number">
                                        <div class="invalid-feedback customerPhone-error"></div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Please <a href="{{ route('login') }}" class="alert-link">login</a> to make a reservation.
                                </div>
                            @endauth

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="reservationDate" class="form-label">Date *</label>
                                    <input type="date" class="form-control" id="reservationDate" name="reservation_date"
                                        required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    <div class="invalid-feedback reservationDate-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="reservationTime" class="form-label">Time *</label>
                                    <select class="form-select" id="reservationTime" name="reservation_time" required>
                                        <option value="" selected disabled>Select Time</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="11:30">11:30 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="12:30">12:30 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="13:30">1:30 PM</option>
                                        <option value="17:00">5:00 PM</option>
                                        <option value="17:30">5:30 PM</option>
                                        <option value="18:00">6:00 PM</option>
                                        <option value="18:30">6:30 PM</option>
                                        <option value="19:00">7:00 PM</option>
                                        <option value="19:30">7:30 PM</option>
                                        <option value="20:00">8:00 PM</option>
                                        <option value="20:30">8:30 PM</option>
                                    </select>
                                    <div class="invalid-feedback reservationTime-error"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="guestCount" class="form-label">Number of Guests *</label>
                                    <select class="form-select" id="guestCount" name="guest_count" required>
                                        <option value="1">1 Person</option>
                                        <option value="2" selected>2 People</option>
                                        <option value="3">3 People</option>
                                        <option value="4">4 People</option>
                                        <option value="5">5 People</option>
                                        <option value="6">6 People</option>
                                        <option value="7">7 People</option>
                                        <option value="8">8 People</option>
                                        <option value="9">9+ People (Please call)</option>
                                    </select>
                                    <div class="invalid-feedback guestCount-error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="tableSelect" class="form-label">Select Table *</label>
                                    <select class="form-select" id="tableSelect" name="table_id" required disabled>
                                        <option value="" selected disabled>First select date, time and guests
                                        </option>
                                    </select>
                                    <div class="invalid-feedback tableSelect-error"></div>
                                    <div class="form-text" id="tableInfo"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="specialRequests" class="form-label">Special Requests</label>
                                <textarea class="form-control" id="specialRequests" name="special_requests" rows="3"
                                    placeholder="Any dietary restrictions, allergies, or special occasions?"></textarea>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">
                                    I agree to the <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#termsModal">Reservation Policy</a>
                                </label>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-spice px-5 py-3" id="reservationSubmit"
                                    @guest disabled @endguest>
                                    <i class="fas fa-calendar-check me-2"></i>
                                    <span class="submit-text">Confirm Reservation</span>
                                    <span class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                                </button>
                            </div>

                            <p class="text-center mt-3 small text-muted">We'll contact you to confirm your reservation.
                                For same-day bookings, please call +94 76 123 4567.</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Summary Sidebar -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="orderSummary" aria-labelledby="orderSummaryLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="orderSummaryLabel">Your Order</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="mb-4">
                <h6>Items ({{ $cart->items->sum('quantity') }})</h6>

                @foreach ($cart->items as $item)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <div>
                            <p class="mb-0">{{ $item->menu->name }}</p>
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
                <div class="d-flex justify-content-between mb-2">
                    <span>Service Charge (10%):</span>
                    <span>Rs. {{ number_format($cart->service_charge, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total:</span>
                    <span>Rs. {{ number_format($cart->total, 2) }}</span>
                </div>
            </div>

            <button class="btn btn-outline-secondary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#editOrderModal">
                <i class="fas fa-edit me-2"></i> Edit Order
            </button>
        </div>
    </div>

    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reservation Policy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Reservation Duration</h6>
                    <p>Tables are reserved for 2 hours. Extensions may be possible depending on availability.</p>

                    <h6 class="mt-4">Cancellation</h6>
                    <p>Please cancel at least 2 hours before your reservation time to avoid a cancellation fee.</p>

                    <h6 class="mt-4">Late Arrivals</h6>
                    <p>We hold reservations for 15 minutes past the booked time. After that, your table may be given to
                        waiting guests.</p>

                    <h6 class="mt-4">Special Requests</h6>
                    <p>While we try to accommodate all requests, we cannot guarantee specific tables or arrangements.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-spice" data-bs-dismiss="modal">I Understand</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Your Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>To edit your order items, you'll need to return to your cart. Your reservation details will be saved.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="{{ route('cart.get') }}" class="btn btn-spice">Go to Cart</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer"></div>

    <!-- Floating Order Summary Button -->
    <button class="btn btn-spice position-fixed" style="bottom: 30px; right: 30px; z-index: 1000;"
        data-bs-toggle="offcanvas" data-bs-target="#orderSummary" aria-controls="orderSummary">
        <i class="fas fa-shopping-bag me-2"></i> View Order
    </button>
@endsection

@push('styles')
    <style>
        /* Hide cart toggle button on reservation page */
        .cart-toggle {
            display: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function() {
            const reservationForm = $('#reservationForm');
            const dateInput = $('#reservationDate');
            const timeInput = $('#reservationTime');
            const guestInput = $('#guestCount');
            const tableSelect = $('#tableSelect');
            const tableInfo = $('#tableInfo');
            const submitBtn = $('#reservationSubmit');
            const submitText = submitBtn.find('.submit-text');
            const spinner = submitBtn.find('.spinner-border');
            const specialRequests = $('#specialRequests');
            const customerPhone = $('#customerPhone'); // Add this

            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.attr('min', tomorrow.toISOString().split('T')[0]);

            // Set default date to tomorrow
            dateInput.val(tomorrow.toISOString().split('T')[0]);

            // Function to check availability and load tables
            function checkAvailability() {
                const date = dateInput.val();
                const time = timeInput.val();
                const guests = guestInput.val();

                if (date && time && guests) {
                    // Show loading state
                    tableSelect.html('<option value="" selected disabled>Loading available tables...</option>');
                    tableSelect.prop('disabled', true);
                    tableInfo.text('Checking table availability...').removeClass('text-success text-danger')
                        .addClass('text-info');

                    $.ajax({
                        url: '{{ route('reservation.available-tables') }}',
                        method: 'POST',
                        data: {
                            reservation_date: date,
                            reservation_time: time,
                            guest_count: guests,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                tableSelect.html(
                                    '<option value="" selected disabled>Select a table</option>');

                                if (response.tables.length > 0) {
                                    $.each(response.tables, function(index, table) {
                                        tableSelect.append(
                                            $('<option>', {
                                                value: table.id,
                                                text: `${table.name} (Code: ${table.code}) - Capacity: ${table.capacity} people`,
                                                'data-capacity': table.capacity,
                                                'data-description': table.description
                                            })
                                        );
                                    });
                                    tableSelect.prop('disabled', false);
                                    tableInfo.text(`${response.tables.length} available table(s) found`)
                                        .removeClass('text-info text-danger').addClass('text-success');
                                } else {
                                    tableSelect.html(
                                        '<option value="" selected disabled>No tables available</option>'
                                    );
                                    tableSelect.prop('disabled', true);
                                    tableInfo.text(
                                        'No tables available for selected criteria. Please try different date/time or contact us.'
                                    ).removeClass('text-info text-success').addClass(
                                        'text-danger');
                                }
                            } else {
                                throw new Error(response.message || 'Failed to fetch tables');
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                            tableSelect.html(
                                '<option value="" selected disabled>Error loading tables</option>');
                            tableSelect.prop('disabled', true);
                            tableInfo.text('Error loading tables. Please try again.').removeClass(
                                'text-info text-success').addClass('text-danger');

                            let errorMsg = 'Error checking table availability';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMsg = response.message;
                                }
                            } catch (e) {
                                console.error('Error parsing response:', e);
                            }

                            showToast(errorMsg, 'error');
                        }
                    });
                }
            }

            // Event listeners for form changes
            [dateInput, timeInput, guestInput].forEach(input => {
                input.on('change', checkAvailability);
            });

            // Show table info when selected
            tableSelect.on('change', function() {
                const selectedOption = $(this).find('option:selected');
                if (selectedOption.val()) {
                    const description = selectedOption.data('description');
                    tableInfo.text(description || 'No additional information').removeClass(
                        'text-success text-danger').addClass('text-muted');
                } else {
                    tableInfo.text('');
                }
            });

            // Form submission - UPDATED for complete order processing
            reservationForm.on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();

                // Clear previous errors
                reservationForm.find('.is-invalid').removeClass('is-invalid');
                reservationForm.find('.invalid-feedback').text('');

                let hasErrors = false;

                // Basic validation
                if (!dateInput.val()) {
                    dateInput.addClass('is-invalid');
                    $('.reservationDate-error').text('Please select a reservation date');
                    hasErrors = true;
                }

                if (!timeInput.val()) {
                    timeInput.addClass('is-invalid');
                    $('.reservationTime-error').text('Please select a reservation time');
                    hasErrors = true;
                }

                if (!guestInput.val()) {
                    guestInput.addClass('is-invalid');
                    $('.guestCount-error').text('Please select number of guests');
                    hasErrors = true;
                }

                if (!customerPhone.val()) {
                    customerPhone.addClass('is-invalid');
                    $('.customerPhone-error').text('Please enter your phone number');
                    hasErrors = true;
                }

                if (!tableSelect.val() || tableSelect.prop('disabled')) {
                    tableSelect.addClass('is-invalid');
                    $('.tableSelect-error').text('Please select an available table');
                    hasErrors = true;
                }

                if (!$('#termsCheck').is(':checked')) {
                    showToast('Please agree to the reservation policy', 'error');
                    hasErrors = true;
                }

                if (hasErrors) {
                    showToast('Please fill all required fields correctly', 'error');
                    return false;
                }

                // Show loading state
                submitText.text('Processing Order...');
                spinner.removeClass('d-none');
                submitBtn.prop('disabled', true);

                // Prepare form data for COMPLETE order processing
                const formData = {
                    table_id: tableSelect.val(),
                    reservation_date: dateInput.val(),
                    reservation_time: timeInput.val(),
                    guest_count: guestInput.val(),
                    customer_phone: customerPhone.val(), // Add phone
                    special_requests: specialRequests.val(),
                    order_type: 'dine_in', // Set order type
                    payment_method: 'cash_on_delivery', // Set payment method
                    _token: '{{ csrf_token() }}'
                };

                console.log('Submitting complete order:', formData);

                $.ajax({
                    url: '{{ route('reservation.complete-order') }}', // New route for complete processing
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        console.log('Order response:', response);

                        if (response.success) {
                            showSuccessMessage(response.message ||
                                'Order confirmed successfully!');

                            // Redirect to order success page
                            setTimeout(() => {
                                window.location.href = response.redirect_url;
                            }, 2000);
                        } else {
                            // Show specific error message from server
                            showToast(response.message || 'Failed to create order',
                                'error');

                            // Reset button state on failure
                            resetButtonState();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Order error:', xhr.responseText);

                        // Reset button state
                        resetButtonState();

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            console.log('Validation errors:', errors);

                            // Display errors under each field
                            $.each(errors, function(field, messages) {
                                const input = reservationForm.find('[name="' + field +
                                    '"]');
                                const errorContainer = reservationForm.find('.' +
                                    field + '-error');

                                input.addClass('is-invalid');
                                if (errorContainer.length) {
                                    errorContainer.text(messages[0]);
                                } else {
                                    input.after('<div class="invalid-feedback">' +
                                        messages[0] + '</div>');
                                }
                            });

                            showToast('Please check the form for errors', 'error');
                        } else if (xhr.status === 400) {
                            // Bad request - show specific message
                            try {
                                const response = JSON.parse(xhr.responseText);
                                showToast(response.message || 'Invalid request', 'error');
                            } catch (e) {
                                showToast('Invalid request', 'error');
                            }
                        } else {
                            let errorMsg = 'An error occurred while processing your order';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMsg = response.message;
                                }
                            } catch (e) {
                                console.error('Error parsing response:', e);
                            }

                            showToast(errorMsg, 'error');
                        }
                    }
                });

                return false;
            });

            // Helper function to show success message with confirmation
            function showSuccessMessage(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: message,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745',
                    timer: 3000,
                    timerProgressBar: true
                });
            }

            // Helper function to show toast notifications
            function showToast(message, type) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: type,
                    text: message
                });
            }

            // Helper function to reset button state
            function resetButtonState() {
                submitText.text('Confirm Reservation & Order');
                spinner.addClass('d-none');
                submitBtn.prop('disabled', false);
            }

            // Real-time validation for date (prevent past dates)
            dateInput.on('change', function() {
                const selectedDate = new Date($(this).val());
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selectedDate <= today) {
                    showToast('Please select a future date', 'error');
                    $(this).val('');
                    tableSelect.html(
                        '<option value="" selected disabled>First select date, time and guests</option>'
                    );
                    tableSelect.prop('disabled', true);
                    tableInfo.text('');
                }
            });

            // Prevent form submission on enter key
            reservationForm.on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });

            initializeDateRestrictions();

            // Initial check for availability if all fields are filled
            if (dateInput.val() && timeInput.val() && guestInput.val()) {
                checkAvailability();
            }
        });
    </script>
@endpush
