<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

class ApiTokenService
{
    private $client;

    public function __construct()
    {
        $this->client = Client::query()->where('password_client', true)->first();
    }

    public function issueToken(Request $request)
    {
        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => '',
            'username' => $request->email,
            'password' => $request->password,
        ];

        $request->request->add($params);

        $proxy = Request::create(url('oauth/token'), 'POST');

        return Route::dispatch($proxy);
    }
}
