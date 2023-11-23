<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthUserResource;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
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

        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken,
            'user' => new AuthUserResource($user)
        ]);
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

        return response()->json([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
            'user' => new AuthUserResource(auth()->user())
        ]);
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
        return new AuthUserResource($request->user());
    }

    public function google(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'action' => ['nullable', 'string']
        ]);

        $client = new Client(['base_uri' => 'https://www.googleapis.com']);

        try {
            $res = $client->request('GET', '/oauth2/v3/userinfo', [
                'headers' => ['Authorization' => 'Bearer ' . $request->get('token')]
            ]);
        } catch (GuzzleException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $userData = json_decode($res->getBody()->getContents());

        $user = User::getByUsername($userData->email);

        if ($request->get('action') == 'signup' && !$user) {
            $user = User::create([
                'name' => $userData->given_name,
                'lastName' => $userData->family_name,
                'username' => $userData->email
            ]);
        }

        if (!$user)
            return response()->json([
                'success' => false,
                'code' => 'user_not_found',
                'email' => $userData->email
            ]);
        else {
            Auth::login($user);

            return response()->json([
                'success' => true,
                'code' => 'success',
                'token' => auth()->user()->createToken('API Token')->plainTextToken,
                'user' => new AuthUserResource($user)
            ]);
        }
    }
}
