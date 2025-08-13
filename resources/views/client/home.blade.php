@extends('client.layouts.master')

@section('content')
    <!-- Hero Section -->
    @include('client.home-components.hero')

    <!-- About Section -->
    @include('client.home-components.about')

    <!-- Special Offers Section -->
    @include('client.home-components.special-offer')

    <!-- Menu Section -->
    <section id="menu" class="menu-section">
        <div class="container">
            <h2 class="text-center section-title">Our Menu</h2>
            <p class="text-center mb-5">Discover the rich flavors of Sri Lanka through our carefully curated menu
                featuring traditional dishes with a modern touch.</p>

            <!-- Menu Tabs -->
            <ul class="nav nav-pills justify-content-center mb-5" id="menuTabs" role="tablist">
                @foreach ($categories as $index => $category)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                            id="{{ Str::slug($category->name) }}-tab" data-bs-toggle="pill"
                            data-bs-target="#{{ Str::slug($category->name) }}" type="button">{{ $category->name }}</button>
                    </li>
                @endforeach
            </ul>

            <!-- Menu Content -->
            <div class="tab-content" id="menuTabsContent">
                @foreach ($categories as $index => $category)
                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ Str::slug($category->name) }}"
                        role="tabpanel">
                        <div class="row">
                            @foreach ($category->menus as $menu)
                                <div class="col-md-6 col-lg-4 fade-in">
                                    <div class="menu-card">
                                        <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}">
                                        <div class="menu-card-body">
                                            <h3 class="menu-card-title">{{ $menu->name }}</h3>
                                            <div class="menu-card-rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                            </div>
                                            <p class="mb-3">{{ $menu->description }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="menu-card-price">Rs.
                                                    {{ number_format($menu->price, 2) }}</span>
                                                <button class="btn btn-add-to-cart">Add to Cart</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Chef Specials Section -->
    @include('client.home-components.chef')

    <!-- Reservation Section -->
    <section id="reservation" class="reservation-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="reservation-form fade-in">
                        <div class="reservation-header">
                            <i class="fas fa-calendar-alt"></i>
                            <h2>Make a Reservation</h2>
                            <p>Book your table in advance to enjoy an authentic Sri Lankan dining experience. For
                                parties of 8 or more, please call us directly.</p>
                        </div>

                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="reservationName" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="reservationName" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="reservationPhone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="reservationPhone" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="reservationEmail" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="reservationEmail">
                                </div>
                                <div class="col-md-6">
                                    <label for="reservationDate" class="form-label">Date *</label>
                                    <input type="date" class="form-control" id="reservationDate" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label for="reservationTime" class="form-label">Time *</label>
                                    <select class="form-select" id="reservationTime" required>
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
                                </div>
                                <div class="col-md-6">
                                    <label for="guestCount" class="form-label">Number of Guests *</label>
                                    <select class="form-select" id="guestCount" required>
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
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="tablePreference" class="form-label">Special Requests</label>
                                <textarea class="form-control" id="specialRequests" rows="3"
                                    placeholder="Any dietary restrictions, allergies, or special occasions?"></textarea>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="newsletterCheck">
                                <label class="form-check-label" for="newsletterCheck">
                                    Subscribe to our newsletter for special offers
                                </label>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-spice px-5 py-3">
                                    <i class="fas fa-calendar-check me-2"></i> Confirm Reservation
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

    <!-- Gallery Section -->
    @include('client.home-components.gallery')

    <!-- Testimonials Section -->
    @include('client.home-components.testimonial')
@endsection
