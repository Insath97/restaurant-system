<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'reservation.table', 'items.menu'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.order.index', compact('orders'));
    }

    public function show($id)
    {
        try {
            $order = Order::with(['user', 'reservation.table', 'items.menu'])
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled'
        ]);

        try {
            $order = Order::findOrFail($id);
            $order->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'status' => $request->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status'
            ], 500);
        }
    }
}
