<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'credential' => 'required|string',
        ]);

        $idToken = $request->credential;

        // verifikasi token ke google
        $response = Http::get(
            'https://oauth2.googleapis.com/tokeninfo',
            ['id_token' => $idToken]
        );

        if (!$response->ok()) {
            return response()->json([
                'message' => 'Token Google tidak valid'
            ], 401);
        }

        $payload = $response->json();

        // VALIDASI CLIENT ID
        if ($payload['aud'] !== config('services.google.client_id')) {
            return response()->json([
                'message' => 'Client ID mismatch'
            ], 401);
        }
        // ambil data user
        $email = $payload['email'];
        $name = $payload['name'] ?? $payload['email'];

// var_dump($name);
        // cari / buat user
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'nik' => null,
                'password' => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
                'profile_completed' => false,
            ]
        );

        // login session laravel (penting!)
        Auth::login($user, true);

        // kalau pakai sanctum token:
        $token = $user->createToken('web')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'isProfileGoogle' => !$user->profile_completed,
        ]);
    }
}
