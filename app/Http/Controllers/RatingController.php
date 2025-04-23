<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class RatingController extends Controller
{
    const MAX_SCORE = 5;
    const MIN_SCORE = 1;

    public function index()
    {
        $ratings = collect(range(self::MIN_SCORE, self::MAX_SCORE))
            ->map(fn($score) => ['id' => $score, 'label' => "{$score} csillag"]);

        return response()->json([
            'success' => true,
            'ratings' => $ratings,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Nem vagy bejelentkezve.'], 401);
        }

        try {
            $validated = $request->validate([
                'apartment_id' => 'required|exists:apartments,id',
                'score' => 'required|integer|min:' . self::MIN_SCORE . '|max:' . self::MAX_SCORE,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        }

        $existingRating = Rating::where('user_id', $user->id)
            ->where('apartment_id', $validated['apartment_id'])
            ->first();

        if ($existingRating) {
            return response()->json(['success' => false, 'error' => 'Már értékelted ezt a szállást.'], 400);
        }

        $rating = Rating::create([
            'user_id' => $user->id,
            'apartment_id' => $validated['apartment_id'],
            'rating_value' => $validated['score'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Értékelés sikeresen mentve.',
            'rating' => $rating,
        ]);
    }

    public function forApartment($apartmentId)
    {
        $comments = Comment::where('apartment_id', $apartmentId)
            ->with(['user', 'rating'])
            ->latest()
            ->get();

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user' => $comment->user->name ?? 'Ismeretlen',
                    'content' => $comment->comment,
                    'rating' => optional($comment->rating)->rating_value, // Ez biztonságosabb
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            }),
        ]);
    }
}
