<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment_type;

class ApartmentTypeController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Apartment_type::all());
    }
}
