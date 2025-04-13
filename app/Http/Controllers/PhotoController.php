<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');

            $photo = Photo::create([
                'apartment_id' => $request->apartment_id,
                'url' => asset('storage/' . $path),
            ]);

            return response()->json($photo, 201);
        }

        return response()->json(['error' => 'Fájl nem érkezett'], 400);
    }
}
