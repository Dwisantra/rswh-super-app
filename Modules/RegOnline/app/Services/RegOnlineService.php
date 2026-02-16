<?php

namespace Modules\RegOnline\Services;

use Illuminate\Support\Facades\Http;

class RegOnlineService
{
    protected $login;
    protected $url;
    protected $id;
    protected $pass;

    public function __construct(LoginService $login)
    {
        $this->login = $login;
        $this->url  = config('regonline.url');
        $this->id   = config('regonline.x_id');
        $this->pass = config('regonline.x_pass');
    }

    private function headers()
    {
        $token = $this->login->token();
        return [
            'Accept' => 'application/json',
            'X-Token' => $token,
        ];
    }

    public function getPoli()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->url.'/registrasionline/rsonline/getReferensiPoli');

        return $response->json();
    }

    public function testConnection()
    {
        $response = Http::withHeaders($this->headers())
            ->get($this->url.'/registrasionline/rsonline/getReferensiPoli');

        return [
            'status' => $response->status(),
            'body' => $response->body()
        ];
    }
}
