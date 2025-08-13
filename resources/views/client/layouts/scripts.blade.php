<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
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

        let cart = [];

        // Toggle cart visibility
        cartToggle.addEventListener('click', function() {
            cartSidebar.classList.toggle('show');
        });

        closeCart.addEventListener('click', function() {
            cartSidebar.classList.remove('show');
        });

        viewCartBtn.addEventListener('click', function() {
            cartSidebar.classList.add('show');
        });

        checkoutBtn.addEventListener('click', function() {
            alert('Proceeding to checkout with ' + cart.reduce((sum, item) => sum + item.quantity, 0) +
                ' items. Total: ' + cartTotalElement.textContent);
        });

        // Add to cart functionality
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                const menuCard = this.closest('.menu-card');
                const itemName = menuCard.querySelector('.menu-card-title').textContent;
                const itemPrice = parseFloat(menuCard.querySelector('.menu-card-price')
                    .textContent.replace('Rs. ', ''));
                const itemImage = menuCard.querySelector('img').src;

                // Check if item already exists in cart
                const existingItem = cart.find(item => item.name === itemName);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        name: itemName,
                        price: itemPrice,
                        image: itemImage,
                        quantity: 1
                    });
                }

                updateCart();
                cartSidebar.classList.add('show');

                // Animation feedback
                this.innerHTML = '<i class="fas fa-check"></i> Added';
                this.classList.add('btn-success');
                setTimeout(() => {
                    this.innerHTML = 'Add to Cart';
                    this.classList.remove('btn-success');
                }, 1000);
            });
        });

        // Update cart display
        function updateCart() {
            // Update cart items
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

            // Update totals
            const serviceCharge = subtotal * 0.1;
            const total = subtotal + serviceCharge;

            cartSubtotalElement.textContent = `Rs. ${subtotal.toFixed(2)}`;
            cartServiceElement.textContent = `Rs. ${serviceCharge.toFixed(2)}`;
            cartTotalElement.textContent = `Rs. ${total.toFixed(2)}`;

            // Update badge
            const itemCount = cart.reduce((count, item) => count + item.quantity, 0);
            cartBadge.textContent = itemCount;
            cartBadge.style.display = itemCount > 0 ? 'flex' : 'none';
        }

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
        dateInput.min = today.toISOString().split('T')[0];

        // Set default date to tomorrow
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        dateInput.value = tomorrow.toISOString().split('T')[0];
    });
</script>
