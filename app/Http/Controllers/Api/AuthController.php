<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiTokenService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(title="Autenticación", version="1.0")
 */
class AuthController extends Controller
{
    private $apiTokenService;

    public function __construct(ApiTokenService $tokenService)
    {
        $this->apiTokenService = $tokenService;
    }

    /**
     * @OA\Post(
     *      path="/api/register",
     *      summary="Registrar un usuario",
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  example={"name": "Marco", "email": "marco@test.com", "password": "123456"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Retorna el token generado."
     *      ),
     * )
     */
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
