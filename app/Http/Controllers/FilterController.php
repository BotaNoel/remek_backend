<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Filter;
use Illuminate\Validation\ValidationException;

class FilterController extends Controller
{
    public function store(Request $request){
        try {
            $validated = $request->validate([
                'apartment_id' => 'required|exists:apartments,id',
                'wellness' => 'boolean',
                'breakfast' => 'boolean',
                'parking' => 'boolean',
                'wifi' => 'boolean',
                'all_inclusive' => 'boolean',
                'near_the_beach' => 'boolean',
                'near_the_center' => 'boolean',
                'pet_friendly' => 'boolean',
                'smoking_allowed' => 'boolean',
            ]);

            $filter = Filter::create($validated);

            return response()->json($filter, 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
        } catch (ValidationExceptio  $e) {
            return response()->json(['errors' => $e->errors()], 422, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
        }
    }
}
