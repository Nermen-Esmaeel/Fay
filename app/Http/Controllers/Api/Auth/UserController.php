<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $userData = $request->except('password');
        $user->update($userData);
        return $user;
    }


    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null, 204);
    }
}

