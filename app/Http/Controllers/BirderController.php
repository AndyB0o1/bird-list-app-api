<?php

namespace App\Http\Controllers;

use App\Models\Birder;
use Illuminate\Http\Request;

class BirderController extends Controller
{
    public Birder $birder;

    public function __construct(Birder $birder)
    {
        $this->birder = $birder;
    }

    public function allBirders()
    {
        $birders = $this->birder->get();

        return response()->json([
            'message' => 'Here is a list of all the birders',
            'success' => true,
            'data' => $birders,
        ]);
    }

    public function birdersWithBirds()
    {
        $allBirdersAndBirds = $this->birder->with('birds')->get();

        return response()->json([
            'message' => 'Here is a list of all the birders and their list of birds',
            'success' => true,
            'data' => $allBirdersAndBirds,
        ]);
    }

    public function getBirderBirdList(int $id)
    {
        $birderWithBirds = $this->birder->with('birds')->find($id);

        return response()->json([
            'message' => 'User bird list returned',
            'success' => true,
            'data' => $birderWithBirds,
        ]);
    }

    public function addBirder(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'string|max:255',
        ]);

        $birder = new Birder;

        $birder->username = $request->username;
        $birder->password = $request->password;

        if ($birder->save()) {
            return response()->json([
                'message' => 'Birder created',
                'success' => true,
            ], 201);
        }

        return response()->json([
            'message' => 'Birder not created',
            'success' => false,
        ], 500);
    }
}
