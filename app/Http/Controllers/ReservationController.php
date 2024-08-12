<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    //
    public function index()
    {
        $reservations = Reservation::all();
        return response()->json($reservations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'ville' => 'required|max:255',
            'adress' => 'required|max:255',
            'phone' => 'required|max:20',
            'product_id' => 'required|exists:products,id'
        ]);

        $reservation = Reservation::create($validated);

        return response()->json([
            'message' => 'Reservation created successfully.',
            'reservation' => $reservation
        ], 201);
    }

    public function show(Reservation $reservation)
    {
        return response()->json($reservation);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'ville' => 'required|max:255',
            'adress' => 'required|max:255',
            'phone' => 'required|max:20',
            'product_id' => 'required|exists:products,id'
        ]);

        $reservation->update($validated);

        return response()->json([
            'message' => 'Reservation updated successfully.',
            'reservation' => $reservation
        ]);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return response()->json([
            'message' => 'Reservation deleted successfully.'
        ]);
    }
}



