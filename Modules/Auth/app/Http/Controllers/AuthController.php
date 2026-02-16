<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::query()
            ->where('email', $validated['identifier'])
            ->orWhere('nik', $validated['identifier'])
            ->orWhere('norm', $validated['identifier'])
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Identitas/NIK/No RM atau kata sandi salah',
            ], 401);
        }

        $token = $user->createToken('patient-app-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'nik' => 'required|string|min:8|max:32|unique:users,nik',
            'norm' => 'nullable|string|min:4|max:32|unique:users,norm',
            'family_code' => 'nullable|string|max:32',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? sprintf('%s@pasien.local', $validated['nik']),
            'nik' => $validated['nik'],
            'norm' => $validated['norm'] ?? null,
            'family_code' => $validated['family_code'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('patient-app-token')->plainTextToken;

        return response()->json([
            'message' => 'Pendaftaran berhasil',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Logout berhasil',
        ]);
    }
}
