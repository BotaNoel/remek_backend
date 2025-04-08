<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;

class LocationController extends Controller
{
    public function store(Request $request)
    {
        $location = Location::create($request->all());

        return response()->json($location, 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
    }
}
