<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\Emails\EmailOtpMail;

class EmailOtpService
{
    public function cacheKey(string $scope, string $email): string
    {
        return 'auth:email_otp:'.$scope.':'.hash('sha256', strtolower(trim($email)));
    }

    public function send(string $scope, string $email, string $name, int $ttlMinutes = 10): void
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Cache::put($this->cacheKey($scope, $email), [
            'otp' => $otp,
            'name' => $name,
            'email' => $email,
            'sent_at' => now()->toIso8601String(),
        ], now()->addMinutes($ttlMinutes));

        Mail::to($email)->send(new EmailOtpMail($otp, $name, $ttlMinutes));
    }

    public function verify(string $scope, string $email, string $otp): bool
    {
        $otpCache = Cache::get($this->cacheKey($scope, $email));
        $otpCode = $otpCache['otp'] ?? null;

        return $otpCode && hash_equals((string) $otpCode, (string) $otp);
    }

    public function forget(string $scope, string $email): void
    {
        Cache::forget($this->cacheKey($scope, $email));
    }
}