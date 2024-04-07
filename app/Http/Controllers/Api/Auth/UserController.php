<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {
            $users = User::all();
            return Response::json($users);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(User $user)
    {
        try {
            return Response::json($user);
        } catch (ModelNotFoundException $e) {
            return Response::json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user->update($request->validated());
            return Response::json($user);
        } catch (ValidationException $e) {
            return Response::json(['error' => $e->errors()], 400);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return Response::json(null, 204);
        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }
    }
}
