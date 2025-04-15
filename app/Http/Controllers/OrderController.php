<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Apartment;
use Carbon;

class OrderController extends Controller
{
    public function index($apartmentId)
    {
        return Order::where('apartment_id', $apartmentId)
            ->select('arrival_date', 'departure_date', 'status')
            ->where('status', '!=', 'cancelled') // csak aktív rendelések
            ->get();
    }

    public function store(Request $request, $apartmentId)
    {
        $request->validate([
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after:arrival_date',
            'headcount' => 'required|integer|min:1',
        ]);

        // Ütközés ellenőrzése
        $overlap = Order::where('apartment_id', $apartmentId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('arrival_date', [$request->arrival_date, $request->departure_date])
                    ->orWhereBetween('departure_date', [$request->arrival_date, $request->departure_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('arrival_date', '<=', $request->arrival_date)
                          ->where('departure_date', '>=', $request->departure_date);
                    });
            })
            ->exists();

        if ($overlap) {
            return response()->json(['message' => 'Ez az időszak már foglalt!'], 409);
        }

        // Apartman ár lekérése
        $apartment = Apartment::findOrFail($apartmentId);
        $pricePerNight = $apartment->price_per_night;

        // Napok kiszámolása
        $days = Carbon\Carbon::parse($request->arrival_date)
            ->diffInDays(Carbon\Carbon::parse($request->departure_date));

        // Összár
        $totalPrice = $days * $pricePerNight;

        $order = Order::create([
            'apartment_id' => $apartmentId,
            'user_id' => auth()->id() ?? 1, // ha nincs auth, default user
            'arrival_date' => $request->arrival_date,
            'departure_date' => $request->departure_date,
            'headcount' => $request->headcount,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        return response()->json($order, 201);
    }
}
