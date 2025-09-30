@extends('client.layouts.master')

@section('content')
    <!-- Menu Header -->
    <section class="menu-header">
        <div class="container">
            <h1 class="display-3 mb-4">Our Authentic Sri Lankan Menu</h1>
            <p class="lead">Experience the rich flavors of Sri Lanka's culinary heritage</p>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="menu-section">
        <div class="container">
            <h2 class="text-center section-title">Our Menu</h2>
            <p class="text-center mb-5">Discover the rich flavors of Sri Lanka through our carefully curated menu featuring
                traditional dishes with a modern touch.</p>

            <!-- Menu Tabs -->
            <ul class="nav nav-pills justify-content-center mb-5" id="menuTabs" role="tablist">
                @foreach ($categories as $index => $category)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $index === 0 ? 'active' : '' }}"
                            id="{{ Str::slug($category->name) }}-tab" data-bs-toggle="pill"
                            data-bs-target="#{{ Str::slug($category->name) }}" type="button"
                            data-category-id="{{ $category->id }}">
                            {{ $category->name }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <!-- Menu Content -->
            <div class="tab-content" id="menuTabsContent">
                @foreach ($categories as $index => $category)
                    @php
                        $menus = $category->menus;
                        $perPage = 3;
                        $currentPage = request()->input('page_' . $category->id, 1);
                        $currentItems = $menus->forPage($currentPage, $perPage);
                        $totalPages = ceil($menus->count() / $perPage);
                    @endphp

                    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ Str::slug($category->name) }}"
                        role="tabpanel" data-category-id="{{ $category->id }}" data-total-pages="{{ $totalPages }}"
                        data-current-page="{{ $currentPage }}">

                        <!-- Loading Spinner -->
                        <div class="text-center loading-spinner d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <!-- Menu Items Container -->
                        <div class="menu-items-container">
                            <div class="row">
                                @foreach ($currentItems as $menu)
                                    <div class="col-md-6 col-lg-4 fade-in">
                                        <div class="menu-card">
                                            <img src="{{ asset($menu->image) }}" alt="{{ $menu->name }}" loading="lazy">
                                            <div class="menu-card-body">
                                                <h3 class="menu-card-title">{{ $menu->name }}</h3>
                                                <div class="menu-card-rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= floor($menu->rating ?? 4.5))
                                                            <i class="fas fa-star"></i>
                                                        @elseif ($i == ceil($menu->rating ?? 4.5) && ($menu->rating ?? 4.5) != floor($menu->rating ?? 4.5))
                                                            <i class="fas fa-star-half-alt"></i>
                                                        @else
                                                            <i class="far fa-star"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <p class="mb-3">{{ $menu->description }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="menu-card-price">Rs.
                                                        {{ number_format($menu->price, 2) }}</span>
                                                    <button class="btn btn-add-to-cart" data-menu-id="{{ $menu->id }}"
                                                        data-menu-name="{{ $menu->name }}"
                                                        data-menu-price="{{ $menu->price }}"
                                                        data-menu-image="{{ asset($menu->image) }}">
                                                        Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if ($totalPages > 1)
                            <nav aria-label="{{ $category->name }} pagination" class="mt-5">
                                <ul class="pagination justify-content-center">
                                    <!-- Previous Button -->
                                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                        <a class="page-link pagination-link" href="#"
                                            data-category-id="{{ $category->id }}" data-page="{{ $currentPage - 1 }}"
                                            tabindex="{{ $currentPage == 1 ? '-1' : '' }}">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>

                                    <!-- Page Numbers -->
                                    @for ($page = 1; $page <= $totalPages; $page++)
                                        <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                                            <a class="page-link pagination-link" href="#"
                                                data-category-id="{{ $category->id }}" data-page="{{ $page }}">
                                                {{ $page }}
                                            </a>
                                        </li>
                                    @endfor

                                    <!-- Next Button -->
                                    <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                                        <a class="page-link pagination-link" href="#"
                                            data-category-id="{{ $category->id }}" data-page="{{ $currentPage + 1 }}">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - initializing pagination');

            // Use event delegation for pagination links
            document.addEventListener('click', function(e) {
                if (e.target.closest('.pagination-link')) {
                    e.preventDefault();
                    console.log('Pagination link clicked');
                    handlePaginationClick(e.target.closest('.pagination-link'));
                }
            });

            // Function to generate star rating HTML
            function generateStarRating(rating) {
                let stars = '';
                const finalRating = rating || 4.5;
                for (let i = 1; i <= 5; i++) {
                    if (i <= Math.floor(finalRating)) {
                        stars += '<i class="fas fa-star"></i>';
                    } else if (i === Math.ceil(finalRating) && finalRating !== Math.floor(finalRating)) {
                        stars += '<i class="fas fa-star-half-alt"></i>';
                    } else {
                        stars += '<i class="far fa-star"></i>';
                    }
                }
                return stars;
            }

            // Function to generate menu item HTML
            function generateMenuItemHTML(menu) {
                return `
            <div class="col-md-6 col-lg-4 fade-in">
                <div class="menu-card">
                    <img src="${menu.image}" alt="${menu.name}" loading="lazy">
                    <div class="menu-card-body">
                        <h3 class="menu-card-title">${menu.name}</h3>
                        <div class="menu-card-rating">
                            ${generateStarRating(menu.rating)}
                        </div>
                        <p class="mb-3">${menu.description}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="menu-card-price">Rs. ${parseFloat(menu.price).toFixed(2)}</span>
                            <button class="btn btn-add-to-cart"
                                data-menu-id="${menu.id}"
                                data-menu-name="${menu.name}"
                                data-menu-price="${menu.price}"
                                data-menu-image="${menu.image}">Add to Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
            }

            // Function to generate pagination HTML
            function generatePaginationHTML(categoryId, currentPage, totalPages) {
                if (totalPages <= 1) return '';

                let paginationHTML = `
            <ul class="pagination justify-content-center">
                <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link pagination-link" href="#" data-category-id="${categoryId}" data-page="${currentPage - 1}" ${currentPage === 1 ? 'tabindex="-1"' : ''}>
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
        `;

                for (let page = 1; page <= totalPages; page++) {
                    paginationHTML += `
                <li class="page-item ${page === currentPage ? 'active' : ''}">
                    <a class="page-link pagination-link" href="#" data-category-id="${categoryId}" data-page="${page}">
                        ${page}
                    </a>
                </li>
            `;
                }

                paginationHTML += `
                <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                    <a class="page-link pagination-link" href="#" data-category-id="${categoryId}" data-page="${currentPage + 1}">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        `;

                return paginationHTML;
            }

            // Single function to handle pagination
            function handlePaginationClick(link) {
                console.log('handlePaginationClick called');

                const categoryId = link.getAttribute('data-category-id');
                const page = parseInt(link.getAttribute('data-page'));

                console.log('Category ID:', categoryId, 'Page:', page);

                const tabPane = document.querySelector(`.tab-pane[data-category-id="${categoryId}"]`);

                if (!tabPane) {
                    console.error('Tab pane not found for category:', categoryId);
                    alert('Error: Tab pane not found');
                    return;
                }

                const totalPages = parseInt(tabPane.getAttribute('data-total-pages')) || 1;
                console.log('Total pages:', totalPages);

                // Validate page number
                if (page < 1 || page > totalPages) {
                    console.log('Invalid page number:', page);
                    return;
                }

                // Show loading spinner
                const loadingSpinner = tabPane.querySelector('.loading-spinner');
                const menuItemsContainer = tabPane.querySelector('.menu-items-container');
                const paginationContainer = tabPane.querySelector('nav');

                // Check if elements exist
                if (!loadingSpinner) {
                    console.error('Loading spinner not found');
                    return;
                }
                if (!menuItemsContainer) {
                    console.error('Menu items container not found');
                    return;
                }

                loadingSpinner.classList.remove('d-none');
                menuItemsContainer.style.opacity = '0.5';
                if (paginationContainer) {
                    paginationContainer.style.opacity = '0.5';
                }

                // AJAX request to fetch paginated data
                fetch(`/menu/paginate?category_id=${categoryId}&page=${page}`)
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);

                        // Check if menus data exists
                        if (!data.menus || !Array.isArray(data.menus)) {
                            throw new Error('Invalid data format received - menus array missing');
                        }

                        // Generate menu items HTML
                        let menuItemsHTML = '<div class="row">';

                        if (data.menus.length === 0) {
                            menuItemsHTML =
                                '<div class="col-12 text-center"><p>No menu items found for this page.</p></div>';
                        } else {
                            data.menus.forEach(menu => {
                                menuItemsHTML += generateMenuItemHTML(menu);
                            });
                            menuItemsHTML += '</div>';
                        }

                        // Update menu items
                        menuItemsContainer.innerHTML = menuItemsHTML;

                        // Update pagination
                        const newPaginationHTML = generatePaginationHTML(categoryId, data.currentPage, data
                            .totalPages);
                        if (paginationContainer) {
                            if (newPaginationHTML) {
                                paginationContainer.innerHTML = newPaginationHTML;
                            } else {
                                paginationContainer.innerHTML = ''; // Remove pagination if only 1 page
                            }
                        }

                        // Update tab pane attributes
                        tabPane.setAttribute('data-total-pages', data.totalPages);
                        tabPane.setAttribute('data-current-page', data.currentPage);

                        console.log('Page updated successfully');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Show error message in the menu container
                        menuItemsContainer.innerHTML = `
                    <div class="col-12 text-center">
                        <p class="text-danger">Error loading menu items: ${error.message}</p>
                        <button class="btn btn-primary" onclick="location.reload()">Reload Page</button>
                    </div>
                `;
                    })
                    .finally(() => {
                        // Hide loading spinner
                        loadingSpinner.classList.add('d-none');
                        menuItemsContainer.style.opacity = '1';
                        if (paginationContainer) {
                            paginationContainer.style.opacity = '1';
                        }
                    });
            }

            console.log('Pagination initialized successfully');
        });
    </script>
@endpush
