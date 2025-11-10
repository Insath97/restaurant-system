@extends('client.layouts.master')

@section('content')
    <!-- Order Type Header -->
    <section class="order-type-header">
        <div class="container">
            <h1 class="display-4 mb-3">How Would You Like Your Order?</h1>
            <p class="lead">Choose between takeaway or dine-in experience</p>
        </div>
    </section>

    <!-- Order Type Selection -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="order-type-card">
                        <h2 class="mb-5">Select Order Type</h2>

                        <form id="orderTypeForm" action="{{ route('order-type.select') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-4 mb-md-0">
                                    <div class="order-option" id="takeawayOption" onclick="selectOption('takeaway')">
                                        <input type="radio" name="order_type" value="takeaway" id="takeaway"
                                            class="d-none">
                                        <i class="fas fa-utensils"></i>
                                        <h3>Takeaway</h3>
                                        <p>Enjoy our delicious food at home or wherever you prefer</p>
                                        <div class="mt-3">
                                            <span class="badge bg-warning text-dark">30-45 min preparation</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="order-option" id="dineInOption" onclick="selectOption('dine_in')">
                                        <input type="radio" name="order_type" value="dine_in" id="dine_in"
                                            class="d-none">
                                        <i class="fas fa-store"></i>
                                        <h3>Dine-In</h3>
                                        <p>Experience our authentic atmosphere and service</p>
                                        <div class="mt-3">
                                            <span class="badge bg-warning text-dark">Reservation recommended</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <a href="javascript:history.back()" class="btn btn-outline-spice">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Cart
                                </a>
                                <button type="submit" class="btn btn-spice" id="continueBtn" disabled>
                                    Continue <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        function selectOption(type) {
            console.log('Selected option:', type); // Debug log

            // Remove selected class from all options
            document.querySelectorAll('.order-option').forEach(option => {
                option.classList.remove('selected');
            });

            // Add selected class to clicked option
            let optionElement;
            if (type === 'takeaway') {
                optionElement = document.getElementById('takeawayOption');
            } else if (type === 'dine_in') {
                optionElement = document.getElementById('dineInOption');
            }

            if (optionElement) {
                optionElement.classList.add('selected');

                // Set the radio button value
                document.getElementById(type).checked = true;

                // Enable continue button
                document.getElementById('continueBtn').disabled = false;

                console.log('Option selected successfully'); // Debug log
            } else {
                console.error('Option element not found for type:', type); // Debug log
            }
        }

        // Alternative approach using event listeners for better reliability
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event listeners to both options
            document.getElementById('takeawayOption').addEventListener('click', function() {
                selectOption('takeaway');
            });

            document.getElementById('dineInOption').addEventListener('click', function() {
                selectOption('dine_in');
            });

            // Handle form submission
            document.getElementById('orderTypeForm').addEventListener('submit', function(e) {
                e.preventDefault();

                // Check if an option is selected
                if (!document.querySelector('input[name="order_type"]:checked')) {
                    alert('Please select an order type');
                    return;
                }

                const formData = new FormData(this);

                // Show loading state
                const btn = document.getElementById('continueBtn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Continuing...';
                btn.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data); // Debug log
                    if (data.success && data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        throw new Error('Invalid response from server');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });
        });
    </script>
@endpush
