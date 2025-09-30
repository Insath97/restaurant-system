@extends('client.layouts.master')

@section('content')
    <section class="hero-section" style="min-height: 150px">
        <div class="container">
            <h1 class="display-3 mb-4">Our Gallery</h1>
            <p class="lead">A visual journey through our restaurant, dishes, and the vibrant atmosphere of Sri Lankan
                dining</p>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-5">
        <div class="container">
            <!-- Gallery Filter -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="text-center">
                        <div class="btn-group" role="group" aria-label="Gallery filter">
                            <button type="button" class="btn btn-spice active" data-filter="all">All</button>
                            <button type="button" class="btn btn-outline-spice" data-filter="food">Food</button>
                            <button type="button" class="btn btn-outline-spice"
                                data-filter="restaurant">Restaurant</button>
                            <button type="button" class="btn btn-outline-spice" data-filter="chefs">Chefs</button>
                            <button type="button" class="btn btn-outline-spice" data-filter="events">Events</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Grid -->
            <div class="row gallery-grid">
                <!-- Food Category -->
                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="food">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1371&q=80"
                            alt="Seafood Platter" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Seafood Platter</h5>
                                <p>Fresh catch from Sri Lankan waters</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1371&q=80"
                                        data-title="Seafood Platter" data-description="Fresh catch from Sri Lankan waters">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="food">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1585032226651-759b368d7246?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1592&q=80"
                            alt="Traditional Rice & Curry" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Traditional Rice & Curry</h5>
                                <p>Authentic Sri Lankan flavors</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1585032226651-759b368d7246?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1592&q=80"
                                        data-title="Traditional Rice & Curry"
                                        data-description="Authentic Sri Lankan flavors">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="food">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Spicy Appetizers" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Spicy Appetizers</h5>
                                <p>Perfect start to your meal</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                                        data-title="Spicy Appetizers" data-description="Perfect start to your meal">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Restaurant Category -->
                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="restaurant">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1559847844-5315695dadae?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1398&q=80"
                            alt="Dining Area" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Elegant Dining Area</h5>
                                <p>Comfortable and authentic ambiance</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1559847844-5315695dadae?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1398&q=80"
                                        data-title="Elegant Dining Area"
                                        data-description="Comfortable and authentic ambiance">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="restaurant">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Outdoor Seating" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Outdoor Seating</h5>
                                <p>Al fresco dining experience</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1601050690597-df0568f70950?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                                        data-title="Outdoor Seating" data-description="Al fresco dining experience">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chefs Category -->
                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="chefs">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1603360946369-dc9bb6258143?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Master Chef" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Master Chef</h5>
                                <p>20+ years of culinary expertise</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1603360946369-dc9bb6258143?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                                        data-title="Master Chef" data-description="20+ years of culinary expertise">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Events Category -->
                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="events">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                            alt="Special Events" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Private Events</h5>
                                <p>Perfect for celebrations</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1519225421980-715cb0215aed?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80"
                                        data-title="Private Events" data-description="Perfect for celebrations">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="events">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1469&q=80"
                            alt="Cooking Classes" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Cooking Classes</h5>
                                <p>Learn authentic Sri Lankan recipes</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1469&q=80"
                                        data-title="Cooking Classes"
                                        data-description="Learn authentic Sri Lankan recipes">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 gallery-item" data-category="food">
                    <div class="gallery-card">
                        <img src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1374&q=80"
                            alt="Desserts" class="gallery-img">
                        <div class="gallery-overlay">
                            <div class="gallery-content">
                                <h5>Traditional Desserts</h5>
                                <p>Sweet endings to your meal</p>
                                <div class="gallery-actions">
                                    <button class="btn btn-sm btn-light view-image" data-bs-toggle="modal"
                                        data-bs-target="#imageModal"
                                        data-image="https://images.unsplash.com/photo-1546833999-b9f581a1996d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1374&q=80"
                                        data-title="Traditional Desserts" data-description="Sweet endings to your meal">
                                        <i class="fas fa-expand"></i> View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" alt="" class="img-fluid modal-image">
                    <p class="mt-3 modal-description">Image description</p>
                </div>
            </div>
        </div>
    </div>
@endsection
