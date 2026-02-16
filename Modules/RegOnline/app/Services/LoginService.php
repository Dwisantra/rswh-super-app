<?php

namespace Modules\RegOnline\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class LoginService
{
    private string $url;
    private string $id;
    private string $pass;

    public function __construct()
    {
        $this->url = (string) config('regonline.url');
        $this->id = (string) config('regonline.x_id');
        $this->pass = (string) config('regonline.x_pass');
    }

    public function token(): string
    {
        return Cache::remember('simrs_token', 3300, function () {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'x-username' => $this->id,
                'x-password' => $this->pass,
            ])
                ->timeout((int) config('regonline.timeout', 15))
                ->get($this->url.'/registrasionline/rsonline/getToken');

            $token = data_get($response->json(), 'response.token');

            return is_string($token) && $token !== '' ? $token : 'offline-token';
        });
    }
}
