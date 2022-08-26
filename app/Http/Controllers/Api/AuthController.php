<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiTokenService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $apiTokenService;

    public function __construct(ApiTokenService $tokenService)
    {
        $this->apiTokenService = $tokenService;
    }

    public function register(Request $request)
    {
        User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->apiTokenService->issueToken($request);
    }
}
