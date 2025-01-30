<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function allUsers()
    {
        $users = $this->user->get();

        return response()->json([
            'message' => 'Here is a list of all the users',
            'success' => true,
            'data' => $users,
        ]);
    }

    public function usersWithBirds()
    {
        $allUsersAndBirds = $this->user->with('birds')->get();

        return response()->json([
            'message' => 'Here is a list of all the users and their list of birds',
            'success' => true,
            'data' => $allUsersAndBirds,
        ]);
    }

    public function getUserBirdList(int $id)
    {
        $userWithBirds = $this->user->with('birds')->find($id);

        return response()->json([
            'message' => 'User bird list returned',
            'success' => true,
            'data' => $userWithBirds,
        ]);
    }

    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'string|max:255',
            'password' => 'string|max:255'
        ]);

        $user = new User;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($user->save()) {
            return response()->json([
                'message' => 'User created',
                'success' => true,
            ], 201);
        }

        return response()->json([
            'message' => 'User not created',
            'success' => false,
        ], 500);
    }

    public function deleteUser(int $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'message' => 'No such User',
                'success' => false,
            ], 400);
        }

        if ($user->delete()) {
            return response()->json([
                'message' => 'User successfully deleted',
                'success' => true,
            ]);
        }

        return response()->json([
            'message' => 'Something went wrong :-(',
            'success' => false,
        ], 500);
    }
}
