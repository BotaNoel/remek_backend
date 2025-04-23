<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
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
                'content' => 'required|string|max:1000',
                'rating_id' => 'required|exists:ratings,id',
            ]);

            $comment = Comment::create([
                'user_id' => $user->id,
                'apartment_id' => $validated['apartment_id'],
                'rating_id' => $validated['rating_id'],
                'comment' => $validated['content'],
            ]);

            return response()->json([
                'message' => 'Hozzászólás sikeresen elmentve.',
                'comment' => $comment,
            ]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function forApartment($apartmentId)
    {
        $comments = Comment::where('apartment_id', $apartmentId)
            ->with('user', 'rating')
            ->latest()
            ->get();

        return response()->json([
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'user' => $comment->user->name ?? 'Ismeretlen',
                    'content' => $comment->comment,
                    'rating' => $comment->rating->rating_value ?? null,
                    'created_at' => $comment->created_at->diffForHumans(),
                ];
            }),
        ]);
    }


}
