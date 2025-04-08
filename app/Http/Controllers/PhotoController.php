<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function store(Request $request)
    {
        $photo = Photo::create($request->all());

        return response()->json($photo, 201);
    }
}
