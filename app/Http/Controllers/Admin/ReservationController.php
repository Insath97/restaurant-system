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
            $reservation = ModelsReservation::findOrFail($id);
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
}
