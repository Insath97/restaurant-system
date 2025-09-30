<div class="cart-sidebar">
    <div class="cart-header">
        <h5 class="mb-0">Your Order</h5>
        <button class="btn-close btn-close-white" id="closeCart"></button>
    </div>

    <div class="cart-items p-3">
        <!-- Cart items will be added here dynamically -->
        <div class="text-center py-5" id="emptyCartMessage">
            <i class="fas fa-shopping-cart fa-3x mb-3" style="color: #ddd;"></i>
            <p>Your cart is empty</p>
            <a href="{{ route('menu') }}" class="btn btn-spice">Browse Menu</a>
        </div>

        <div id="cartItemsContainer"></div>
    </div>

    <div class="cart-total">
        <div class="d-flex justify-content-between">
            <span>Subtotal:</span>
            <span id="cartSubtotal">Rs. 0.00</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Service Charge (10%):</span>
            <span id="cartService">Rs. 0.00</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Total:</span>
            <span id="cartTotal">Rs. 0.00</span>
        </div>
    </div>

    <div class="p-3">
        @auth
            <button class="btn btn-spice w-100 mb-3" id="checkoutBtn">Proceed to Checkout</button>
        @else
            <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="btn btn-spice w-100 mb-3">
                Login to Checkout
            </a>
        @endauth
        <button class="btn btn-outline-spice w-100" id="viewCartBtn">View Cart</button>
    </div>
</div>
