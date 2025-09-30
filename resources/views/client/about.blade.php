@extends('client.layouts.master')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section" style="min-height: 150px">
        <div class="container">
            <h1 class="display-3 mb-4">Our Story</h1>
            <p class="lead">Preserving Sri Lankan culinary traditions since 2010</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                        alt="Colombo Spice Restaurant" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6">
                    <h2 class="mb-4">Authentic Sri Lankan Flavors</h2>
                    <p>Founded in 2010 by Chef Ranjith Perera, Colombo Spice brings the authentic taste of Sri Lanka to
                        Colombo's culinary scene. What began as a small family-run eatery has grown into one of the
                        city's most beloved restaurants, celebrated for its commitment to traditional recipes and
                        locally-sourced ingredients.</p>
                    <p>Our mission is to preserve Sri Lanka's rich culinary heritage while creating memorable dining
                        experiences. Each dish tells a story of the island's diverse cultural influences - from Malay
                        and Indian to Dutch and Portuguese.</p>

                    <div class="mt-5">
                        <div class="about-feature">
                            <div class="about-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div>
                                <h5>Traditional Recipes</h5>
                                <p>Authentic dishes prepared using time-honored Sri Lankan cooking techniques passed
                                    down through generations.</p>
                            </div>
                        </div>

                        <div class="about-feature">
                            <div class="about-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div>
                                <h5>Local Ingredients</h5>
                                <p>We source directly from Sri Lankan farmers, fishermen, and spice growers to ensure
                                    freshness and support local communities.</p>
                            </div>
                        </div>

                        <div class="about-feature">
                            <div class="about-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <div>
                                <h5>Award-Winning</h5>
                                <p>Recognized as "Best Sri Lankan Restaurant" by Sri Lanka Tourism for five consecutive
                                    years.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Meet Our Team</h2>

            <div class="row">
                <div class="col-md-4">
                    <div class="team-card">
                        <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="Chef Ranjith Perera"
                            class="team-img">
                        <h4>Chef Ranjith Perera</h4>
                        <p class="text-muted">Executive Chef & Founder</p>
                        <p>With over 25 years of experience, Chef Ranjith specializes in traditional Sri Lankan cuisine
                            with a modern presentation.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="team-card">
                        <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Nayomi Fernando" class="team-img">
                        <h4>Nayomi Fernando</h4>
                        <p class="text-muted">Head Pastry Chef</p>
                        <p>Creator of our famous watalappan and other Sri Lankan desserts, trained in both Colombo and
                            Paris.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="team-card">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Dinesh Silva" class="team-img">
                        <h4>Dinesh Silva</h4>
                        <p class="text-muted">Beverage Director</p>
                        <p>Expert in Ceylon teas and traditional Sri Lankan beverages, with extensive knowledge of spice
                            pairings.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our History -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Journey</h2>

            <div class="timeline">
                <div class="timeline-item">
                    <h4>2010</h4>
                    <p>Colombo Spice opens its doors as a small 20-seat restaurant in Colombo 03, specializing in
                        home-style Sri Lankan cuisine.</p>
                </div>

                <div class="timeline-item">
                    <h4>2013</h4>
                    <p>Featured in Lonely Planet's guide to Sri Lanka, bringing international recognition to our
                        authentic flavors.</p>
                </div>

                <div class="timeline-item">
                    <h4>2015</h4>
                    <p>Expanded to our current location with a full-service dining room and open kitchen concept.</p>
                </div>

                <div class="timeline-item">
                    <h4>2018</h4>
                    <p>First awarded "Best Sri Lankan Restaurant" by Sri Lanka Tourism, an honor we've maintained
                        annually.</p>
                </div>

                <div class="timeline-item">
                    <h4>2020</h4>
                    <p>Launched our spice masterclass series, teaching guests about traditional Sri Lankan cooking
                        techniques.</p>
                </div>

                <div class="timeline-item">
                    <h4>2023</h4>
                    <p>Celebrated our 13th anniversary with the launch of our cookbook "Tastes of Sri Lanka".</p>
                </div>
            </div>
        </div>
    </section>

@endsection
