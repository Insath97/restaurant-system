<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
