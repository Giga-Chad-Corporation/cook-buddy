<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        return view('users');
    }

    public function get($id = null)
    {
        $id = request('id');

        var_dump($id);
        if ($id)
        {
            $user = User::find($id);

            if ($user)
            {
                return response()->json($user);
            } else
            {
                return response()->json(['message' => 'User not found'], 404);
            }

        } else
        {
            $users = User::all();

            if ($users)
            {
                return response()->json($users);
            }
        }
    }
}
