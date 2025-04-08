<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApartmentController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type_id' => 'required|integer|exists:apartment_types,id',
                'max_capacity' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'price_per_night' => 'required|numeric|min:0',
            ]);

            $user = Auth::user();

            $apartment = Apartment::create([
                'user_id' => 1,
                'name' => $validated['name'],
                'type_id' => $validated['type_id'],
                'max_capacity' => $validated['max_capacity'],
                'description' => $validated['description'] ?? null,
                'price_per_night' => $validated['price_per_night'],
            ]);

            return response()->json([
                'message' => 'Szállás sikeresen létrehozva!',
                'apartment' => $apartment
            ], 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hiba történt a szállás létrehozása során.'], 500);
        }
    }
}
