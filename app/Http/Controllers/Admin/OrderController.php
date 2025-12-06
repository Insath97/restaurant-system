<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Order Index,admin'])->only(['index']);
        $this->middleware(['permission:Order View,admin'])->only(['show']);
        $this->middleware(['permission:Order Update,admin'])->only(['updateStatus']);
    }

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
            $order = Order::with('reservation')->findOrFail($id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            if ($order->status === 'completed' || $order->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update status of a completed or cancelled order'
                ], 400);
            }

            if ($order->status === $request->status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is already in the requested status'
                ], 400);
            }

            if (!$this->isValidStatusTransition($order->status, $request->status)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition from ' . $order->statusText() . ' to ' . ucfirst($request->status)
                ], 400);
            }

            $paymentStatus = $order->payment_status;
            if ($request->status === 'completed') {
                $paymentStatus = true;

                if ($order->reservation) {
                    $order->reservation->update(['status' => 'completed']);

                    if ($order->reservation->table) {
                        $order->reservation->table->update([
                            'is_available' => true,
                            'status' => 'available'
                        ]);
                    }
                }
            } elseif ($request->status === 'cancelled') {
                if ($order->reservation) {
                    $order->reservation->update(['status' => 'cancelled']);

                    if ($order->reservation->table) {
                        $order->reservation->table->update([
                            'is_available' => true,
                            'status' => 'available'
                        ]);
                    }
                }
            }

            $order->update([
                'status' => $request->status,
                'payment_status' => $paymentStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'status' => $request->status,
                'payment_status' => $paymentStatus,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status'
            ], 500);
        }
    }

    private function isValidStatusTransition($currentStatus, $newStatus)
    {
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => []
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }
}
