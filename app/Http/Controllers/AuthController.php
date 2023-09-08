<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'username' => $attr['username']
        ]);

        return response()->json(array_merge([
            'token' => $user->createToken('API Token')->plainTextToken
        ], $user->toArray()));
    }

    public function login(Request $request)
    {
        $attr = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($attr)) {
            return response()->json(['message' => 'Логин или пароль не верный'], 422);
        }

        return response()->json(array_merge([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ], auth()->user()->toArray()));
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }

    public function user(Request $request)
    {
        return response()->json($request->user()->toArray());
    }
}
