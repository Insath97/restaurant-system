<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\Review;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $categories = Category::with(['menus' => function ($query) {
            $query->limit(3);
        }])->get();

        return view('client.home', compact('categories'));
    }

    public function about()
    {
        return view('client.about');
    }

    public function menu()
    {
        $categories = Category::with('menus')->get();
        return view('client.menu', compact('categories'));
    }

    public function gallery()
    {
        return view('client.gallary');
    }

    public function contact()
    {
        return view('client.contact');
    }

    public function myaccount()
    {
        $user = Auth::user();

        // Get user's reservations with pagination (5 per page)
        $reservations = Reservation::with('table')
            ->where('user_id', $user->id)
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(2, ['*'], 'reservations_page');

        // Get user's orders with pagination (5 per page)
        $orders = Order::with(['items.menu', 'reservation'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(2, ['*'], 'orders_page');

        // Get user's reviews with pagination (5 per page)
        $reviews = Review::with(['reviewable'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(3, ['*'], 'reviews_page');

        return view('client.my-account', compact('user', 'reservations', 'orders', 'reviews'));
    }

    public function orderType()
    {
        return view('client.order-type');
    }

    public function paginateMenu(Request $request)
    {
        $categoryId = $request->input('category_id');
        $page = $request->input('page', 1);
        $perPage = 3;

        $category = Category::with('menus')->findOrFail($categoryId);
        $menus = $category->menus;
        $totalPages = ceil($menus->count() / $perPage);

        if ($page < 1) $page = 1;
        if ($page > $totalPages) $page = $totalPages;

        $currentItems = $menus->forPage($page, $perPage);

        return response()->json([
            'menus' => $currentItems->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'image' => asset($menu->image),
                    'rating' => $menu->rating ?? 4.5,
                    'description' => $menu->description,
                    'price' => number_format($menu->price, 2)
                ];
            }),
            'currentPage' => (int)$page,
            'totalPages' => $totalPages
        ]);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
        ]);

        try {
            $user = Auth::user();
            $cart = $user->getCart();

            $menu = Menu::findOrFail($request->menu_id);
            $existingItem = $cart->items()->where('menu_id', $request->menu_id)->first();

            if ($existingItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'already_in_cart',
                    'cart_count' => $cart->items->sum('quantity')
                ], 400);
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'menu_id' => $request->menu_id,
                    'quantity' => 1,
                    'price' => $menu->price
                ]);
            }

            $cart->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully',
                'cart_count' => $cart->items->sum('quantity')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart'
            ], 500);
        }
    }

    public function getCart()
    {
        try {
            $user = Auth::user();
            $cart = $user->getCart();

            // Load cart items with menu information
            $cart->load(['items.menu']);

            $items = $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'menu_id' => $item->menu_id,
                    'name' => $item->menu->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'image' => $item->menu->image,
                    'total' => $item->price * $item->quantity
                ];
            });

            return response()->json([
                'success' => true,
                'items' => $items,
                'totals' => [
                    'subtotal' => $cart->subtotal,
                    'service_charge' => $cart->service_charge,
                    'total' => $cart->total
                ],
                'cart_count' => $cart->items->sum('quantity')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load cart'
            ], 500);
        }
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id'
        ]);

        try {
            $user = Auth::user();
            $cart = $user->getCart();

            // Ensure the cart item belongs to the user's cart
            $cartItem = $cart->items()->where('id', $request->item_id)->firstOrFail();
            $cartItem->delete();

            $cart->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully',
                'cart_count' => $cart->items->sum('quantity')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart'
            ], 500);
        }
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'change' => 'required|integer|in:-1,1' // Only allow -1 (decrease) or 1 (increase)
        ]);

        try {
            $user = Auth::user();
            $cart = $user->getCart();

            // Ensure the cart item belongs to the user's cart
            $cartItem = $cart->items()->where('id', $request->item_id)->firstOrFail();

            $newQuantity = $cartItem->quantity + $request->change;

            // Validate minimum quantity
            if ($newQuantity < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantity cannot be less than 1'
                ], 400);
            }

            // Validate maximum quantity (optional - you can set a limit)
            if ($newQuantity > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maximum quantity per item is 10'
                ], 400);
            }

            $cartItem->update([
                'quantity' => $newQuantity
            ]);

            $cart->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'cart_count' => $cart->items->sum('quantity')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart'
            ], 500);
        }
    }

    public function getAvailableTables(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1|max:20'
        ]);

        try {
            $availableTables = Table::where('is_available', true)
                ->where('capacity', '>=', $request->guest_count)
                ->where(function ($query) use ($request) {
                    $query->where('status', 'available')
                        ->orWhere(function ($q) use ($request) {
                            // Include tables that are reserved but not for this specific date/time
                            $q->where('status', 'reserved')
                                ->whereDoesntHave('reservations', function ($subQuery) use ($request) {
                                    $subQuery->where('reservation_date', $request->reservation_date)
                                        ->where('reservation_time', $request->reservation_time)
                                        ->whereIn('status', ['pending', 'confirmed']);
                                });
                        });
                })
                ->get();

            return response()->json([
                'success' => true,
                'tables' => $availableTables->map(function ($table) {
                    return [
                        'id' => $table->id,
                        'name' => $table->name,
                        'code' => $table->code,
                        'capacity' => $table->capacity,
                        'description' => $table->description,
                        'status' => $table->status
                    ];
                })
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch available tables'
            ], 500);
        }
    }

    public function makeReservationTable111(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500'
        ]);

        try {
            // Check if table is available for this specific date and time
            $existingReservation = Reservation::where('table_id', $validated['table_id'])
                ->where('reservation_date', $validated['reservation_date'])
                ->where('reservation_time', $validated['reservation_time'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if ($existingReservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'This table is already reserved for the selected date and time. Please choose a different time or table.'
                ], 400);
            }

            // Check if table has capacity for guests
            $table = Table::find($validated['table_id']);
            if ($table->capacity < $validated['guest_count']) {
                return response()->json([
                    'success' => false,
                    'message' => 'This table does not have enough capacity for ' . $validated['guest_count'] . ' guests. Maximum capacity: ' . $table->capacity
                ], 400);
            }

            // Create reservation - DO NOT update table status to reserved
            $reservation = Reservation::create([
                'user_id' => Auth::id(),
                'table_id' => $validated['table_id'],
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'guest_count' => $validated['guest_count'],
                'special_requests' => $validated['special_requests'],
                'status' => 'pending'
            ]);

            // Note: We don't change the table status to 'reserved' anymore
            // The table remains 'available' but will be excluded for this specific date/time

            return response()->json([
                'success' => true,
                'message' => 'Reservation created successfully!',
                'reservation_id' => $reservation->id
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation: ' . $th->getMessage()
            ], 500);
        }
    }

    public function makeReservationTable(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1|max:20',
            'special_requests' => 'nullable|string|max:500'
        ]);

        try {
            // Check if table is available for this specific date and time
            $existingReservation = Reservation::where('table_id', $validated['table_id'])
                ->where('reservation_date', $validated['reservation_date'])
                ->where('reservation_time', $validated['reservation_time'])
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if ($existingReservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'This table is already reserved for the selected date and time. Please choose a different time or table.'
                ], 400);
            }

            // Check if table has capacity for guests
            $table = Table::find($validated['table_id']);
            if ($table->capacity < $validated['guest_count']) {
                return response()->json([
                    'success' => false,
                    'message' => 'This table does not have enough capacity for ' . $validated['guest_count'] . ' guests. Maximum capacity: ' . $table->capacity
                ], 400);
            }

            // Create reservation with unique code
            $reservation = Reservation::create([
                'code' => Reservation::generateReservationCode(), // Add this line
                'user_id' => Auth::id(),
                'table_id' => $validated['table_id'],
                'reservation_date' => $validated['reservation_date'],
                'reservation_time' => $validated['reservation_time'],
                'guest_count' => $validated['guest_count'],
                'special_requests' => $validated['special_requests'],
                'status' => 'pending'
            ]);

            // Store reservation data in session for checkout
            session([
                'reservation_id' => $reservation->id,
                'reservation_table_id' => $validated['table_id'],
                'order_type' => 'dine_in'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation created successfully!',
                'reservation_id' => $reservation->id,
                'reservation_code' => $reservation->code, // Return code in response
                'redirect_url' => route('checkout')
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create reservation: ' . $th->getMessage()
            ], 500);
        }
    }

    public function cancelReservation($id)
    {
        try {
            $reservation = Reservation::where('user_id', Auth::id())->findOrFail($id);

            // Only allow cancelling future reservations
            $reservationDateTime = $reservation->reservation_date . ' ' . $reservation->reservation_time;
            if (strtotime($reservationDateTime) < time()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel past reservations'
                ], 400);
            }

            $reservation->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Reservation cancelled successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel reservation'
            ], 500);
        }
    }

    public function createReservation()
    {
        $user = Auth::user();
        $cart = $user->getCart();
        $cart->load(['items.menu']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('order-type.index')->with('error', 'Your cart is empty. Please add items before making a reservation.');
        }

        return view('client.reservation-create', compact('cart'));
    }

    public function selectOrderType(Request $request)
    {
        $request->validate([
            'order_type' => 'required|in:takeaway,dine_in'
        ]);

        // Store in session with proper persistence
        session(['order_type' => $request->order_type]);

        // Force session save
        $request->session()->save();

        if ($request->order_type === 'dine_in') {
            return response()->json([
                'success' => true,
                'redirect_url' => route('reservation.create')
            ]);
        }

        // For takeaway, redirect to checkout
        return response()->json([
            'success' => true,
            'redirect_url' => route('checkout')
        ]);
    }

    public function checkout()
    {
        $user = Auth::user();
        $cart = $user->getCart();
        $cart->load(['items.menu']);

        if ($cart->items->isEmpty()) {
            return redirect()->route('menu')->with('error', 'Your cart is empty.');
        }

        $orderType = session('order_type', 'takeaway');

        // For dine_in, check if reservation exists
        if ($orderType === 'dine_in') {
            $reservationId = session('reservation_id');
            if (!$reservationId) {
                return redirect()->route('reservation.create')->with('error', 'Please make a reservation first.');
            }

            $reservation = Reservation::with('table')->find($reservationId);
            return view('client.checkout', compact('cart', 'orderType', 'reservation'));
        }

        return view('client.checkout', compact('cart', 'orderType'));
    }

    public function processCheckout(Request $request)
    {
        $user = Auth::user();
        $cart = $user->getCart();
        $cart->load(['items.menu']);

        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.'
            ], 400);
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required_if:order_type,takeaway|string|max:500',
            'special_instructions' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery,credit_card,paypal'
        ]);

        try {
            DB::beginTransaction();

            // Get reservation ID from session for dine-in orders
            $reservationId = null;
            $tableId = null;

            if (session('order_type') === 'dine_in') {
                $reservationId = session('reservation_id');
                $tableId = session('reservation_table_id');

                // Verify the reservation belongs to the user and is still valid
                if ($reservationId) {
                    $reservation = Reservation::where('id', $reservationId)
                        ->where('user_id', $user->id)
                        ->where('status', 'pending')
                        ->first();

                    if (!$reservation) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid or expired reservation. Please make a new reservation.'
                        ], 400);
                    }

                    // Update reservation status to confirmed
                    $reservation->update(['status' => 'confirmed']);
                    $tableId = $reservation->table_id;
                }
            }

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'reservation_id' => $reservationId,
                'order_type' => session('order_type', 'takeaway'),
                'status' => 'pending',
                'subtotal' => $cart->subtotal,
                'service_charge' => $cart->service_charge,
                'delivery_fee' => session('order_type') === 'takeaway' ? 300 : 0,
                'total' => $cart->total + (session('order_type') === 'takeaway' ? 300 : 0),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'delivery_address' => $request->delivery_address,
                'special_instructions' => $request->special_instructions,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method !== 'cash_on_delivery',
                'order_date' => now(),
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $cartItem->menu_id,
                    'menu_name' => $cartItem->menu->name,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->price * $cartItem->quantity,
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->calculateTotals();

            // Clear session data
            session()->forget(['order_type', 'reservation_id', 'reservation_table_id']);

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'redirect_url' => route('order.success', $order)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function orderSuccess(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.menu', 'reservation']);

        return view('client.order-success', compact('order'));
    }

    public function completeDineInOrder(Request $request)
    {
        $user = Auth::user();
        $cart = $user->getCart();
        $cart->load(['items.menu']);

        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.'
            ], 400);
        }

        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date|after:today',
            'reservation_time' => 'required|date_format:H:i',
            'guest_count' => 'required|integer|min:1|max:20',
            'customer_phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            // Check if table is available
            $existingReservation = Reservation::where('table_id', $request->table_id)
                ->where('reservation_date', $request->reservation_date)
                ->where('reservation_time', $request->reservation_time)
                ->whereIn('status', ['pending', 'confirmed'])
                ->exists();

            if ($existingReservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'This table is already reserved. Please choose a different time or table.'
                ], 400);
            }

            // Check table capacity
            $table = Table::find($request->table_id);
            if ($table->capacity < $request->guest_count) {
                return response()->json([
                    'success' => false,
                    'message' => 'This table does not have enough capacity for ' . $request->guest_count . ' guests.'
                ], 400);
            }

            // Create reservation with unique code
            $reservation = Reservation::create([
                'code' => Reservation::generateReservationCode(), // Add this line
                'user_id' => $user->id,
                'table_id' => $request->table_id,
                'reservation_date' => $request->reservation_date,
                'reservation_time' => $request->reservation_time,
                'guest_count' => $request->guest_count,
                'special_requests' => $request->special_requests,
                'status' => 'pending'
            ]);

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $user->id,
                'reservation_id' => $reservation->id,
                'order_type' => 'dine_in',
                'status' => 'pending',
                'subtotal' => $cart->subtotal,
                'service_charge' => $cart->service_charge,
                'delivery_fee' => 0,
                'total' => $cart->total,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $request->customer_phone,
                'delivery_address' => null,
                'special_instructions' => $request->special_requests,
                'payment_method' => 'cash_on_delivery',
                'payment_status' => false,
                'order_date' => now(),
            ]);

            // Create order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $cartItem->menu_id,
                    'menu_name' => $cartItem->menu->name,
                    'price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->price * $cartItem->quantity,
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->calculateTotals();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Your dine-in order has been confirmed successfully!',
                'order_id' => $order->id,
                'reservation_code' => $reservation->code, // Return reservation code
                'redirect_url' => route('order.success', $order)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to process order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeReview(Request $request)
    {
        try {
            $request->validate([
                'reviewable_type' => 'required|in:App\Models\Order,App\Models\Reservation',
                'reviewable_id' => 'required|integer',
                'review_title' => 'required|string|max:255',
                'comment' => 'required|string|min:10',
                'rating' => 'required|integer|between:1,5',
                'food_quality' => 'nullable|integer|between:1,5',
                'service_quality' => 'nullable|integer|between:1,5',
                'ambiance' => 'nullable|integer|between:1,5',
                'would_recommend' => 'nullable|boolean',
            ]);

            $user = Auth::user();

            // Check if already reviewed
            $existingReview = Review::where('user_id', $user->id)
                ->where('reviewable_type', $request->reviewable_type)
                ->where('reviewable_id', $request->reviewable_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this item.'
                ], 422);
            }

            // Verify ownership and completion status
            $canReview = false;

            if ($request->reviewable_type === 'App\Models\Order') {
                $order = Order::where('id', $request->reviewable_id)
                    ->where('user_id', $user->id)
                    ->first();

                if ($order && $order->status === 'completed') {
                    $canReview = true;
                }
            } elseif ($request->reviewable_type === 'App\Models\Reservation') {
                $reservation = Reservation::where('id', $request->reviewable_id)
                    ->where('user_id', $user->id)
                    ->first();

                if ($reservation && $reservation->status === 'completed') {
                    $canReview = true;
                }
            }

            if (!$canReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot review this item. Please complete it first.'
                ], 403);
            }

            // Create review
            $review = Review::create([
                'user_id' => $user->id,
                'reviewable_type' => $request->reviewable_type,
                'reviewable_id' => $request->reviewable_id,
                'review_title' => $request->review_title,
                'comment' => $request->comment,
                'rating' => $request->rating,
                'food_quality' => $request->food_quality,
                'service_quality' => $request->service_quality,
                'ambiance' => $request->ambiance,
                'would_recommend' => $request->boolean('would_recommend'),
                'is_approved' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully!',
                'review' => $review
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit review. Please try again.'
            ], 500);
        }
    }
}
