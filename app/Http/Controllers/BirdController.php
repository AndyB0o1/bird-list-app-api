<?php

namespace App\Http\Controllers;

use App\Models\Bird;
use Illuminate\Http\Request;

class BirdController extends Controller
{
    public Bird $bird;

    /**
     * @param Bird $bird
     */
    public function __construct(Bird $bird)
    {
        $this->bird = $bird;
    }

    public function allBirds()
    {
        $birds = $this->bird->get();

        return response()->json([
            'message' => 'Here are your birds',
            'success' => true,
            'data' => $birds
        ]);
    }

    public function getRecent()
    {
        $recent = $this->bird->get()->sortDesc()->slice(0, 5);

        return response()->json([
           'message' => '5 most recent birds',
            'success' => true,
            'data' => $recent
        ]);
    }

    public function addBird(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'string|max:255',
            'location' => 'string|max:255'
        ]);

        $bird = new Bird;

        $bird->name = $request->name;
        $bird->image = $request->image;
        $bird->location = $request->location;

        if ($bird->save()) {
            return response()->json([
                'message' => 'Bird added',
                'success' => true
            ], 201);
        }

        return response()->json([
            'message' => 'Bird not created',
            'success' => false
        ], 500);
    }

}
