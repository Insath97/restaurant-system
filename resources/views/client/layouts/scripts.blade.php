<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Separate jQuery AJAX function for authenticated users
    function addToCartAjax(menuId, buttonElement) {
        const isAuthenticated = $('meta[name="user-auth"]').attr('content') === 'true';
        if (!isAuthenticated) return false;

        // Show loading state on button
        if (buttonElement) {
            buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            buttonElement.disabled = true;
        }

        // Otherwise proceed with AJAX
        $.ajax({
            url: '/cart/add',
            method: 'POST',
            data: {
                menu_id: menuId,
                quantity: 1
            },
            success: function(data) {
                if (data.success) {
                    updateCartBadge(data.cart_count);
                    loadCartFromDatabase();

                } else if (data.message === 'already_in_cart') {
                    Swal.fire({
                        icon: "info",
                        title: "Item Already in Cart",
                        text: "This item is already in your cart. You can increase the quantity from the cart sidebar.",
                        confirmButtonText: 'View Cart',
                        showCancelButton: true,
                        cancelButtonText: 'Continue Shopping'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show cart sidebar
                            const cartSidebar = document.querySelector('.cart-sidebar');
                            if (cartSidebar) {
                                cartSidebar.classList.add('show');
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Failed',
                        text: data.message || 'Failed to add item to cart',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error adding to cart:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Error adding item to cart. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                // Reset button state
                if (buttonElement) {
                    buttonElement.innerHTML = 'Add to Cart';
                    buttonElement.disabled = false;
                }
            }
        });
    }


    // Function to update cart badge
    function updateCartBadge(count) {
        const cartBadge = $('.cart-badge');
        if (cartBadge.length) {
            cartBadge.text(count);
            cartBadge.css('display', 'flex');
        }
    }

    // Function to load cart items from database for authenticated users
    function loadCartFromDatabase() {
        const isAuthenticated = $('meta[name="user-auth"]').attr('content') === 'true';

        if (!isAuthenticated) {
            return; // Only for authenticated users
        }

        $.ajax({
            url: '/cart',
            method: 'GET',
            success: function(data) {
                if (data.success) {
                    displayDBCartItems(data.items, data.totals);
                    updateCartBadge(data.cart_count);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading cart:', error);
            }
        });
    }

    // Function to display database cart items in the sidebar
    function displayDBCartItems(items, totals) {
        const cartItemsContainer = document.getElementById('cartItemsContainer');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const cartSubtotalElement = document.getElementById('cartSubtotal');
        const cartServiceElement = document.getElementById('cartService');
        const cartTotalElement = document.getElementById('cartTotal');

        if (items && items.length > 0) {
            emptyCartMessage.style.display = 'none';

            let cartHTML = '';
            items.forEach((item) => {
                const itemTotal = item.price * item.quantity;
                cartHTML += `
                <div class="cart-item">
                    <div class="d-flex align-items-center">
                        <img src="${item.image}" alt="${item.name}" class="cart-item-img me-3">
                        <div>
                            <h6 class="mb-1">${item.name}</h6>
                            <p class="mb-0">Rs. ${parseFloat(item.price).toFixed(2)} x ${item.quantity}</p>
                        </div>
                    </div>
                    <div>
                        <span class="d-block text-end">Rs. ${itemTotal.toFixed(2)}</span>
                        <div class="btn-group btn-group-sm mt-2">
                            <button class="btn btn-outline-secondary decrease-item-db" data-item-id="${item.id}">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button class="btn btn-outline-danger remove-item-db" data-item-id="${item.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="btn btn-outline-secondary increase-item-db" data-item-id="${item.id}">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            });

            cartItemsContainer.innerHTML = cartHTML;
            attachDBCartEventListeners();
        } else {
            emptyCartMessage.style.display = 'block';
            cartItemsContainer.innerHTML = ''; // clears only items, not the message
        }

        // Update totals
        if (totals) {
            cartSubtotalElement.textContent = `Rs. ${parseFloat(totals.subtotal).toFixed(2)}`;
            cartServiceElement.textContent = `Rs. ${parseFloat(totals.service_charge).toFixed(2)}`;
            cartTotalElement.textContent = `Rs. ${parseFloat(totals.total).toFixed(2)}`;
        }
    }


    // Function to attach event listeners for database cart items
    function attachDBCartEventListeners() {
        // Remove item from database cart
        document.querySelectorAll('.remove-item-db').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                removeFromDBCart(itemId, this);
            });
        });

        // Decrease quantity in database cart
        document.querySelectorAll('.decrease-item-db').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                updateDBCartQuantity(itemId, -1, this);
            });
        });

        // Increase quantity in database cart
        document.querySelectorAll('.increase-item-db').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-item-id');
                updateDBCartQuantity(itemId, 1, this);
            });
        });
    }

    // Function to remove item from database cart with immediate DOM update
    function removeFromDBCart(itemId, buttonElement) {
        // Get the cart item element to remove
        const cartItemElement = buttonElement.closest('.cart-item');

        // Remove from DOM immediately for better UX
        if (cartItemElement) {
            cartItemElement.style.opacity = '0.5'; // Visual feedback
            cartItemElement.style.transition = 'opacity 0.3s ease';

            setTimeout(() => {
                cartItemElement.remove();

                // Check if cart is empty after removal
                const cartItemsContainer = document.querySelector('.cart-items');
                const emptyCartMessage = document.getElementById('emptyCartMessage');
                const remainingItems = cartItemsContainer.querySelectorAll('.cart-item');

                if (remainingItems.length === 0) {
                    // Show empty cart message
                    if (emptyCartMessage) {
                        emptyCartMessage.style.display = 'block';
                    }

                    // Reset totals to zero
                    const cartSubtotalElement = document.getElementById('cartSubtotal');
                    const cartServiceElement = document.getElementById('cartService');
                    const cartTotalElement = document.getElementById('cartTotal');

                    if (cartSubtotalElement && cartServiceElement && cartTotalElement) {
                        cartSubtotalElement.textContent = 'Rs. 0.00';
                        cartServiceElement.textContent = 'Rs. 0.00';
                        cartTotalElement.textContent = 'Rs. 0.00';
                    }

                    // Update badge to zero
                    updateCartBadge(0);
                }
            }, 300);
        }

        // Send AJAX request to server
        $.ajax({
            url: '/cart/remove',
            method: 'DELETE',
            data: {
                item_id: itemId
            },
            success: function(data) {
                if (data.success) {
                    // Update cart badge with server count
                    updateCartBadge(data.cart_count);

                    // If there are still items, reload totals
                    const cartItemsContainer = document.querySelector('.cart-items');
                    const remainingItems = cartItemsContainer.querySelectorAll('.cart-item');
                    if (remainingItems.length > 0) {
                        loadCartTotals();
                    }
                } else {
                    alert('Failed to remove item from cart');
                    // Optionally: Re-add the item to DOM if removal failed
                }
            },
            error: function(xhr, status, error) {
                console.error('Error removing from cart:', error);
                alert('Error removing item from cart');
                // Optionally: Re-add the item to DOM if removal failed
            }
        });
    }

    // Function to update quantity in database cart with immediate DOM update
    function updateDBCartQuantity(itemId, change, buttonElement) {
        // Get the cart item element
        const cartItemElement = buttonElement.closest('.cart-item');
        const quantityElement = cartItemElement.querySelector('.mb-0');
        const itemTotalElement = cartItemElement.querySelector('.d-block.text-end');

        // Get current values from DOM
        const currentQuantityText = quantityElement.textContent;
        const price = parseFloat(currentQuantityText.split('Rs. ')[1].split(' x')[0]);
        const currentQuantity = parseInt(currentQuantityText.split('x')[1].trim());
        const newQuantity = currentQuantity + change;

        // Validate minimum quantity
        if (newQuantity < 1) {
            alert('Quantity cannot be less than 1');
            return;
        }

        // Validate maximum quantity
        if (newQuantity > 10) {
            alert('Maximum quantity per item is 10');
            return;
        }

        // Update DOM immediately for better UX
        quantityElement.textContent = `Rs. ${price.toFixed(2)} x ${newQuantity}`;
        const newItemTotal = price * newQuantity;
        itemTotalElement.textContent = `Rs. ${newItemTotal.toFixed(2)}`;

        // Add visual feedback
        buttonElement.disabled = true;
        setTimeout(() => {
            buttonElement.disabled = false;
        }, 500);

        // Send AJAX request to server
        $.ajax({
            url: '/cart/update',
            method: 'PUT',
            data: {
                item_id: itemId,
                change: change
            },
            success: function(data) {
                if (data.success) {
                    // Update cart badge
                    updateCartBadge(data.cart_count);
                    // Reload totals from server
                    loadCartTotals();
                } else {
                    alert(data.message || 'Failed to update cart');
                    // Revert DOM changes if update failed
                    revertDOMChanges(quantityElement, itemTotalElement, price, currentQuantity);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating cart:', error);
                alert('Error updating cart');
                // Revert DOM changes if update failed
                revertDOMChanges(quantityElement, itemTotalElement, price, currentQuantity);
            }
        });
    }

    // Helper function to revert DOM changes if update fails
    function revertDOMChanges(quantityElement, itemTotalElement, price, originalQuantity) {
        quantityElement.textContent = `Rs. ${price.toFixed(2)} x ${originalQuantity}`;
        const originalTotal = price * originalQuantity;
        itemTotalElement.textContent = `Rs. ${originalTotal.toFixed(2)}`;
    }

    // Function to load only cart totals (without reloading entire cart)
    function loadCartTotals() {
        const isAuthenticated = $('meta[name="user-auth"]').attr('content') === 'true';

        if (!isAuthenticated) {
            return;
        }

        $.ajax({
            url: '/cart',
            method: 'GET',
            success: function(data) {
                if (data.success) {
                    // Update totals only
                    const cartSubtotalElement = document.getElementById('cartSubtotal');
                    const cartServiceElement = document.getElementById('cartService');
                    const cartTotalElement = document.getElementById('cartTotal');

                    if (cartSubtotalElement && cartServiceElement && cartTotalElement) {
                        cartSubtotalElement.textContent =
                            `Rs. ${parseFloat(data.totals.subtotal).toFixed(2)}`;
                        cartServiceElement.textContent =
                            `Rs. ${parseFloat(data.totals.service_charge).toFixed(2)}`;
                        cartTotalElement.textContent = `Rs. ${parseFloat(data.totals.total).toFixed(2)}`;
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading cart totals:', error);
            }
        });
    }

    // Cart functionality
    document.addEventListener('DOMContentLoaded', function() {
        const cartToggle = document.getElementById('cartToggle');
        const cartSidebar = document.querySelector('.cart-sidebar');
        const closeCart = document.getElementById('closeCart');
        const addToCartButtons = document.querySelectorAll('.btn-add-to-cart');
        const cartItemsContainer = document.querySelector('.cart-items');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        const cartSubtotalElement = document.getElementById('cartSubtotal');
        const cartServiceElement = document.getElementById('cartService');
        const cartTotalElement = document.getElementById('cartTotal');
        const cartBadge = document.querySelector('.cart-badge');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const viewCartBtn = document.getElementById('viewCartBtn');

        // Load cart from localStorage or initialize empty
        let cart = JSON.parse(localStorage.getItem('restaurantCart')) || [];

        // Load database cart if user is authenticated
        const isAuthenticated = $('meta[name="user-auth"]').attr('content') === 'true';

        if (isAuthenticated) {
            loadCartFromDatabase();
            updateCartBadge(0);
        } else {
            const itemCount = cart.reduce((count, item) => count + item.quantity, 0);
            updateCartBadge(itemCount);
        }

        // Toggle cart visibility
        if (cartToggle) {
            cartToggle.addEventListener('click', function() {
                cartSidebar.classList.toggle('show');
            });
        }

        if (closeCart) {
            closeCart.addEventListener('click', function() {
                cartSidebar.classList.remove('show');
            });
        }

        if (viewCartBtn) {
            viewCartBtn.addEventListener('click', function() {
                cartSidebar.classList.add('show');
            });
        }

        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function() {
                const isAuthenticated = $('meta[name="user-auth"]').attr('content') === 'true';
                const itemCount = isAuthenticated ?
                    (parseInt($('.cart-badge').text()) || 0) :
                    cart.reduce((sum, item) => sum + item.quantity, 0);

                if (itemCount === 0) {
                    alert('Your cart is empty. Please add items before proceeding to checkout.');
                    return;
                }

                if (isAuthenticated) {
                    alert('Proceeding to checkout with ' + itemCount + ' items. Total: ' +
                        cartTotalElement.textContent);
                    // window.location.href = '/checkout';
                } else {
                    alert('Proceeding to checkout with ' + itemCount + ' items. Total: ' +
                        cartTotalElement.textContent);
                }
            });
        }

        // Add to cart functionality
        if (addToCartButtons.length > 0) {
            addToCartButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Get data from button attributes first (more reliable)
                    const itemId = this.getAttribute('data-menu-id');
                    const itemName = this.getAttribute('data-menu-name');
                    const itemPrice = parseFloat(this.getAttribute('data-menu-price'));
                    const itemImage = this.getAttribute('data-menu-image');

                    // If data attributes are not available, fall back to DOM parsing
                    let finalItemName = itemName;
                    let finalItemPrice = itemPrice;
                    let finalItemImage = itemImage;

                    if (!finalItemName || !finalItemPrice || !finalItemImage) {
                        const menuCard = this.closest('.menu-card');
                        if (menuCard) {
                            finalItemName = menuCard.querySelector('.menu-card-title')
                                .textContent;
                            finalItemPrice = parseFloat(menuCard.querySelector(
                                    '.menu-card-price')
                                .textContent.replace('Rs. ', '').replace(',', ''));
                            finalItemImage = menuCard.querySelector('img').src;
                        }
                    }

                    // Check if user is authenticated
                    const isAuthenticated = $('meta[name="user-auth"]').attr('content') ===
                        'true';

                    if (isAuthenticated) {
                        // Use AJAX for authenticated users
                        addToCartAjax(itemId);
                    } else {
                        // Use localStorage for guest users
                        const existingItem = cart.find(item => item.id === itemId);

                        if (existingItem) {
                            Swal.fire({
                                icon: "info",
                                title: "Item already in cart",
                                text: "You can increase the quantity inside the cart."
                            });
                        } else {
                            cart.push({
                                id: itemId,
                                name: finalItemName,
                                price: finalItemPrice,
                                image: finalItemImage,
                                quantity: 1
                            });
                        }

                        updateCart();
                    }

                    // Animation feedback
                    this.innerHTML = '<i class="fas fa-check"></i> Added';
                    this.classList.add('btn-success');
                    setTimeout(() => {
                        this.innerHTML = 'Add to Cart';
                        this.classList.remove('btn-success');
                    }, 1000);
                });
            });
        }

        // Update cart display (for localStorage only)
        function updateCart() {
            // Save cart to localStorage
            localStorage.setItem('restaurantCart', JSON.stringify(cart));

            const cartItemsContainer = document.getElementById('cartItemsContainer');
            const emptyCartMessage = document.getElementById('emptyCartMessage');

            // Only update if user is not authenticated
            const isAuthenticated = $('meta[name="user-auth"]').attr('content') === 'true';
            if (isAuthenticated) {
                return; // Don't update localStorage display for authenticated users
            }

            // Update cart items only if elements exist
            if (cartItemsContainer && emptyCartMessage) {
                if (cart.length > 0) {
                    emptyCartMessage.style.display = 'none';

                    let cartHTML = '';
                    let subtotal = 0;

                    cart.forEach((item, index) => {
                        const itemTotal = item.price * item.quantity;
                        subtotal += itemTotal;

                        cartHTML += `
                                <div class="cart-item">
                                    <div class="d-flex align-items-center">
                                        <img src="${item.image}" alt="${item.name}" class="cart-item-img me-3">
                                        <div>
                                            <h6 class="mb-1">${item.name}</h6>
                                            <p class="mb-0">Rs. ${item.price.toFixed(2)} x ${item.quantity}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="d-block text-end">Rs. ${itemTotal.toFixed(2)}</span>
                                        <div class="btn-group btn-group-sm mt-2">
                                            <button class="btn btn-outline-secondary decrease-item" data-index="${index}">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <button class="btn btn-outline-danger remove-item" data-index="${index}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary increase-item" data-index="${index}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                    });

                    cartItemsContainer.innerHTML = cartHTML;

                    // Add event listeners to cart buttons
                    document.querySelectorAll('.remove-item').forEach(button => {
                        button.addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index'));
                            cart.splice(index, 1);
                            updateCart();
                        });
                    });

                    document.querySelectorAll('.decrease-item').forEach(button => {
                        button.addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index'));
                            if (cart[index].quantity > 1) {
                                cart[index].quantity -= 1;
                                updateCart();
                            }
                        });
                    });

                    document.querySelectorAll('.increase-item').forEach(button => {
                        button.addEventListener('click', function() {
                            const index = parseInt(this.getAttribute('data-index'));
                            cart[index].quantity += 1;
                            updateCart();
                        });
                    });


                } else {
                    emptyCartMessage.style.display = 'block';
                    cartItemsContainer.innerHTML = '';
                }
            }

            // Update totals only if elements exist
            if (cartSubtotalElement && cartServiceElement && cartTotalElement) {
                const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const serviceCharge = subtotal * 0.1;
                const total = subtotal + serviceCharge;

                cartSubtotalElement.textContent = `Rs. ${subtotal.toFixed(2)}`;
                cartServiceElement.textContent = `Rs. ${serviceCharge.toFixed(2)}`;
                cartTotalElement.textContent = `Rs. ${total.toFixed(2)}`;
            }

            const itemCount = cart.reduce((count, item) => count + item.quantity, 0);
            updateCartBadge(itemCount);
        }

        // Initialize cart after a short delay to ensure DOM is ready
        setTimeout(() => {
            const isAuthenticated = $('meta[name="user-auth"]').attr('content') === 'true';
            if (!isAuthenticated) {
                updateCart();
            }
        }, 100);

        // Animation on scroll
        const fadeElements = document.querySelectorAll('.fade-in');

        function checkScroll() {
            fadeElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight - 100) {
                    element.classList.add('show');
                }
            });
        }

        // Initial check
        checkScroll();

        // Check on scroll
        window.addEventListener('scroll', checkScroll);

        // Initialize date picker for reservation
        const today = new Date();
        const dateInput = document.getElementById('reservationDate');
        if (dateInput) {
            dateInput.min = today.toISOString().split('T')[0];

            // Set default date to tomorrow
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.value = tomorrow.toISOString().split('T')[0];
        }
    });
</script>
