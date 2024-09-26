<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): Response | JsonResponse
    {
        $request->validate([
            'name' => 'bail|string|required',
            'password' => 'bail|string|required',
        ]);

        $user = User::firstWhere('name', $request->name);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->noContent(401);
        }

        $token = $user->createToken('access_token');
        $user->access_token = explode('|', $token->plainTextToken)[1];

        return response()->json($user);
    }

    public function signup(Request $request): Response | JsonResponse
    {
        $filteredRequestBody = $request->validate([
            'email' => 'bail|required|email',
            'name' => 'bail|string|required|max:255|min:8',
            'password' => 'bail|string|required|max:255|min:8',
        ]);

        if (User::exists()) {
            return response()->noContent(406);
        }

        $filteredRequestBody['type'] = UserType::Admin;
        $newUser = User::create($filteredRequestBody);

        return response()->json($newUser, 201);
    }

    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
