<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\EmailOtpMail;
use Modules\RegOnline\Services\RegOnlineService;

class AuthController extends Controller
{
    private const EMAIL_OTP_TTL_MINUTES = 10;

    public function __construct(protected RegOnlineService $regonline) {}

    private function getOtpCacheKey(string $email): string
    {
        return 'auth:email_otp:'.hash('sha256', strtolower(trim($email)));
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::query()
            ->where('email', $validated['identifier'])
            // ->orWhere('nik', $validated['identifier'])
            // ->orWhere('norm', $validated['identifier'])
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau kata sandi salah',
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
            'email' => 'required|string|email|max:255|unique:users,email',
            'email_otp' => 'required_with:email|string|size:6',
            'nik' => 'required|string|min:16|max:16|unique:users,nik',
            'norm' => 'nullable|string|min:6|max:8|unique:users,norm',
            'family_code' => 'nullable|string|max:32',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!empty($validated['email'])) {
            $otpCache = Cache::get($this->getOtpCacheKey($validated['email']));
            $otpCode = $otpCache['otp'] ?? null;

            if (!$otpCode || $validated['email_otp'] !== $otpCode) {
                return response()->json([
                    'message' => 'Kode OTP email tidak valid atau sudah kedaluwarsa.',
                    'errors' => [
                        'email_otp' => ['Kode OTP email tidak valid atau sudah kedaluwarsa.'],
                    ],
                ], 422);
            }
        }

        $simrsPatient = $this->regonline->findPatientByNik($validated['nik']);
        $isExistingPatient = $simrsPatient['status'] == 200;

        $resolvedNorm = $validated['norm']
            ?? $simrsPatient['norm']
            ?? null;

        if ($resolvedNorm && User::where('norm', $resolvedNorm)->exists()) {
            $resolvedNorm = null;
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? sprintf('%s@pasien.local', $validated['nik']),
            'nik' => $validated['nik'],
            'norm' => $resolvedNorm,
            'family_code' => $validated['family_code'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('patient-app-token')->plainTextToken;

        if (!empty($validated['email'])) {
            Cache::forget($this->getOtpCacheKey($validated['email']));
        }

        return response()->json([
            'message' => 'Pendaftaran berhasil',
            'patient_status' => $isExistingPatient ? 'pasien_lama' : 'pasien_baru',
            'simrs_patient_found' => $isExistingPatient,
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function sendRegisterEmailOtp(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
        ]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put($this->getOtpCacheKey($validated['email']), [
            'otp' => $otp,
            'name' => $validated['name'],
            'sent_at' => now()->toIso8601String(),
        ], now()->addMinutes(self::EMAIL_OTP_TTL_MINUTES));

        Mail::to($validated['email'])->send(new EmailOtpMail($otp, $validated['name'], self::EMAIL_OTP_TTL_MINUTES));

        return response()->json([
            'message' => 'OTP berhasil dikirim ke email.',
            'expires_in_minutes' => self::EMAIL_OTP_TTL_MINUTES,
        ]);
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
