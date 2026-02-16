<?php

namespace Modules\RegOnline\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LoginService
{
    private $url;
    private $id;
    private $pass;
    private $ref;

    public function __construct()
    {
        $this->url  = config('regonline.url');
        $this->id   = config('regonline.x_id');
        $this->pass = config('regonline.x_pass');
        $this->ref  = config('regonline.ref');
    }

    public function token()
    {
        return Cache::remember('simrs_token', 3300, function () {

            $response = Http::withHeaders([
                    'Accept' => 'application/json',
                    'x-username'  => $this->id,
                    'x-password'=> $this->pass
                ])
                ->get($this->url.'/registrasionline/rsonline/getToken');

            $json = $response->json();

            if (!isset($json['response']['token'])) {
                throw new \Exception('Token tidak ditemukan dari SIMRS');
            }

            return $json['response']['token'];
        });
    }
}
