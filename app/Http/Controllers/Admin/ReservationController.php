<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation as ModelsReservation;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Reservation Index,admin'])->only(['index']);
        $this->middleware(['permission:Reservation Update,admin'])->only(['updateReservationStatus', 'getReservationDetails']);
    }

    public function index()
    {
        $reservations = ModelsReservation::with('user', 'table')
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->get();

        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateReservationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        try {
            $reservation = ModelsReservation::with('order', 'table')->findOrFail($id);

            if (!$reservation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation not found'
                ], 404);
            }

            if ($reservation->status === 'completed' || $reservation->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update status of a completed or cancelled reservation'
                ], 400);
            }

            if ($reservation->status === $request->status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservation is already in the requested status'
                ], 400);
            }

            if (!$this->isValidReservationStatusTransition($reservation->status, $request->status)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status transition from ' . ucfirst($reservation->status) . ' to ' . ucfirst($request->status)
                ], 400);
            }

            $reservation->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Reservation status updated successfully',
                'status' => $reservation->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reservation status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getReservationDetails($id)
    {
        try {
            $reservation = ModelsReservation::with(['user', 'table', 'order.items.menu'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'reservation' => $reservation,
                'has_order' => !is_null($reservation->order)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Reservation not found'
            ], 404);
        }
    }

    private function isValidReservationStatusTransition($currentStatus, $newStatus)
    {
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => []
        ];

        return in_array($newStatus, $validTransitions[$currentStatus] ?? []);
    }
}
