<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Nem vagy bejelentkezve.'], 401);
            }

            $validated = $request->validate([
                'apartment_id' => 'required|exists:apartments,id',
                'score' => 'required|integer|min:1|max:5',
            ]);

            // Egy felhasználó csak egyszer értékelhet egy apartmant
            $existingRating = Rating::where('user_id', $user->id)
                ->where('apartment_id', $validated['apartment_id'])
                ->first();

            if ($existingRating) {
                return response()->json(['error' => 'Már értékelted ezt a szállást.'], 400);
            }

            $rating = Rating::create([
                'user_id' => $user->id,
                'apartment_id' => $validated['apartment_id'],
                'score' => $validated['score'],
            ]);

            return response()->json([
                'message' => 'Értékelés sikeresen mentve.',
                'rating' => $rating,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function forApartment($apartmentId)
    {
        $ratings = Rating::where('apartment_id', $apartmentId)->with('user')->get();

        $average = $ratings->avg('score');

        return response()->json([
            'ratings' => $ratings->map(function ($rating) {
                return [
                    'id' => $rating->id,
                    'user' => $rating->user->name ?? 'Ismeretlen',
                    'score' => $rating->score,
                    'created_at' => $rating->created_at->toDateString(),
                ];
            }),
            'average_score' => round($average, 2),
        ]);
    }
}
