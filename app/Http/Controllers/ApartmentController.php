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
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Nem vagy bejelentkezve.'], 401);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type_id' => 'required|integer|exists:apartment_types,id',
                'max_capacity' => 'required|integer|min:1',
                'description' => 'nullable|string',
                'price_per_night' => 'required|numeric|min:0',
                'user_id' => 'required|exists:users,id',  // Felhasználó ID validálás
            ]);

            $apartment = Apartment::create([
                'user_id' => $user->id,
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
    public function index(){
        return response()->json(Apartment::with(["type","filters","photos","location"])->get(), 200, ['Access-Control-Allow-Origin' => '*'], JSON_UNESCAPED_UNICODE);
    }

    public function search(Request $request)
    {
        $query = Apartment::query()
            ->with(['location', 'filters', 'photos']);

        if ($request->has('city') && $request->city) {
            $query->whereHas('location', function ($q) use ($request) {
                $q->where('city', 'LIKE', '%' . $request->city . '%');
            });
        }

        if ($request->has('type_id') && $request->type_id) {
            $query->where('type_id', $request->type_id);
        }

        if ($request->has('filters')) {
            foreach ($request->filters as $key => $value) {
                if ($value) {
                    $query->whereHas('filters', function ($q) use ($key) {
                        $q->where($key, true);
                    });
                }
            }
        }

        $results = $query->get()->map(function ($apartment) {
            return [
                'id' => $apartment->id,
                'name' => $apartment->name,
                'description' => $apartment->description,
                'price_per_night' => $apartment->price_per_night,
                'max_capacity' => $apartment->max_capacity,
                'cover_photo' => $apartment->photos->first()->url ?? null
            ];
        });

        return response()->json($results);
    }

    public function show($id)
    {
        $apartment = Apartment::with(['type', 'filters', 'photos', 'location'])->findOrFail($id);

        return response()->json([
            'id' => $apartment->id,
            'name' => $apartment->name,
            'description' => $apartment->description,
            'max_capacity' => $apartment->max_capacity,
            'price_per_night' => $apartment->price_per_night,
            'created_at' => $apartment->created_at->toDateString(),
            'type' => $apartment->type->name ?? 'Ismeretlen',
            'uploader' => $apartment->user->name ?? 'Ismeretlen',
            'filters' => $apartment->filters,
            'photo' => $apartment->photos->first()->url ?? null, // kompatibilitás a régivel
            'photos' => $apartment->photos->map(function ($photo) {
                return [
                    'url' => $photo->url
                ];
            }),
            'location' => [
                'postal_code' => $apartment->location->postal_code ?? '',
                'city' => $apartment->location->city ?? '',
                'street' => $apartment->location->street ?? '',
                'address_number' => $apartment->location->address_number ?? '',
            ],
        ]);
    }

}
