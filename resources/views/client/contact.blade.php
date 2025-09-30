@extends('client.layouts.master')

@section('content')
    <section class="hero-section" style="min-height: 150px">
        <div class="container">
            <h1 class="display-3 mb-4">Contact Us</h1>
            <p class="lead">We'd love to hear from you</p>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="contact-card">
                        <h2 class="mb-4">Get In Touch</h2>
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <input type="text" class="form-control" placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <input type="email" class="form-control" placeholder="Your Email" required>
                                </div>
                            </div>
                            <input type="text" class="form-control" placeholder="Subject">
                            <textarea class="form-control" rows="5" placeholder="Your Message" required></textarea>
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-spice">Send Message</button>
                            </div>
                        </form>
                    </div>

                    <div class="contact-card mt-4">
                        <h3 class="mb-4">Frequently Asked Questions</h3>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne">
                                        What are your opening hours?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We're open daily from 11:00 AM to 10:00 PM. On Fridays and Saturdays, we stay
                                        open until 11:00 PM.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo">
                                        Do you take reservations for large groups?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes! We welcome group reservations of up to 20 people. For larger parties,
                                        please call us directly at +94 76 123 4567.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree">
                                        Is parking available?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                    data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We have limited parking spaces available behind the restaurant. Additional
                                        parking can be found at the Galle Face Green parking lot, just a 5-minute walk
                                        away.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-card">
                        <h2 class="mb-4">Our Location</h2>
                        <div class="map-container mb-4">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.798511757686!2d79.84521441532853!3d6.914657295003785!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2596b8d5f07b1%3A0x8bd6f8d5d5f5f5f5!2sGalle%20Face%20Green!5e0!3m2!1sen!2slk!4v1620000000000!5m2!1sen!2slk"
                                width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <h5>Address</h5>
                                    <p>
                                        123 Galle Road<br>
                                        Colombo 03<br>
                                        Sri Lanka
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <h5>Phone</h5>
                                    <p>
                                        +94 11 234 5678<br>
                                        +94 76 123 4567
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <h5>Email</h5>
                                    <p>
                                        info@colombospice.com<br>
                                        reservations@colombospice.com
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="contact-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <h5>Opening Hours</h5>
                                    <p>
                                        Daily: 11:00 AM - 10:00 PM<br>
                                        Fri-Sat: Until 11:00 PM
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
