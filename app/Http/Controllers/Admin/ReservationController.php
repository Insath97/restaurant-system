<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation as ModelsReservation;

class ReservationController extends Controller
{
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
                'status' => $request->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update reservation status'
            ], 500);
        }
    }
}
