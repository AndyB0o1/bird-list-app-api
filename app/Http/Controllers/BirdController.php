<?php

namespace App\Http\Controllers;

use App\Models\Bird;
use Illuminate\Http\Request;

class BirdController extends Controller
{
    public Bird $bird;

    public function __construct(Bird $bird)
    {
        $this->bird = $bird;
    }

    public function allBirds(Request $request)
    {
        $request->validate([
            'search' => 'string'
        ]);

        $birdQuery = $this->bird->query();

        if ($request->search) {
            $birdQuery = $birdQuery->whereAny(['name', 'location'], 'LIKE', "%{$request->search}%");
        }

        else {
            $birdQuery = $this->bird->with('user');
        }

        $birds = $birdQuery->with('user')->get();

        return response()->json([
            'message' => 'Here are your birds',
            'success' => true,
            'data' => $birds,
        ]);
    }

    public function mapBirds()
    {
        $mapBirds = $this->bird->where([['lat', '!=', null], ['lon', '!=', null]])->with('user')->get();

        return response()->json([
            'message' => 'Here are your birds for the map',
            'success' => true,
            'data' => $mapBirds,
        ]);
    }

    public function getRecent()
    {
        $recent = $this->bird->with('user')->get()->shuffle()->slice(0, 5);

        return response()->json([
            'message' => '5 recent bird sightings',
            'success' => true,
            'data' => $recent,
        ]);
    }

    public function getSingleBird(int $id)
    {
        $bird = $this->bird->find($id);

        if (! $bird) {
            return response()->json([
                'message' => 'Bird sighting does not exist',
                'success' => false,
            ], 400);
        }
            return response()->json([
                'message' => 'Here is your bird',
                'success' => true,
                'data' => $bird,
            ]);
        }

    public function addBird(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'string|max:255',
            'location' => 'string|max:255',
            'lat' => 'numeric',
            'lon' => 'numeric',
            'user_id' => 'integer|exists:users,id',
        ]);

        $bird = new Bird;

        $bird->name = $request->name;
        $bird->image = $request->image;
        $bird->location = $request->location;
        $bird->lat = $request->lat;
        $bird->lon = $request->lon;
        $bird->user_id = $request->user_id;

        if ($bird->save()) {
            return response()->json([
                'message' => 'Bird added',
                'success' => true,
            ], 201);
        }

        return response()->json([
            'message' => 'Bird not created',
            'success' => false,
        ], 500);
    }

    public function editBird(int $id, Request $request)
    {
        $bird = $this->bird->find($id);

        if (! $bird) {
            return response()->json([
                'message' => 'Bird not found',
                'success' => false,
            ], 400);
        }

        $bird->name = $request->name ?? $bird->name;
        $bird->image = $request->image ?? $bird->image;
        $bird->location = $request->location ?? $bird->location;
        $bird->lat = $request->lat ?? $bird->lat;
        $bird->lon = $request->lon ?? $bird->lon;
        $bird->user_id = $request->user_id ?? $bird->user_id;

        if ($bird->save()) {
            return response()->json([
                'message' => 'Bird sighting updated',
                'success' => true,
                'data' => $bird,
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'success' => false,
        ], 500);
    }

    public function deleteBird(int $id)
    {
        $bird = Bird::find($id);

        if (! $bird) {
            return response()->json([
                'message' => 'No such Bird',
                'success' => false,
            ], 400);
        }

        if ($bird->delete()) {
            return response()->json([
                'message' => 'Bird successfully deleted',
                'success' => true,
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong :-(',
            'success' => false,
        ], 500);
    }
}
